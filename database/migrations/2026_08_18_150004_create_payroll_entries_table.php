<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('period'); // e.g. 2026-08 (Y-m)
            $table->decimal('gross_salary', 15, 2)->default(0);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->foreignId('currency_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft');
            $table->date('paid_on')->nullable();
            $table->string('notes', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_entries');
    }
};