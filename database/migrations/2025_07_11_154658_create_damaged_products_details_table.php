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
        Schema::create('damaged_products_details', function (Blueprint $table) {
             $table->id();
             $table->foreignId('go_down_product_id')
            ->references('id')->on('go_down_products')->onDelete('cascade');
             $table->timestamp('damaged_date');
             $table->float('quantity');
             $table->foreignId('unit_id')
            ->references('id')->on('units')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Who took this action')
            ->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['0','1','2'])->default('0')->comment('0=>Damaged,1=>Cleared from Godown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_products_details');
    }
};
