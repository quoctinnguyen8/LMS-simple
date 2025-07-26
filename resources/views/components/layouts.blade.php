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
                <x-heroicon-o-bars-3 class="w-8 h-8" />
            </label>
            <ul class="desktop-menu">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a></li>
                <li class="dropdown">
                    <a href="{{ route('courses.index') }}"
                        class="dropdown-toggle {{ request()->routeIs('courses.index') || request()->routeIs('courses.category') || request()->routeIs('courses.show') ? 'active' : '' }}"
                        id="courses-toggle">Khóa học</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('courses.index') }}"
                                class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">Tất cả các khóa
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
                    <a href="{{ route('news.index') }}"
                        class="dropdown-toggle {{ request()->routeIs('news.index') || request()->routeIs('news.category') || request()->routeIs('news.show') ? 'active' : '' }}"
                        id="news-toggle">Tin tức - Sự kiện</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('news.index') }}"
                                class="{{ request()->routeIs('news.index') ? 'active' : '' }}">Tin tức nổi bật</a>
                        </li>
                        @foreach (App\Models\NewsCategory::all() as $newsCategory)
                            <li><a href="{{ route('news.category', $newsCategory->slug) }}"
                                    class="news-category {{ request()->routeIs('news.category') && request()->route('slug') == $newsCategory->slug ? 'active' : '' }}">{{ $newsCategory->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li><a href="{{ route('contacts') }}"
                        class="{{ request()->routeIs('contacts') ? 'active' : '' }}">Liên hệ</a></li>
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
                        <x-heroicon-o-x-mark class="w-8 h-8" />
                    </label>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a></li>
                    <li class="dropdown">
                        <div class="dropdown-header">
                            <a href="{{ route('courses.index') }}"
                                class="dropdown-toggle {{ request()->routeIs('courses.index') || request()->routeIs('courses.category') || request()->routeIs('courses.show') ? 'active' : '' }}">
                                Khóa
                                học</a>
                            <label for="dropdown-toggle-1" class="dropdown-icon">
                                <x-heroicon-o-chevron-down id="dropdown-icon-1" class="w-6 h-6" />
                            </label>
                            <input type="checkbox" id="dropdown-toggle-1" class="dropdown-toggle hidden">
                        </div>
                        <ul class="dropdown-menu" id="dropdown-menu-1">
                            <li><a href="{{ route('courses.index') }}"
                                    class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">Tất cả các khóa
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
                                tức - Sự kiện</a>
                            <label for="dropdown-toggle-2" class="dropdown-icon">
                                <x-heroicon-o-chevron-down id="dropdown-icon-2" class="w-6 h-6" />
                            </label>
                            <input type="checkbox" id="dropdown-toggle-2" class="dropdown-toggle hidden">
                        </div>
                        <ul class="dropdown-menu" id="dropdown-menu-2">
                            <li><a href="{{ route('news.index') }}"
                                    class="{{ request()->routeIs('news.index') ? 'active' : '' }}">Tin tức nổi bật</a>
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
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</h3>
                <p>{{ App\Helpers\SettingHelper::get('center_description', 'Chưa cập nhật') }}</p>
            </div>
            <div class="footer-section">
                <h3>Khóa học</h3>
                @foreach (App\Models\Category::all() as $Category)
                    <a href="{{ route('courses.category', $Category->slug) }}"
                        class="course-category {{ request()->routeIs('courses.category') && request()->route('slug') == $Category->slug ? 'active' : '' }}">{{ $Category->name }}</a><br>
                @endforeach
            </div>
            <div class="footer-section">
                <h3>Liên hệ</h3>
                <p><strong>Địa chỉ:</strong> {{ App\Helpers\SettingHelper::get('address', 'Chưa cập nhật') }}</p>
                <p><strong>Điện thoại:</strong> {{ App\Helpers\SettingHelper::get('phone', 'Chưa cập nhật') }}</p>
                <p><strong>Email:</strong> {{ App\Helpers\SettingHelper::get('email', 'Chưa cập nhật') }}</p>
                <p><strong>Website:</strong> {{ App\Helpers\SettingHelper::get('website', 'Chưa cập nhật') }}</p>
            </div>
            <div class="footer-section">
                <h3>Giờ làm việc</h3>
                <p>Thứ 2 - Thứ 6: 8:00 - 21:00</p>
                <p>Thứ 7 - Chủ nhật: 8:00 - 17:00</p>
                <p><strong>Hotline 24/7:</strong> {{ App\Helpers\SettingHelper::get('hotline', 'Chưa cập nhật') }}</p>
            </div>
        </div>
        <div class="footer-menu">
            <ul>
                <li><a class="{{ request()->is('/') ? 'active' : '' }}" href="/">Trang chủ</a></li>
                <li><a class="{{ request()->routeIs('courses.index') || request()->routeIs('courses.category') || request()->routeIs('courses.show') ? 'active' : '' }}"
                        href="{{ route('courses.index') }}">Khóa học</a></li>
                <li><a class="{{ request()->routeIs('rooms.index') || request()->routeIs('rooms.show') ? 'active' : '' }}"
                        href="{{ route('rooms.index') }}">Phòng học</a></li>
                <li><a class="{{ request()->routeIs('news.index') || request()->routeIs('news.category') || request()->routeIs('news.show') ? 'active' : '' }}"
                        href="{{ route('news.index') }}">Tin tức - Sự kiện</a></li>
                <li><a class="{{ request()->routeIs('contacts') ? 'active' : '' }}"
                        href="{{ route('contacts') }}">Liên hệ</a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}.
                All rights reserved.</p>
        </div>
    </footer>
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

        function isTouchDevice() {
            return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        }

        if (isTouchDevice()) {
            document.querySelectorAll('.desktop-menu .dropdown > .dropdown-toggle').forEach(function(toggle) {
                let tapped = false;
                toggle.addEventListener('touchend', function(e) {
                    const parent = toggle.parentElement;
                    const dropdownMenu = parent.querySelector('.dropdown-menu');
                    if (!tapped) {
                        e.preventDefault();
                        // Hide other dropdowns
                        document.querySelectorAll('.desktop-menu .dropdown .dropdown-menu').forEach(
                            function(menu) {
                                if (menu !== dropdownMenu) menu.classList.remove('show-dropdown');
                            });
                        dropdownMenu.classList.toggle('show-dropdown');
                        tapped = true;
                        setTimeout(function() {
                            tapped = false;
                        }, 500);
                    } else {
                        window.location = toggle.getAttribute('href');
                    }
                });
            });
        }
    </script>
    @if (App\Helpers\SettingHelper::get('custom_js'))
        <script>
            {!! App\Helpers\SettingHelper::get('custom_js') !!}
        </script>
    @endif
</body>

</html>
