<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    /** @var array<string, string|null> */
    private array $cache = [];

    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key] ?? $default;
        }

        $value = Setting::where('key', $key)->value('value');
        $this->cache[$key] = $value;

        return $value ?? $default;
    }

    public function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        $this->cache[$key] = $value;
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
