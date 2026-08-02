<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 255);
            $table->string('contact_name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->enum('source', ['website', 'referral', 'cold_call', 'marketing', 'trade_show', 'social_media', 'other'])->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'])->default('new');
            $table->decimal('expected_amount', 15, 2)->nullable();
            $table->foreignId('currency_id')->nullable();
            $table->date('expected_date')->nullable();
            $table->foreignId('owner_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};