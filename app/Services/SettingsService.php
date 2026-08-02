<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function get(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function bulkSet(array $values): void
    {
        foreach ($values as $key => $value) {
            if ($key !== '_token' && $key !== '_method' && $value !== null) {
                $this->set($key, $value);
            }
        }
    }
}