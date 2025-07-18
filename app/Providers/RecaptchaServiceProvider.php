<?php

namespace App\Providers;

use App\Forms\Components\Recaptcha;
use Filament\Forms\Components\Field;
use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Nếu reCAPTCHA không được cấu hình đúng thì show thông báo lỗi
        if (!config('services.recaptcha.site_key') || !config('services.recaptcha.secret_key')) {
            throw new \Exception('Cấu hình reCAPTCHA không hợp lệ. Vui lòng kiểm tra lại file [.env], tham khảo cấu hình từ file .env.example.');
        }

        // Đăng ký macro để sử dụng component dễ dàng hơn
        Field::macro('recaptcha', function (string $name = 'g-recaptcha-response') {
            return Recaptcha::make($name);
        });
    }
}
