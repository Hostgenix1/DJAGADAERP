<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('packaging', 255)->nullable();
            $table->string('origin', 255)->nullable();
            $table->decimal('supplier_price', 15, 2)->default(0);
            $table->foreignId('currency_id')->nullable()->constrained()->nullOnDelete();
            $table->string('incoterm', 50)->nullable();
            $table->string('destination_port', 255)->nullable();
            $table->decimal('quantity', 15, 2)->nullable();
            $table->decimal('container_quantity', 15, 2)->nullable();
            $table->string('container_type', 10)->nullable();
            $table->date('date_received');
            $table->date('valid_until')->nullable();
            $table->string('source', 20)->default('other');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('selling_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_price_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('packaging', 255)->nullable();
            $table->decimal('supplier_cost', 15, 2)->default(0);
            $table->decimal('margin_pct', 6, 3)->nullable();
            $table->decimal('margin_amount', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->foreignId('currency_id')->nullable()->constrained()->nullOnDelete();
            $table->string('destination', 255)->nullable();
            $table->string('incoterm', 50)->nullable();
            $table->decimal('min_qty', 15, 2)->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status', 20)->default('draft');
            $table->boolean('approved_for_ai')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selling_prices');
        Schema::dropIfExists('supplier_prices');
    }
};
