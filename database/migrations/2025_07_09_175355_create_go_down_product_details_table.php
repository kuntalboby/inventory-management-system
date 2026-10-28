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
        Schema::create('go_down_product_details', function (Blueprint $table) {
             $table->id();
             $table->foreignId('godown_product_id')
            ->references('id')->on('go_down_products')->onDelete('cascade');
             $table->float('quantity');
             $table->foreignId('unit_id')
            ->references('id')->on('units')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('go_down_product_details');
    }
};
