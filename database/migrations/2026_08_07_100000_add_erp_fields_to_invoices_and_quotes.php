<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->after('due_date');
            $table->text('payment_terms')->nullable()->after('terms');
            $table->string('delivery_terms')->nullable()->after('payment_terms');
            $table->string('port_of_loading')->nullable()->after('delivery_terms');
            $table->string('port_of_discharge')->nullable()->after('port_of_loading');
            $table->string('goods_origin')->nullable()->after('port_of_discharge');
            $table->unsignedSmallInteger('offer_valid')->nullable()->after('goods_origin');
            $table->string('vat_mode', 20)->default('excluded')->after('offer_valid');
            $table->decimal('vat_rate', 6, 3)->nullable()->after('vat_mode');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->after('valid_until');
            $table->text('payment_terms')->nullable()->after('terms');
            $table->string('delivery_terms')->nullable()->after('payment_terms');
            $table->string('port_of_loading')->nullable()->after('delivery_terms');
            $table->string('port_of_discharge')->nullable()->after('port_of_loading');
            $table->string('goods_origin')->nullable()->after('port_of_discharge');
            $table->unsignedSmallInteger('offer_valid')->nullable()->after('goods_origin');
            $table->string('vat_mode', 20)->default('excluded')->after('offer_valid');
            $table->decimal('vat_rate', 6, 3)->nullable()->after('vat_mode');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'reference_no', 'payment_terms', 'delivery_terms', 'port_of_loading',
                'port_of_discharge', 'goods_origin', 'offer_valid', 'vat_mode', 'vat_rate',
            ]);
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'reference_no', 'payment_terms', 'delivery_terms', 'port_of_loading',
                'port_of_discharge', 'goods_origin', 'offer_valid', 'vat_mode', 'vat_rate',
            ]);
        });
    }
};
