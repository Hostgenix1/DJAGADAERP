<?php

namespace App\Support;

use App\Models\Unit;

/**
 * Global units-of-measure list, configurable from Settings.
 * Falls back to config/invoice.php when the table is empty.
 */
class Units
{
    public static function all(): array
    {
        $units = Unit::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        if (empty($units)) {
            return config('invoice.units', []);
        }

        return $units;
    }
}
