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
        Schema::create('master_records', function (Blueprint $table) {
            $table->id();
            $table->timestamp('record_date');
            $table->foreignId('go_down_product_id')
            ->references('id')->on('go_down_products')->onDelete('cascade');
            $table->float('opening_stock_quantity');
            $table->float('add_stock_quantity');
            $table->float('sold_stock_quantity');
            $table->float('damaged_stock_quantity');
            $table->float('closing_stock_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_records');
    }
};
