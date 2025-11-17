<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\SystemSetting;

/**
 * Service for accessing and casting system settings
 */
class SystemSettingService
{
    const CACHE_KEY = 'system_settings';
    const CACHE_TTL = 3600; // 1 hour

    /**
     * Get a system setting by code, with type casting and null handling
     *
     * @param string $code
     * @param mixed $default
     * @return mixed
     */
    public static function get($code, $default = null)
    {
        $settings = Cache::rememberForever(self::CACHE_KEY, function () {
            return SystemSetting::where('active', true)
                ->get()
                ->keyBy('setting_code')
                ->toArray();
        });

        if (!isset($settings[$code])) {
            return $default;
        }
        $setting = (object) $settings[$code];
        if ($setting->setting_value === null) {
            return $default;
        }

        switch ($setting->setting_value_type) {
            case 'number':
                return is_numeric($setting->setting_value) ? (float) $setting->setting_value : $default;
            case 'boolean':
                return filter_var($setting->setting_value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default;
            case 'json':
                $decoded = json_decode($setting->setting_value, true);
                return $decoded !== null ? $decoded : $default;
            case 'string':
            default:
                return $setting->setting_value;
        }
    }

    /**
     * Refresh the cache for all system settings
     */
    public static function refreshCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::rememberForever(self::CACHE_KEY, function () {
            return SystemSetting::where('active', true)
                ->get()
                ->keyBy('setting_code')
                ->toArray();
        });
    }
}
