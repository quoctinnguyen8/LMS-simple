<?php

namespace App\Helpers;

use App\Forms\Components\Recaptcha;
use App\Rules\RecaptchaRule;

class RecaptchaHelper
{
    /**
     * Kiểm tra reCAPTCHA có được bật không
     */
    public static function isEnabled(): bool
    {
        return config('services.recaptcha.enabled', false) && 
               !empty(config('services.recaptcha.site_key')) && 
               !empty(config('services.recaptcha.secret_key'));
    }

    /**
     * Lấy Site Key
     */
    public static function getSiteKey(): ?string
    {
        return config('services.recaptcha.site_key');
    }

    /**
     * Lấy phiên bản reCAPTCHA
     */
    public static function getVersion(): string
    {
        return config('services.recaptcha.version', 'v2');
    }

    /**
     * Tạo reCAPTCHA component cho form
     */
    public static function component(string $name = 'g-recaptcha-response'): Recaptcha
    {
        return Recaptcha::make($name)
            ->rules([new RecaptchaRule()])
            ->validationMessages([
                'required' => 'Vui lòng xác minh reCAPTCHA.',
            ]);
    }

    /**
     * Tạo validation rule
     */
    public static function rule(): RecaptchaRule
    {
        return new RecaptchaRule();
    }

    /**
     * Thêm reCAPTCHA vào schema form nếu được bật (chỉ dành cho Filament admin)
     * Chỉ sử dụng khi cần thiết trong admin panel
     */
    public static function addToSchemaIfNeeded(array $schema, string $context = ''): array
    {
        // Chỉ thêm reCAPTCHA trong các context cụ thể nếu cần
        $allowedContexts = ['admin-form']; // Có thể mở rộng nếu cần
        
        if (self::isEnabled() && in_array($context, $allowedContexts)) {
            $schema[] = self::component();
        }
        
        return $schema;
    }

    /**
     * Tạo script tag cho reCAPTCHA
     */
    public static function scriptTag(): string
    {
        if (!self::isEnabled()) {
            return '';
        }

        return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    }

    /**
     * Kiểm tra cấu hình
     */
    public static function checkConfiguration(): array
    {
        $issues = [];

        if (!config('services.recaptcha.enabled')) {
            $issues[] = 'reCAPTCHA chưa được bật (RECAPTCHA_ENABLED=false)';
        }

        if (empty(config('services.recaptcha.site_key'))) {
            $issues[] = 'Chưa cấu hình RECAPTCHA_SITE_KEY';
        }

        if (empty(config('services.recaptcha.secret_key'))) {
            $issues[] = 'Chưa cấu hình RECAPTCHA_SECRET_KEY';
        }

        return $issues;
    }
}
