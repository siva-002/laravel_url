<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePrices extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function StripeProduct()
    {
        return $this->belongsTo(StripeProduct::class, 'product_id', 'product_id');
    }

    public function getProductName()
    {
        // return $this->product_id;
        return StripeProduct::where('product_id', $this->product_id)->first()->plan_type;
    }

    public function CheckDefaultPrice()
    {
        $defaultPrice = StripeProduct::where('product_id', $this->product_id)->first()->price_id;
        if ($defaultPrice == $this->price_id) {
            return '<div style="width:30px">
            <i class="badge fa fa-star" style="display:flex;justify-content:center;background:goldenrod;"></i></div>';
        }
        return "";
    }
    public function formatPrice()
    {

        return "<i class='fa fa-dollar'></i> <b>$this->price</b>";
    }
    public function formatStatus()
    {
        return $this->status == 1 ? '<div style="width:30px">
            <i class="badge fa fa-check-circle" style="display:flex;justify-content:center;background:green;"></i></div>' :
            '<div style="width:30%">
            <i class="badge fa fa-times-circle" style="display:flex;justify-content:center;background:red;"></i></div>';
    }

}
