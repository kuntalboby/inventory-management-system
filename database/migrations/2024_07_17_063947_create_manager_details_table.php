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
        Schema::create('manager_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('personal_email')->nullable();
            $table->string('personal_phone_one')->nullable();
            $table->string('personal_phone_two')->nullable();
            $table->string('complete_address')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('designation')->nullable();
            $table->enum('status_otp_verification', ['1','2'])->default('1')->comment('1 for Simple login , 2 for login using otp in manager panel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_details');
    }
};
