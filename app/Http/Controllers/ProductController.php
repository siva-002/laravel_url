<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class ProductController extends Controller
{
    //
    private $stripe;
    protected $product = "prod_Qm4rULmVUe532t";
    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }
    public function getAllPrices()
    {
        $prices = $this->stripe->products->all();
        $priceData = $prices->data;
        // $data = array_map(function ($item) {
        //     // if($item->unit_amount / 100 == 5){
        //     //     $description=[];
        //     // }
        //     // else if($item->unit_amount / 100 == 5){
        //     //     $description=[];
        //     // }
        //     return ["price_id" => $item->id, "price" => '$' . $item->unit_amount / 100, "name" => $item->nickname];
        // }, $priceData);
        return response()->json(["status" => 200, "data" => $priceData]);

    }
}
