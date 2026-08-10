<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained()->nullOnDelete();
            $table->date('po_date');
            $table->date('expected_delivery')->nullable();
            $table->string('status')->default('draft');
            $table->string('payment_terms', 1000)->nullable();
            $table->string('delivery_terms', 500)->nullable();
            $table->string('port_of_loading', 500)->nullable();
            $table->string('port_of_discharge', 500)->nullable();
            $table->string('goods_origin', 500)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->string('vat_mode', 20)->default('excluded');
            $table->decimal('vat_rate', 6, 3)->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->string('sub_description', 255)->nullable();
            $table->decimal('qty', 10, 2)->default(1);
            $table->string('unit', 20)->default('pc');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('tax_rate', 6, 3)->nullable();
            $table->decimal('discount_pct', 6, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};
