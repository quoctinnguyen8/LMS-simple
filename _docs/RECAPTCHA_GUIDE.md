# reCAPTCHA Integration Guide

## Tổng quan
Hệ thống reCAPTCHA v2 (checkbox) đã được tích hợp vào LMS với khả năng tái sử dụng cao. Bạn có thể dễ dàng thêm reCAPTCHA vào bất kỳ form nào trong Filament.

## Cấu hình

### 1. Tạo reCAPTCHA Site
1. Truy cập: https://www.google.com/recaptcha/admin/create
2. Chọn **reCAPTCHA v2** > **"I'm not a robot" Checkbox**
3. Thêm domain của bạn (ví dụ: `localhost`, `yourdomain.com`)
4. Lấy **Site Key** và **Secret Key**

### 2. Cấu hình Environment
Thêm vào file `.env`:
```bash
# Bật reCAPTCHA
RECAPTCHA_ENABLED=true

# Keys từ Google reCAPTCHA
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here

# Cấu hình tùy chọn
RECAPTCHA_VERSION=v2
RECAPTCHA_MIN_SCORE=0.5
```

### 3. Clear Cache
```bash
php artisan config:clear
```

## Sử dụng

### Cách 1: Sử dụng Helper (Khuyên dùng)
```php
use App\Helpers\RecaptchaHelper;

// Trong form schema
$schema = [
    // ... các field khác
];

// Thêm reCAPTCHA nếu được bật
$schema = RecaptchaHelper::addToSchema($schema);
```

### Cách 2: Sử dụng Component trực tiếp
```php
use App\Forms\Components\Recaptcha;
use App\Rules\RecaptchaRule;

// Trong form schema
Forms\Components\Section::make('Form của bạn')
    ->schema([
        // ... các field khác
        
        Recaptcha::make('g-recaptcha-response')
            ->label('Xác minh bảo mật')
            ->rules([new RecaptchaRule()])
            ->validationMessages([
                'required' => 'Vui lòng xác minh reCAPTCHA.',
            ]),
    ])
```

### Cách 3: Sử dụng Helper component
```php
use App\Helpers\RecaptchaHelper;

// Trong form schema
RecaptchaHelper::component('g-recaptcha-response'),
```

## Tùy chỉnh

### Themes và Sizes
```php
Recaptcha::make('g-recaptcha-response')
    ->theme('dark') // 'light' hoặc 'dark'
    ->size('compact'), // 'normal' hoặc 'compact'
```

### Validation tùy chỉnh
```php
use App\Rules\RecaptchaRule;

TextInput::make('some_field')
    ->rules([
        'required',
        new RecaptchaRule(),
        // ... các rule khác
    ])
```

## Kiểm tra cấu hình

### Command kiểm tra
```bash
php artisan recaptcha:test
```

### Kiểm tra trong code
```php
use App\Helpers\RecaptchaHelper;

// Kiểm tra có bật không
if (RecaptchaHelper::isEnabled()) {
    // reCAPTCHA đã được cấu hình và bật
}

// Kiểm tra các vấn đề cấu hình
$issues = RecaptchaHelper::checkConfiguration();
if (!empty($issues)) {
    // Có vấn đề cấu hình
    foreach ($issues as $issue) {
        echo $issue . "\n";
    }
}
```

## Các file liên quan

### Core Files
- `app/Rules/RecaptchaRule.php` - Validation rule
- `app/Forms/Components/Recaptcha.php` - Form component
- `app/Helpers/RecaptchaHelper.php` - Helper utilities
- `resources/views/forms/components/recaptcha.blade.php` - View template

### Configuration
- `config/services.php` - Cấu hình reCAPTCHA
- `.env` - Environment variables

### Auth Integration
- `app/Filament/Pages/Auth/Login.php` - Custom login page
- `app/Providers/Filament/AdminPanelProvider.php` - Panel configuration

### Testing
- `app/Console/Commands/TestRecaptcha.php` - Test command

## Troubleshooting

### reCAPTCHA không hiển thị
1. Kiểm tra `RECAPTCHA_ENABLED=true`
2. Kiểm tra Site Key và Secret Key
3. Chạy `php artisan config:clear`
4. Kiểm tra console browser có lỗi JavaScript không

### Validation luôn fail
1. Kiểm tra Secret Key đúng chưa
2. Kiểm tra domain đã được thêm vào reCAPTCHA site chưa
3. Kiểm tra internet connection

### Performance
- reCAPTCHA script chỉ load khi cần thiết
- Component có caching để tránh render nhiều lần
- Timeout 10 giây cho API call

## Bảo mật
- Secret Key chỉ sử dụng server-side
- Site Key có thể public
- IP address được gửi kèm để validation
- Có logging cho các lỗi validation
