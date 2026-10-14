<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 30)->nullable();
            $table->string('location', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('notes', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            // qty is signed: positive = stock in, negative = stock out/adjustment down
            $table->decimal('qty', 15, 2);
            $table->string('type', 20)->default('in'); // in | out | adjustment
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->string('reference', 100)->nullable();
            $table->date('movement_date');
            $table->string('notes', 500)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'warehouse_id']);
            $table->index('movement_date');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('reorder_level', 15, 2)->nullable()->after('sell_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('reorder_level');
        });
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('warehouses');
    }
};
