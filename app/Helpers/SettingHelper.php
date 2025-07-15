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
            'course_unit' => self::get('course_unit', 'khóa'),
            'room_rental_unit' => self::get('room_rental_unit', 'buổi'),
            'room_unit_to_hour' => self::get('room_unit_to_hour', '1'),
        ];
    }

    /**
     * Get course unit setting
     */
    public static function getCourseUnit(): string
    {
        return self::get('course_unit', 'khóa');
    }

    /**
     * Get room rental unit setting
     */
    public static function getRoomRentalUnit(): string
    {
        return self::get('room_rental_unit', 'buổi');
    }

    /**
     * Get room unit to hour conversion rate
     */
    public static function getRoomUnitToHour(): float
    {
        return (float) self::get('room_unit_to_hour', '1');
    }

    /**
     * Convert room units to hours
     */
    public static function convertRoomUnitsToHours(float $units): float
    {
        return $units * self::getRoomUnitToHour();
    }

    /**
     * Convert hours to room units
     */
    public static function convertHoursToRoomUnits(float $hours): float
    {
        $rate = self::getRoomUnitToHour();
        return $rate > 0 ? $hours / $rate : $hours;
    }
}
