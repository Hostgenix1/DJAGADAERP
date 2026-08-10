<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('tax_rate', 8, 3)->nullable()->change();
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->decimal('tax_rate', 8, 3)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('tax_rate', 8, 3)->default(0)->change();
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->decimal('tax_rate', 8, 3)->default(0)->change();
        });
    }
};
