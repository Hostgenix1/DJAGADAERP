<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('currencies')->where('code', 'AED')->update(['is_default' => true]);
        DB::table('currencies')->where('code', '!=', 'AED')->update(['is_default' => false]);
    }

    public function down(): void
    {
        DB::table('currencies')->where('code', 'USD')->update(['is_default' => true]);
        DB::table('currencies')->where('code', '!=', 'USD')->update(['is_default' => false]);
    }
};
