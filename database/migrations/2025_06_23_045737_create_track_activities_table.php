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
        Schema::create('track_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->references('id')->on('users')->onDelete('cascade');
            $table->longText('note');
            $table->enum('status', ['0', '1'])->default('0')->comment('0=>visited,1=>notvisited');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_activities');
    }
};
