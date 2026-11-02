<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Performance optimization: foreign keys and hot filter columns on the
     * CRM and document tables were created without indexes, so every join and
     * filtered query in those modules was a table scan.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('brand_id');
            $table->index('category_id');
            $table->index('supplier_id');
            $table->index('currency_id');
            $table->index('tax_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('currency_id');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->index('customer_id');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('owner_id');
            $table->index('currency_id');
        });

        Schema::table('communications', function (Blueprint $table) {
            $table->index(['communicable_type', 'communicable_id']);
            $table->index('occurred_at');
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->index(['followable_type', 'followable_id']);
            $table->index('due_date');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['currency_id']);
            $table->dropIndex(['tax_id']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['currency_id']);
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['owner_id']);
            $table->dropIndex(['currency_id']);
        });

        Schema::table('communications', function (Blueprint $table) {
            $table->dropIndex(['communicable_type', 'communicable_id']);
            $table->dropIndex(['occurred_at']);
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropIndex(['followable_type', 'followable_id']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['assigned_to']);
        });
    }
};
