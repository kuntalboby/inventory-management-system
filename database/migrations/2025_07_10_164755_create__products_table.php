<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
             $table->id();
             $table->string('name');
             $table->foreignId('go_down_product_id')
            ->references('id')->on('go_down_products')->onDelete('cascade');
             $table->string('product_code');
             $table->string('slug')->unique();
             $table->float('packet_size');
             $table->foreignId('unit_id')
            ->references('id')->on('units')->onDelete('cascade');
             $table->float('quantity');
             $table->float('selling_price');
             $table->float('gst')->default(0.00);
             $table->longText('description')->nullable();
             $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_products');
    }
};
