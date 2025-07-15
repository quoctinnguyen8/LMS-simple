{{--
*
* Trang này là một ví dụ về cách hiển thị thông tin cài đặt hệ thống,
* bao gồm tên trung tâm, mô tả, địa chỉ, điện thoại, email, bản đồ Google, fanpage Facebook và Zalo.
* Bạn có thể tùy chỉnh các phần này trong trang quản trị.
* 
* Trang chỉ có mục đích demo cách sử dụng source code.
* Không được sử dụng trong trong bất kỳ controller/action nào.
* 
--}}

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</title>
    
    {{-- Custom CSS từ settings --}}
    @if(App\Helpers\SettingHelper::get('custom_css'))
        <style>
            {!! App\Helpers\SettingHelper::get('custom_css') !!}
        </style>
    @endif
</head>
<body>
    <div class="container">
        <header>
            {{-- Logo --}}
            @if(App\Helpers\SettingHelper::get('logo'))
                <div class="logo-header">
                    <img src="{{ asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}" 
                         alt="{{ App\Helpers\SettingHelper::get('center_name') }}" 
                         style="max-height: 100px;">
                </div>
            @endif
            
            <h1>{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</h1>
        </header>

        <main>
            {{-- Sử dụng component --}}
            <x-system-settings />
            
            {{-- Hoặc hiển thị từng phần riêng biệt --}}
            <section class="contact-info">
                <h2>Thông tin liên hệ</h2>
                <p><strong>Địa chỉ:</strong> {{ App\Helpers\SettingHelper::get('address') }}</p>
                <p><strong>Điện thoại:</strong> {{ App\Helpers\SettingHelper::get('phone') }}</p>
                <p><strong>Email:</strong> {{ App\Helpers\SettingHelper::get('email') }}</p>
            </section>

            {{-- Giới thiệu --}}
            @if(App\Helpers\SettingHelper::get('description'))
                <section class="description">
                    <h2>Giới thiệu</h2>
                    <div class="content">
                        {!! App\Helpers\SettingHelper::get('description') !!}
                    </div>
                </section>
            @endif
        </main>
    </div>

    {{-- Custom JavaScript từ settings --}}
    @if(App\Helpers\SettingHelper::get('custom_js'))
        <script>
            {!! App\Helpers\SettingHelper::get('custom_js') !!}
        </script>
    @endif
</body>
</html>
