# Hệ thống quản lý Settings

## Giới thiệu
Hệ thống quản lý cài đặt thông tin trung tâm bao gồm các chức năng:
- Tên trung tâm
- Địa chỉ
- Số điện thoại liên hệ
- Email
- Ảnh logo (file upload)
- Giới thiệu trung tâm (Rich text editor)
- Nhúng bản đồ Google Maps
- Nhúng fanpage Facebook
- Nhúng Zalo
- CSS tùy chỉnh
- JavaScript tùy chỉnh

## Cách sử dụng

### 1. Quản lý qua Filament Admin
- Truy cập admin panel Filament
- Vào menu "Cài đặt hệ thống"
- Điền thông tin và lưu

### 2. Sử dụng trong code

#### Sử dụng Helper Class:
```php
use App\Helpers\SettingHelper;

// Lấy một setting
$centerName = SettingHelper::get('center_name', 'Default name');

// Lấy tất cả settings
$allSettings = SettingHelper::all();

// Lấy thông tin hệ thống
$systemInfo = SettingHelper::getSystemInfo();

// Cập nhật setting
SettingHelper::set('center_name', 'Tên mới');
```

#### Sử dụng Facade:
```php
use App\Facades\Setting;

$centerName = Setting::get('center_name');
```

#### Sử dụng trong Blade template:
```php
{{ App\Helpers\SettingHelper::get('center_name') }}
```

#### Sử dụng Component:
```html
<x-system-settings />
```

### 3. Các setting keys có sẵn:
- `center_name`: Tên trung tâm
- `address`: Địa chỉ
- `phone`: Số điện thoại
- `email`: Email
- `logo`: Đường dẫn logo
- `description`: Giới thiệu trung tâm (HTML)
- `google_map`: Mã nhúng Google Maps
- `facebook_fanpage`: Mã nhúng Facebook Fanpage
- `zalo_embed`: Mã nhúng Zalo
- `custom_css`: CSS tùy chỉnh
- `custom_js`: JavaScript tùy chỉnh

### 4. Cache
Hệ thống sử dụng cache để tối ưu hiệu suất:
- Cache tự động khi lấy dữ liệu
- Tự động clear cache khi cập nhật
- Có thể clear cache thủ công: `SettingHelper::clearCache()`

## File được tạo

### Backend Files:
1. `app/Filament/Pages/SystemSettings.php` - Trang quản lý Filament
2. `app/Helpers/SettingHelper.php` - Helper class
3. `app/Facades/Setting.php` - Facade
4. `app/View/Components/SystemSettings.php` - Component class

### Frontend Files:
1. `resources/views/filament/pages/system-settings.blade.php` - View Filament
2. `resources/views/components/system-settings.blade.php` - Component view
3. `resources/views/settings-demo.blade.php` - Demo usage

### Updated Files:
1. `app/Providers/AppServiceProvider.php` - Đăng ký facade

## Logic xử lý
- Nếu có key trong database: UPDATE
- Nếu không có key: INSERT
- Sử dụng `updateOrCreate()` method của Eloquent
