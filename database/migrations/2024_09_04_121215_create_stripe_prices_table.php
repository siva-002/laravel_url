<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stripe_prices', function (Blueprint $table) {
            $table->id();
            $table->string("product_id");
            $table->string("price_id");
            $table->string("price");
            $table->string("status");
            $table->timestamps();
            $table->foreign('product_id')->references('product_id')->on('stripe_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_prices');
    }
};
