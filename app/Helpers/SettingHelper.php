<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingHelper
{
    /**
     * Get setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('setting_key', $key)->first();
            return $setting ? $setting->setting_value : $default;
        });
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value]
        );
        
        Cache::forget("setting_{$key}");
    }

    /**
     * Get all settings as array
     */
    public static function all(): array
    {
        return Cache::remember('all_settings', 3600, function () {
            return Setting::pluck('setting_value', 'setting_key')->toArray();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        $settings = Setting::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->setting_key}");
        }
        Cache::forget('all_settings');
    }

    /**
     * Get system info settings
     */
    public static function getSystemInfo(): array
    {
        return [
            'center_name' => self::get('center_name', ''),
            'address' => self::get('address', ''),
            'phone' => self::get('phone', ''),
            'email' => self::get('email', ''),
            'logo' => self::get('logo', ''),
            'description' => self::get('description', ''),
            'google_map' => self::get('google_map', ''),
            'facebook_fanpage' => self::get('facebook_fanpage', ''),
            'zalo_embed' => self::get('zalo_embed', ''),
            'custom_css' => self::get('custom_css', ''),
            'custom_js' => self::get('custom_js', ''),
        ];
    }
}
