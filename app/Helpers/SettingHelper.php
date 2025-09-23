<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingHelper
{
    /**
     * Get setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
                $setting = Setting::where('setting_key', $key)->first();
                return $setting ? $setting->setting_value : $default;
            });
        } catch (\Exception $e) {
            Log::error("Error getting setting '{$key}': " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, mixed $value): void
    {
        try {
            Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
            
            Cache::forget("setting_{$key}");
        } catch (\Exception $e) {
            Log::error("Error setting '{$key}': " . $e->getMessage());
            throw $e; // Re-throw để caller biết có lỗi
        }
    }

    /**
     * Get all settings as array
     */
    public static function all(): array
    {
        try {
            return Cache::remember('all_settings', 3600, function () {
                return Setting::pluck('setting_value', 'setting_key')->toArray();
            });
        } catch (\Exception $e) {
            Log::error("Error getting all settings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        try {
            $settings = Setting::all();
            foreach ($settings as $setting) {
                Cache::forget("setting_{$setting->setting_key}");
            }
            Cache::forget('all_settings');
        } catch (\Exception $e) {
            Log::error("Error clearing settings cache: " . $e->getMessage());
            // Không throw lại vì clear cache không quan trọng lắm
        }
    }

    /**
     * Get system info settings
     */
    public static function getSystemInfo(): array
    {
        try {
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
                'seo_description' => self::get('seo_description', ''),
                'seo_title' => self::get('seo_title', ''),
                'seo_image' => self::get('seo_image', ''),
                'seo_keywords' => self::get('seo_keywords', ''),
                'sitemap_file' => self::get('sitemap_file', ''),
                'ga_head' => self::get('ga_head', ''),
                'ga_body' => self::get('ga_body', ''),
                'feedback' => self::get('feedback', ''),
                'youtube_embed' => self::get('youtube_embed', ''), 
            ];
        } catch (\Exception $e) {
            Log::error("Error getting system info: " . $e->getMessage());
            return [
                'center_name' => '',
                'address' => '',
                'phone' => '',
                'email' => '',
                'logo' => '',
                'description' => '',
                'google_map' => '',
                'facebook_fanpage' => '',
                'zalo_embed' => '',
                'custom_css' => '',
                'custom_js' => '',
                'course_unit' => 'khóa',
                'room_rental_unit' => 'buổi',
                'room_unit_to_hour' => '1',
                'seo_description' => '',
                'seo_title' => '',
                'seo_image' => '',
                'feedback' => '',
                'seo_keywords' => '',
                'sitemap_file' => '',
                'ga_head' => '',
                'ga_body' => '',
                'youtube_embed' => '',
            ];
        }
    }

    /**
     * Get course unit setting
     */
    public static function getCourseUnit(): string
    {
        try {
            return self::get('course_unit', 'khóa');
        } catch (\Exception $e) {
            Log::error("Error getting course unit: " . $e->getMessage());
            return 'khóa';
        }
    }

    /**
     * Get room rental unit setting
     */
    public static function getRoomRentalUnit(): string
    {
        try {
            return self::get('room_rental_unit', 'buổi');
        } catch (\Exception $e) {
            Log::error("Error getting room rental unit: " . $e->getMessage());
            return 'buổi';
        }
    }

    /**
     * Get room unit to hour conversion rate
     */
    public static function getRoomUnitToHour(): float
    {
        try {
            return (float) self::get('room_unit_to_hour', '1');
        } catch (\Exception $e) {
            Log::error("Error getting room unit to hour: " . $e->getMessage());
            return 1.0;
        }
    }

    /**
     * Convert room units to hours
     */
    public static function convertRoomUnitsToHours(float $units): float
    {
        try {
            return $units * self::getRoomUnitToHour();
        } catch (\Exception $e) {
            Log::error("Error converting room units to hours: " . $e->getMessage());
            return $units; // Fallback: trả về giá trị gốc
        }
    }

    /**
     * Convert hours to room units
     */
    public static function convertHoursToRoomUnits(float $hours): float
    {
        try {
            $rate = self::getRoomUnitToHour();
            return $rate > 0 ? $hours / $rate : $hours;
        } catch (\Exception $e) {
            Log::error("Error converting hours to room units: " . $e->getMessage());
            return $hours; // Fallback: trả về giá trị gốc
        }
    }
}
