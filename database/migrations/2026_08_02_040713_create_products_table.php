<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 255)->unique();
            $table->string('name', 255);
            $table->foreignId('brand_id');
            $table->foreignId('category_id');
            $table->foreignId('supplier_id')->nullable();
            $table->decimal('buy_price', 15, 2)->nullable();
            $table->decimal('sell_price', 15, 2)->nullable();
            $table->foreignId('currency_id')->nullable();
            $table->foreignId('tax_id')->nullable();
            $table->string('unit', 255)->nullable();
            $table->integer('pack_qty')->nullable();
            $table->enum('pack_type', ['carton', 'box', 'unit', 'pallet'])->nullable();
            $table->decimal('weight_kg', 10, 3)->nullable();
            $table->string('dimensions', 255)->nullable();
            $table->longText('specifications')->nullable();
            $table->longText('certificates')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};