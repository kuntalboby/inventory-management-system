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
        Schema::create('product_vendors', function (Blueprint $table) {
            $table->id();
            $table->timestamp('purchase_date');
            $table->timestamp('expiry_date');
            $table->foreignId('go_down_product_id')
            ->references('id')->on('go_down_products')->onDelete('cascade');
             $table->foreignId('vendor_id')
            ->references('id')->on('vendors')->onDelete('cascade');
            $table->float('buying_price');
            $table->float('stock_quantity');
            $table->foreignId('unit_id')
            ->references('id')->on('units')->onDelete('cascade');
             $table->string('description')->nullable();
            $table->enum('is_deleted', ['0', '1'])->default('0')->comment('0=>No,1=>Yes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_vendors');
    }
};
