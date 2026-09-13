<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('transport_allowance', 15, 2)->default(0)->after('base_salary');
            $table->string('iban', 34)->nullable()->after('transport_allowance');
            $table->foreignId('user_id')->nullable()->after('iban')->constrained()->nullOnDelete();
        });

        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->decimal('base_salary', 15, 2)->default(0)->after('employee_id');
            $table->unsignedInteger('working_days')->default(30)->after('base_salary');
            $table->decimal('present_days', 6, 2)->default(0)->after('working_days');
            $table->decimal('absent_days', 6, 2)->default(0)->after('present_days');
            $table->decimal('leave_days', 6, 2)->default(0)->after('absent_days');
            $table->decimal('transport_allowance', 15, 2)->default(0)->after('leave_days');
            $table->decimal('bonus', 15, 2)->default(0)->after('transport_allowance');
            $table->decimal('other_allowances', 15, 2)->default(0)->after('bonus');
            $table->decimal('absence_deduction', 15, 2)->default(0)->after('other_allowances');
            $table->decimal('other_deductions', 15, 2)->default(0)->after('absence_deduction');
            $table->date('pay_date')->nullable()->after('paid_on');
            $table->timestamp('payslip_emailed_at')->nullable()->after('pay_date');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->dropColumn([
                'base_salary', 'working_days', 'present_days', 'absent_days', 'leave_days',
                'transport_allowance', 'bonus', 'other_allowances', 'absence_deduction',
                'other_deductions', 'pay_date', 'payslip_emailed_at',
            ]);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['transport_allowance', 'iban']);
        });
    }
};
