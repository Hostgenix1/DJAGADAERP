<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->string('previous_status', 30)->nullable()->after('amount');
        });

        Schema::table('supplier_bill_payments', function (Blueprint $table) {
            $table->string('previous_status', 30)->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('previous_status');
        });

        Schema::table('supplier_bill_payments', function (Blueprint $table) {
            $table->dropColumn('previous_status');
        });
    }
};
