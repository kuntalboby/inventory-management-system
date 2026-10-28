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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamp('order_date');
            $table->foreignId('customer_id')->comment('who placed the order')
            ->references('id')->on('customers')->onDelete('cascade');

            $table->foreignId('manager_id')->comment('Who placed the order (Manager detls) ')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('extra_gst')->nullable();
            $table->string('total_amount');
            $table->text('comment')->nullable();
            $table->enum('status', ['0', '1','2'])->default('1')->comment('0=>Incomplted,1=>Complete ,2=>Hold');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
