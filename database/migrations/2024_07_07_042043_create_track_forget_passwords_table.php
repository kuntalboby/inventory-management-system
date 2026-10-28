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
        Schema::create('track_forget_passwords', function (Blueprint $table) {
 
            $table->id();
            $table->string('role')->comment('for user type');
            $table->bigInteger('applicante_identifier_digit');
            $table->string('randnumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_forget_passwords');
    }
};
