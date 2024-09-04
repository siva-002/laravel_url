<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function StripePrices()
    {
        return $this->hasMany(StripePrices::class, 'product_id', 'product_id');
    }

    public function getPrice()
    {
        // dd($this->StripePrices());
        $price = optional(StripePrices::where("price_id", $this->price_id))->first()->price ?? null;
        return $price ? "<i class='fa fa-dollar'></i><b>$price</b>" : "<i>Price not set</i>";
    }

    public function formatStatus()
    {
        return $this->status ? '<div style="width:30px;">
            <i class="badge fa fa-check-circle" style="display:flex;justify-content:center;background:green;"></i></div>' :
            '<div style="width:30px">
            <i class="badge fa fa-times-circle" style="display:flex;justify-content:center;background:red;"></i></div>';
    }
}
