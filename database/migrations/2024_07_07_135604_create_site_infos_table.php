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
        Schema::create('site_infos', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_thum_logo')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_contact')->nullable();
            $table->string('site_phone')->nullable();
            $table->string('site_fax')->nullable();
            $table->string('site_copyright')->nullable();
            $table->string('site_addressline_1')->nullable();
            $table->string('site_addressline_2')->nullable();
            $table->string('site_state')->nullable();
            $table->string('site_city')->nullable();
            $table->string('site_suburb')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_infos');
    }
};
