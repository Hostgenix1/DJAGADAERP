<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The product create form allows submitting without a brand or category
     * (validation marks both optional), but the database columns were NOT NULL
     * — causing a 500 error instead of a clean save. Make them optional to
     * match the validated form contract.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->change();
            $table->foreignId('category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable(false)->change();
            $table->foreignId('category_id')->nullable(false)->change();
        });
    }
};
