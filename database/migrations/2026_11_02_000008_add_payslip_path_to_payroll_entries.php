<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stores the generated final payslip PDF (private disk) once payroll is
     * marked paid, so the employee portal and HR reprints serve a stable file
     * and a complete payslip history is kept.
     */
    public function up(): void
    {
        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->string('payslip_path')->nullable()->after('payslip_emailed_at');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->dropColumn('payslip_path');
        });
    }
};
