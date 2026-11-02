<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Structured product dimensions: separate length / width / height values
     * so they can be entered precisely. The free-text `dimensions` column
     * stays as the printable summary, filled automatically by ProductService.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('length_cm', 10, 2)->nullable()->after('dimensions');
            $table->decimal('width_cm', 10, 2)->nullable()->after('length_cm');
            $table->decimal('height_cm', 10, 2)->nullable()->after('width_cm');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['length_cm', 'width_cm', 'height_cm']);
        });
    }
};
