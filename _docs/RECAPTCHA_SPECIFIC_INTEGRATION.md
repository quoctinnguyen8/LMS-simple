# reCAPTCHA Integration - Chỉ cho Form Đặt phòng và Đăng ký khóa học

## Tổng quan
reCAPTCHA v2 đã được tích hợp **có chọn lọc** vào hệ thống LMS, chỉ áp dụng cho:
1. **Form đặt phòng** (`/rooms/{id}`)
2. **Form đăng ký khóa học** (`/courses/{id}`)

## Tính năng

### ✅ Có reCAPTCHA
- Form đặt phòng (frontend)
- Form đăng ký khóa học (frontend)

### ❌ KHÔNG có reCAPTCHA
- Trang chủ
- Danh sách khóa học/phòng học
- Admin panel Filament
- Bất kỳ form nào khác trong hệ thống

## Cấu hình

### 1. File .env
```env
# Bật reCAPTCHA
RECAPTCHA_ENABLED=true

# Keys từ Google reCAPTCHA Console
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here

# Version (v2 checkbox)
RECAPTCHA_VERSION=v2
```

### 2. Tạo reCAPTCHA Site
1. Truy cập: https://www.google.com/recaptcha/admin/create
2. Chọn **reCAPTCHA v2** > **"I'm not a robot" Checkbox**
3. Thêm domains: `localhost`, `yourdomain.com`
4. Lấy Site Key và Secret Key

## Cách hoạt động

### Form đặt phòng
- **Route**: `POST /rooms/bookings` (name: `rooms.bookings`)
- **View**: `resources/views/rooms/detail.blade.php`
- **Request**: `App\Http\Requests\RoomRequest`
- **Component**: `<x-recaptcha form-type="room-booking" />`

### Form đăng ký khóa học
- **Route**: `POST /courses/registration` (name: `courses.registration`)
- **View**: `resources/views/courses/detail.blade.php`
- **Request**: `App\Http\Requests\CourseRequest`
- **Component**: `<x-recaptcha form-type="course-registration" />`

## Validation Logic

### RoomRequest.php
```php
public function rules(): array
{
    $rules = [
        // ... other rules
    ];

    // Chỉ thêm reCAPTCHA cho route rooms.bookings
    if (config('services.recaptcha.enabled', false) && 
        (request()->is('rooms/*/bookings') || request()->routeIs('rooms.bookings'))) {
        $rules['g-recaptcha-response'] = ['required', new RecaptchaRule()];
    }

    return $rules;
}
```

### CourseRequest.php
```php
public function rules(): array
{
    $rules = [
        // ... other rules
    ];

    // Chỉ thêm reCAPTCHA cho route courses.registration
    if (config('services.recaptcha.enabled', false) && 
        (request()->is('courses/*/registration') || request()->routeIs('courses.registration'))) {
        $rules['g-recaptcha-response'] = ['required', new RecaptchaRule()];
    }

    return $rules;
}
```

## Component Usage

### Trong Blade view
```blade
<!-- Form đặt phòng -->
<x-recaptcha form-type="room-booking" />

<!-- Form đăng ký khóa học -->
<x-recaptcha form-type="course-registration" />
```

### Component tự động:
- Kiểm tra `RECAPTCHA_ENABLED`
- Load script Google reCAPTCHA
- Tạo unique callback functions
- Handle validation errors

## Script Loading

### Tối ưu hóa performance
- Script reCAPTCHA **chỉ** load trên 2 trang cần thiết
- **KHÔNG** load trên trang chủ hay các trang khác
- Sử dụng `async defer` để không block rendering

### Room detail page
```blade
<x-slot:scripts>
    @if(config('services.recaptcha.enabled', false))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</x-slot:scripts>
```

### Course detail page
```blade
<x-slot:scripts>
    @if(config('services.recaptcha.enabled', false))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</x-slot:scripts>
```

## Tắt reCAPTCHA

### Development mode
```env
RECAPTCHA_ENABLED=false
```

### Khi tắt:
- Form vẫn hoạt động bình thường
- Không có reCAPTCHA hiển thị
- Không có validation reCAPTCHA
- Script không được load

## CSS Styling

### Responsive design
```css
.recaptcha-wrapper {
    margin: 1rem 0;
    display: flex;
    justify-content: center;
}

.recaptcha-wrapper .g-recaptcha {
    transform: scale(0.9);
    transform-origin: 0 0;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .recaptcha-wrapper .g-recaptcha {
        transform: scale(0.8);
    }
}
```

## Kiểm tra hoạt động

### Test reCAPTCHA
```bash
php artisan recaptcha:check
```

### Manual testing
1. Truy cập `/rooms/{id}` 
2. Kiểm tra có reCAPTCHA trong form đặt phòng
3. Truy cập `/courses/{id}`
4. Kiểm tra có reCAPTCHA trong form đăng ký
5. Truy cập các trang khác → **KHÔNG** có reCAPTCHA

## Troubleshooting

### reCAPTCHA không hiển thị
- Kiểm tra `RECAPTCHA_ENABLED=true`
- Kiểm tra `RECAPTCHA_SITE_KEY`
- Kiểm tra domain trong reCAPTCHA Console
- Kiểm tra có ở đúng route `/rooms/{id}` hoặc `/courses/{id}`

### Validation lỗi
- Kiểm tra `RECAPTCHA_SECRET_KEY`
- Kiểm tra network requests
- Xem Laravel logs: `storage/logs/laravel.log`

### Performance issues
- reCAPTCHA script chỉ load khi cần
- Sử dụng `async defer`
- Không ảnh hưởng đến tốc độ trang khác
