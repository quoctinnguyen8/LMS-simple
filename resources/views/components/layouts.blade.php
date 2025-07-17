<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $attributes['title'] ? $attributes['title']. ' - ' : '' }}{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</title>

    {{-- SEO Meta Tags --}}
    <x-seo 
        ogTitle="{{ $attributes['ogTitle'] ?? App\Helpers\SettingHelper::get('seo_title', 'Chưa cập nhật') }}"
        ogDescription="{{ $attributes['ogDescription'] ?? App\Helpers\SettingHelper::get('seo_description', 'Chưa cập nhật') }}"
        ogImage="{{ $attributes['ogImage'] ?? asset('storage/' .App\Helpers\SettingHelper::get('seo_image')) }}"
    />
    <link rel="icon" href="{{asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- Custom CSS từ settings --}}
    @if (App\Helpers\SettingHelper::get('custom_css'))
        <style>
            {!! App\Helpers\SettingHelper::get('custom_css') !!}
        </style>
    @endif
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}"
                        alt="{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}"
                        class="logo-image"
                        style="max-height: 100px;">
                </a>
            </div>
            <input type="checkbox" id="menu-toggle" class="hidden">
            <label for="menu-toggle" class="menu-button">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </label>
            <ul class="desktop-menu">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a></li>
                <li><a href="{{ route('courses.index') }}"
                        class="{{ request()->is('courses*') ? 'active' : '' }}">Khóa học</a></li>
                <li><a href="{{ route('rooms.index') }}" class="{{ request()->is('rooms*') ? 'active' : '' }}">Phòng
                        học</a></li>
                <li><a href="{{ route('contacts') }}" class="{{ request()->is('contacts') ? 'active' : '' }}">Liên
                        hệ</a></li>
            </ul>
            <div class="sidebar">
                <div class="sidebar-header">
                    <div class="logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}"
                                alt="{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}"
                                class="logo-image"
                                style="max-height: 100px;">
                        </a>
                    </div>
                    <label for="menu-toggle" class="close-button">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </label>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a></li>
                    <li><a href="{{ route('courses.index') }}"
                            class="{{ request()->is('courses*') ? 'active' : '' }}">Khóa học</a></li>
                    <li><a href="{{ route('rooms.index') }}"
                            class="{{ request()->is('rooms*') ? 'active' : '' }}">Phòng học</a></li>
                    <li><a href="{{ route('contacts') }}" class="{{ request()->is('contacts') ? 'active' : '' }}">Liên
                            hệ</a></li>
                </ul>
            </div>
            <label for="menu-toggle" class="overlay"></label>
        </nav>
    </header>
    <main>
        {{ $slot }}
    </main>
    <footer>
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} {{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}.
                All rights reserved.</p>
            <p>Địa chỉ: {{ App\Helpers\SettingHelper::get('address', 'Chưa cập nhật') }}</p>
            <p>Điện thoại: {{ App\Helpers\SettingHelper::get('phone', 'Chưa cập nhật') }}</p>
            <p>Email: {{ App\Helpers\SettingHelper::get('email', 'Chưa cập nhật') }}</p>
        </div>
        <div class="footer-links">
            <a href="{{ route('contacts') }}">Liên hệ</a>
            <a href="{{ route('courses.index') }}">Khóa học</a>
            <a href="{{ route('rooms.index') }}">Phòng học</a>
            <a href="{{ route('home') }}">Trang chủ</a>
        </div>
        <div class="social-media">
            @if (App\Helpers\SettingHelper::get('facebook'))
                <a href="{{ App\Helpers\SettingHelper::get('facebook') }}" target="_blank" class="social-link">
                    <i class="fab fa-facebook"></i> Facebook
                </a>
            @endif
            @if (App\Helpers\SettingHelper::get('zalo'))
                <a href="{{ App\Helpers\SettingHelper::get('zalo') }}" target="_blank" class="social-link">
                    <img src="{{ asset('images/zalo-icon.png') }}" alt="Zalo" width="16"> Zalo
                </a>
            @endif
        </div>
    </footer>
    {{ $scripts ?? '' }}
    @if (App\Helpers\SettingHelper::get('custom_js'))
        <script>
            {!! App\Helpers\SettingHelper::get('custom_js') !!}
        </script>
    @endif
</body>

</html>
