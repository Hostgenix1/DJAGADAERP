<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('nationality', 100)->nullable()->after('date_of_birth');
            $table->string('emirates_id', 50)->nullable()->after('nationality');
            $table->string('labour_card_no', 50)->nullable()->after('emirates_id');
            $table->string('bank_name', 100)->nullable()->after('iban');
        });

        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('leave_days');
            $table->decimal('overtime_amount', 15, 2)->default(0)->after('overtime_hours');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'nationality', 'emirates_id', 'labour_card_no', 'bank_name']);
        });

        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->dropColumn(['overtime_hours', 'overtime_amount']);
        });
    }
};
