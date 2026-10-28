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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
             $table->string('name');
             $table->string('slug')->unique();
             $table->enum('status', ['0', '1'])->default('0')->comment('0=>Active,1=>Inactive');
            $table->enum('is_deleted', ['0', '1'])->default('0')->comment('0=>No,1=>Yes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
