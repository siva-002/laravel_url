<?php

namespace App\Http\Controllers;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use App\Models\StripePrices;
use App\Models\StripeProduct;


class StripeSyncController extends Controller
{
    //
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }
    public function syncProducts()
    {
        $products = $this->stripe->products->all();
        // StripePrices::query()->delete();
        StripeProduct::truncate();
        foreach ($products->data as $product) {
            StripeProduct::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'description' => $product->description,
                    'plan_type' => $product->name,
                    'price_id' => $product->default_price, // Amount in dollars
                    "status" => $product->active,
                    // 'currency' => $price->currency,
                    // 'active' => $price->active,
                ]
            );
        }
    }
    public function syncPrices()
    {
        $prices = $this->stripe->prices->all();
        // $product = $stripe->product->all(['id' => $this->product]);

        StripePrices::truncate();
        foreach ($prices->data as $price) {
            StripePrices::updateOrCreate(
                ['price_id' => $price->id],
                [
                    'product_id' => $price->product, // Amount in dollars
                    'price' => $price->unit_amount / 100,
                    "status" => $price->active,
                    // 'currency' => $price->currency,
                    // 'active' => $price->active,
                ]
            );
        }
    }
}
