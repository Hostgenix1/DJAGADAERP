<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pack Types become a settings-managed list (like Units of Measure):
     * a dedicated table seeded with the historical enum values, and the
     * products.pack_type column relaxed from a fixed enum to a string so
     * custom pack types can be saved without schema changes.
     */
    public function up(): void
    {
        Schema::create('pack_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        foreach (['carton', 'box', 'unit', 'pallet'] as $index => $name) {
            DB::table('pack_types')->insert([
                'name' => $name,
                'is_active' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('pack_type', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('pack_type', ['carton', 'box', 'unit', 'pallet'])->nullable()->change();
        });

        Schema::dropIfExists('pack_types');
    }
};
