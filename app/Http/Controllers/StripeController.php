<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Payment;
use App\Models\Userid;
use App\Models\Stripeinfo;

class StripeController extends Controller
{
    public function stripe(Request $request)
    {
        $key = Stripeinfo::where('id', 1)->first()->stripe_key;
        $stripe = new \Stripe\StripeClient($key);
        $response = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => $request->product_name],
                        'unit_amount' => $request->product_price * 100,
                    ],
                    'quantity' => $request->quantity ?? 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);


        // dd($response);

        if (isset($response->id) && $response->id != '') {
            session()->put('product_name', $request->product_name);
            session()->put('product_quantity', $request->quantity);
            session()->put('price', $request->product_price);
            return response()->json(['id' => $response->id, 'url' => $response->url]);
        } else {
            return redirect()->route('cancel');
        }
    }

    public function success(Request $request)
    {
        if (isset($request->session_id)) {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SK_TEST_KEY'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            $payment = new Payment();
            $payment->payment_id = $response->id;
            $payment->user_id = Auth::user()->user_id;
            $payment->product_name = session()->get('product_name');
            $payment->quantity = session()->get('product_quantity');
            $payment->amount = session()->get('price');
            $payment->payer_name = $response->customer_details->name;
            $payment->payer_email = $response->customer_details->email;
            $payment->payment_status = $response->status;
            $payment->payment_method = 'Stripe';
            $payment->save();

            session()->forget('product_name');
            session()->forget('product_quantity');
            session()->forget('price');

            Userid::where('user_id', Auth::user()->user_id)->update(['user_status' => 3]);
            return redirect()->away(env('REACT_APP_URL') . 'paymentstatus?status=success');
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