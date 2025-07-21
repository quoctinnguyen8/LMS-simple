<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        {{ $attributes['title'] ? $attributes['title'] . ' - ' : '' }}{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}
    </title>

    {{-- SEO Meta Tags --}}
    <x-seo ogTitle="{{ $attributes['ogTitle'] ?? App\Helpers\SettingHelper::get('seo_title', 'Chưa cập nhật') }}"
        ogDescription="{{ $attributes['ogDescription'] ?? App\Helpers\SettingHelper::get('seo_description', 'Chưa cập nhật') }}"
        ogImage="{{ $attributes['ogImage'] ?? asset('storage/' . App\Helpers\SettingHelper::get('seo_image')) }}" />
    <link rel="icon" href="{{ asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- Custom CSS từ settings --}}
    @if (App\Helpers\SettingHelper::get('custom_css'))
        <style>
            {!! App\Helpers\SettingHelper::get('custom_css') !!}
        </style>
    @endif
</head>

<body>
    <div class="container">
        <header>
            <nav>
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}"
                            alt="{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}"
                            class="logo-image" style="max-height: 100px;">
                    </a>
                </div>
                <input type="checkbox" id="menu-toggle" class="hidden">
                <label for="menu-toggle" class="menu-button">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7">
                        </path>
                    </svg>
                </label>
                <ul class="desktop-menu">
                    <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle {{ request()->routeIs('courses.index') || request()->routeIs('courses.category') || request()->routeIs('courses.show') ? 'active' : '' }}">Khóa
                            học</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('courses.index') }}"
                                    class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">Tất cả khóa
                                    học</a>
                            </li>
                            @foreach (App\Models\Category::all() as $Category)
                                <li><a href="{{ route('courses.category', $Category->slug) }}"
                                        class="course-category {{ request()->routeIs('courses.category') && request()->route('slug') == $Category->slug ? 'active' : '' }}">{{ $Category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="{{ route('rooms.index') }}"
                            class="{{ request()->routeIs('rooms.index') || request()->routeIs('rooms.show') ? 'active' : '' }}">Phòng
                            học</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle {{ request()->routeIs('news.index') || request()->routeIs('news.category') || request()->routeIs('news.show') ? 'active' : '' }}">Tin
                            tức</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('news.index') }}"
                                    class="{{ request()->routeIs('news.index') ? 'active' : '' }}">Tin tức mới nhất</a>
                            </li>
                            @foreach (App\Models\NewsCategory::all() as $newsCategory)
                                <li><a href="{{ route('news.category', $newsCategory->slug) }}"
                                        class="news-category {{ request()->routeIs('news.category') && request()->route('slug') == $newsCategory->slug ? 'active' : '' }}">{{ $newsCategory->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="{{ route('contacts') }}"
                            class="{{ request()->routeIs('contacts') ? 'active' : '' }}">Liên
                            hệ</a></li>

                </ul>
                <div class="sidebar">
                    <div class="sidebar-header">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}"
                                    alt="{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}"
                                    class="logo-image" style="max-height: 100px;">
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
                        <li class="dropdown">
                            <div class="dropdown-header">
                                <a href="{{ route('courses.index') }}"
                                    class="dropdown-toggle {{ request()->routeIs('courses.index') || request()->routeIs('courses.category') || request()->routeIs('courses.show') ? 'active' : '' }}">Khóa
                                    học</a>
                                <label for="dropdown-toggle-1" class="dropdown-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" id="dropdown-icon-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </label>
                                <input type="checkbox" id="dropdown-toggle-1" class="dropdown-toggle hidden">
                            </div>
                            <ul class="dropdown-menu" id="dropdown-menu-1">
                                <li><a href="{{ route('courses.index') }}"
                                        class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">Tất cả khóa
                                        học</a>
                                </li>
                                @foreach (App\Models\Category::all() as $Category)
                                    <li><a href="{{ route('courses.category', $Category->slug) }}"
                                            class="course-category {{ request()->routeIs('courses.category') && request()->route('slug') == $Category->slug ? 'active' : '' }}">{{ $Category->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li><a href="{{ route('rooms.index') }}"
                                class="{{ request()->routeIs('rooms.index') || request()->routeIs('rooms.show') ? 'active' : '' }}">Phòng
                                học</a>
                        <li class="dropdown">
                            <div class="dropdown-header">
                                <a href="{{ route('news.index') }} "
                                    class="dropdown-toggle {{ request()->routeIs('news.index') || request()->routeIs('news.category') || request()->routeIs('news.show') ? 'active' : '' }}">Tin
                                    tức</a>
                                <label for="dropdown-toggle-2" class="dropdown-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" id="dropdown-icon-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </label>
                                <input type="checkbox" id="dropdown-toggle-2" class="dropdown-toggle hidden">
                            </div>
                            <ul class="dropdown-menu" id="dropdown-menu-2">
                                <li><a href="{{ route('news.index') }}"
                                        class="{{ request()->routeIs('news.index') ? 'active' : '' }}">Tin tức mới
                                        nhất</a>
                                </li>
                                @foreach (App\Models\NewsCategory::all() as $newsCategory)
                                    <li><a href="{{ route('news.category', $newsCategory->slug) }}"
                                            class="news-category {{ request()->routeIs('news.category') && request()->route('slug') == $newsCategory->slug ? 'active' : '' }}">{{ $newsCategory->name }}</a>
                                @endforeach
                            </ul>
                        </li>
                        <li><a href="{{ route('contacts') }}"
                                class="{{ request()->routeIs('contacts') ? 'active' : '' }}">Liên hệ</a></li>
                    </ul>
                </div>
                <label for="menu-toggle" class="overlay"></label>
            </nav>
        </header>
        <main>
            @include('includes._notify')
            {{ $slot }}
        </main>
        <footer>
            <div class="footer-content">
                <p>&copy; {{ date('Y') }}
                    {{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}.
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
        </footer>
    </div>
    {{ $scripts ?? '' }}
    <script>
        const dropdownToggles1 = document.getElementById('dropdown-toggle-1');
        const dropdownToggles2 = document.getElementById('dropdown-toggle-2');
        const dropdownMenus1 = document.getElementById('dropdown-menu-1');
        const dropdownMenus2 = document.getElementById('dropdown-menu-2');
        const icon1 = document.getElementById('dropdown-icon-1');
        const icon2 = document.getElementById('dropdown-icon-2');
        dropdownToggles1.addEventListener('change', function() {
            if (this.checked) {
                dropdownMenus1.className += ' show-dropdown active';
                icon1.style.transform = 'rotate(180deg)';
            } else {
                dropdownMenus1.className = 'dropdown-menu';
                icon1.style.transform = 'rotate(0deg)';
            }
        });
        dropdownToggles2.addEventListener('change', function() {
            if (this.checked) {
                dropdownMenus2.className += ' show-dropdown active';
                icon2.style.transform = 'rotate(180deg)';
            } else {
                dropdownMenus2.className = 'dropdown-menu';
                icon2.style.transform = 'rotate(0deg)';
            }
        });
    </script>
    @if (App\Helpers\SettingHelper::get('custom_js'))
        <script>
            {!! App\Helpers\SettingHelper::get('custom_js') !!}
        </script>
    @endif
</body>

</html>
