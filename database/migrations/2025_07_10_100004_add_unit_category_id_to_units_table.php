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
       Schema::table('units', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_category_id')->nullable()->after('id'); // or after any specific column
            $table->foreign('unit_category_id')->references('id')->on('unit_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
        $table->dropForeign(['unit_category_id']);
        $table->dropColumn('unit_category_id');
    });
    }
};
