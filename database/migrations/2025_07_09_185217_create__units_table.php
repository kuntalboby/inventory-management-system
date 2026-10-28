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
        Schema::create('_units', function (Blueprint $table) {
             $table->id();
             $table->string('name');
             $table->string('slug')->unique();
             $table->float('conversion_factor')->nullable();
             $table->string('parent_id')->nullable()->constrained('units');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_units');
    }
};
