<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Payment;
use App\Models\Userid;
use App\Models\Stripeinfo;
use App\Models\SubscriptionModel;

class StripeController extends Controller
{
    public function stripe(Request $request)
    {
        // getting stripe key
        try {
            $key = Stripeinfo::where('id', 1)->first()->stripe_key;
            // getting choosen  product id
            // $productid = SubscriptionModel::where('plan_type', trim(strtolower($request->plan_type)))->first()->stripe_id;
            $stripe = new \Stripe\StripeClient($key);
            // $response = $stripe->checkout->sessions->create([
            //     'line_items' => [
            //         [
            //             'price_data' => [
            //                 'currency' => 'usd',
            //                 'product_data' => ['name' => $request->product_name],
            //                 'unit_amount' => $request->product_price * 100,
            //             ],
            //             'quantity' => $request->quantity ?? 1,
            //         ],
            //     ],
            //     'mode' => 'payment',
            //     'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            //     'cancel_url' => route('cancel'),
            // ]);
            $response = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        'price' => "price_1PuWhzP91NqGukxcBjRLW8Bu",
                        'quantity' => $request->quantity ?? 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);


            // print_r($response);
            if (isset($response->id) && $response->id != '') {
                // session()->put('product_name', $request->product_name);
                // session()->put('price', $request->product_price);
                return response()->json(['id' => $response->id, 'url' => $response->url, 'data' => $response]);
            } else {
                return redirect()->route('cancel');
            }
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "msg" => $e->getMessage()], 500);
        }
    }

    public function formatTimeStamp($date)
    {
        return \Carbon\Carbon::createFromTimestamp($date)->toDateString();

    }
    public function success(Request $request)
    {
        if (isset($request->session_id)) {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SK_TEST_KEY'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            dd($response);
            // getting subscription id
            $subid = $response['subscription'];
            $subscription = $stripe->subscriptions->retrieve($subid);

            // getting starting and ending period of the plan
            $currentPeriodEnd = $subscription->current_period_end;
            $expirationDate = $this->formatTimeStamp($currentPeriodEnd);
            $currentPeriodStart = $subscription->current_period_start;
            $startDate = $this->formatTimeStamp($currentPeriodStart);

            // getting invoice
            $invoiceid = $response['invoice'];
            $invoice = $stripe->invoices->retrieve($invoiceid);
            // Get the invoice PDF URL
            $invoicePdfUrl = $invoice->invoice_pdf;

            // getting payment method
            // $paymentMethod = $stripe->paymentMethods->retrieve($paymentMethodId);
            // $paymenttype=$paymentMethod->type;
            // inserting into payments table
            $payment = new Payment();
            $payment->user_id = Auth::user()->user_id ?? 'default user';
            $payment->invoice_id = $invoiceid;
            $payment->subscription_id = $subid;
            $payment->payment_id = $response->id;
            $payment->product_name = session()->get('product_name') ?? 'default name';
            $payment->amount = $response['amount_total'] / 100 . ' ' . $response['currency'];
            $payment->payer_name = $response->customer_details->name;
            $payment->payer_email = $response->customer_details->email;
            $payment->payment_status = $response->status;
            $payment->payment_method = 'Stripe';
            $payment->starting_date = $startDate;
            $payment->ending_date = $expirationDate;
            $payment->invoice_url = $invoicePdfUrl;
            $payment->save();


            $product = session()->get('product_name') ?? "default user";
            session()->forget('product_name');
            session()->forget('product_quantity');
            session()->forget('price');
            if ($product == "standard_plan") {
                Userid::where('user_id', Auth::user()->user_id)->update(['user_status' => 3]);
                return redirect()->away(env('REACT_APP_URL') . 'paymentstatus?status=success');
            } else if ($product == "premium_plan") {
                Userid::where('user_id', Auth::user()->user_id)->update(['user_status' => 4]);
                return redirect()->away(env('REACT_APP_URL') . 'paymentstatus?status=successs');
            }
            // return response()->json(["status" => 200, "message" => "Payment Success"]);
            // return 'Payment Successfull';
        }
    }

    public function cancel()
    {
        return redirect()->away(env('REACT_APP_URL') . 'paymentstatus?status=failure');
        // return response()->json(["status" => 500, "message" => "Payment Failured"]);
        // return "cancel";
    }
}