<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('tax_registration_number', 20)->nullable()->after('country');
            $table->string('emirate', 100)->nullable()->after('city');
            $table->string('po_box', 50)->nullable()->after('address');
            $table->string('postal_code', 20)->nullable()->after('po_box');

        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['tax_registration_number', 'emirate', 'po_box', 'postal_code']);
        });
    }
};
