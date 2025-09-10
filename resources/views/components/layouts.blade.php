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

    {{-- Custom CSS from settings --}}
    {{-- Custom CSS từ settings --}}
    @if (App\Helpers\SettingHelper::get('custom_css'))
        <style>
            {!! App\Helpers\SettingHelper::get('custom_css') !!}
        </style>
    @endif
    @if (App\Helpers\SettingHelper::get('ga_head'))
        {!! App\Helpers\SettingHelper::get('ga_head') !!}
    @endif
</head>

<body>
    <header>
        <div class="topbar">
            <div class="container">
                <div class="topbar-left">
                    <div class="contact-item">
                        <x-heroicon-o-envelope class="icon" />
                        <span>{{ App\Helpers\SettingHelper::get('email', 'Chưa cập nhật') }}</span>
                    </div>
                    <div class="contact-item">
                        <x-heroicon-o-phone class="icon" />
                        <span>{{ App\Helpers\SettingHelper::get('phone', 'Chưa cập nhật') }}</span>
                    </div>
                </div>
                <div class="topbar-right">
                    <a href="{{ route('contacts') }}" class="contact-link">Liên hệ</a>
                    <div class="social-links">
                        <a href="{{ App\Helpers\SettingHelper::get('facebook_fanpage', '#') }}" aria-label="Facebook"
                            class="social-link">
                            <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="{{ App\Helpers\SettingHelper::get('youtube_channel', '#') }}" aria-label="YouTube"
                            class="social-link">
                            <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="brand-row">
            <div class="container">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('storage/' . App\Helpers\SettingHelper::get('logo')) }}"
                            alt="{{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}"
                            class="logo-image">
                    </a>
                </div>

                <form action="{{ route('search') }}" method="GET" class="header-search" role="search">
                    <input type="text" name="q" placeholder="Tìm kiếm khóa học, tin tức..."
                        aria-label="Tìm kiếm">
                    <button type="submit" aria-label="Tìm kiếm">
                        <x-heroicon-o-magnifying-glass class="search-icon" />
                    </button>
                </form>

                <div class="header-contact">
                    <div class="contact-info">
                        <x-heroicon-o-phone class="contact-icon" />
                        <div class="contact-details">
                            <span class="contact-label">Hotline</span>
                            <a href="tel:{{ App\Helpers\SettingHelper::get('phone', '') }}" class="contact-number">
                                {{ App\Helpers\SettingHelper::get('phone', 'Chưa cập nhật') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav>
            <input type="checkbox" id="menu-toggle" class="hidden">
            <label for="menu-toggle" class="menu-button">
                <x-heroicon-o-bars-3 class="w-8 h-8" />
            </label>
            <ul class="desktop-menu">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a></li>
                <li><a href="/#about" class="{{ request()->is('/#about') ? 'active' : '' }}">Giới thiệu</a></li>
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
                    <li><a href="/#about" class="{{ request()->is('/#about') ? 'active' : '' }}">Giới thiệu</a></li>
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
            </div>
            <div class="footer-section">
                <h3>Giờ làm việc</h3>
                <p><strong>Thứ 2 - Thứ 7</strong></p>
                <p><strong>Sáng:</strong> 8:00 - 11:30</p>
                <p><strong>Tối:</strong> 18:00 - 21:00</p>
                <p><strong>Chủ nhật:</strong> Nghỉ</p>
            </div>
            <div class="footer-section">
                <h3>Vị trí trung tâm</h3>
                <div class="map-wrapper">
                    <iframe src="{{ App\Helpers\SettingHelper::get('google_map', '') }}" width="100%"
                        height="200" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <style></style>
        </div>
        <div class="footer-menu">
            <ul>
                <li><a class="{{ request()->is('/') ? 'active' : '' }}" href="/">Trang chủ</a></li>
                <li><a class="{{ request()->routeIs('courses.index') || request()->routeIs('courses.category') || request()->routeIs('courses.show') ? 'active' : '' }}"
                        href="{{ route('courses.index') }}">Khóa học</a></li>
                <li><a class="/#about {{ request()->is('/#about') ? 'active' : '' }}" href="/#about">Giới thiệu</a>
                </li>
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
    <!-- Floating Contact Button with Popup -->
    <div class="floating-contact-wrapper">
        <!-- Main Toggle Button -->
        <button class="main-contact-btn" id="contactToggle" aria-label="Liên hệ">
            <div class="btn-icon-wrapper">
                <x-heroicon-o-phone class="icon phone-icon" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo"
                    class="icon zalo-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                    alt="Facebook" class="icon facebook-icon">
            </div>
        </button>

        <!-- Popup Menu -->
        <div class="contact-popup" id="contactPopup">
            <a href="tel:{{ App\Helpers\SettingHelper::get('phone', '') }}" class="contact-btn contact-btn-phone"
                aria-label="Gọi điện">
                <x-heroicon-o-phone class="w-6 h-6" />
                <span class="btn-label">Gọi điện</span>
            </a>
            <a href="https://zalo.me/{{ App\Helpers\SettingHelper::get('zalo', '') }}" target="_blank"
                class="contact-btn contact-btn-zalo" aria-label="Zalo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo">
                <span class="btn-label">Zalo</span>
            </a>
            <a href="{{ App\Helpers\SettingHelper::get('facebook_fanpage', 'https://facebook.com') }}"
                target="_blank" class="contact-btn contact-btn-facebook" aria-label="Facebook">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                    alt="Facebook">
                <span class="btn-label">Facebook</span>
            </a>
        </div>

        <!-- Overlay -->
        <div class="contact-overlay" id="contactOverlay"></div>
    </div>

    {{ $scripts ?? '' }}
    @if(App\Helpers\SettingHelper::get('ga_body'))
        {!! App\Helpers\SettingHelper::get('ga_body') !!}
    @endif
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


        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('contactToggle');
            const popup = document.getElementById('contactPopup');
            const overlay = document.getElementById('contactOverlay');
            const icons = document.querySelectorAll('.btn-icon-wrapper .icon');

            let currentIconIndex = 0;
            let iconInterval;

            // Initialize first icon
            icons[0].classList.add('active');

            function startIconSlider() {
                iconInterval = setInterval(() => {
                    const currentIcon = icons[currentIconIndex];
                    const nextIconIndex = (currentIconIndex + 1) % icons.length;
                    const nextIcon = icons[nextIconIndex];

                    // Current icon exits to left
                    currentIcon.classList.remove('active');
                    currentIcon.classList.add('exit-left');

                    // Next icon enters from right
                    nextIcon.classList.remove('enter-right');
                    nextIcon.classList.add('active');

                    // Clean up classes after animation
                    setTimeout(() => {
                        currentIcon.classList.remove('exit-left');
                        currentIcon.classList.add('enter-right');
                    }, 500);

                    currentIconIndex = nextIconIndex;
                }, 2000); // Change icon every 2 seconds
            }

            function stopIconSlider() {
                if (iconInterval) {
                    clearInterval(iconInterval);
                    iconInterval = null;
                }
            }

            // Start icon slider
            startIconSlider();

            function togglePopup() {
                const isActive = popup.classList.contains('active');

                if (isActive) {
                    closePopup();
                } else {
                    openPopup();
                }
            }

            function openPopup() {
                popup.classList.add('active');
                overlay.classList.add('active');

                // Add subtle bounce effect to main button
                toggleBtn.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    toggleBtn.style.transform = 'scale(1)';
                }, 100);
            }

            function closePopup() {
                popup.classList.remove('active');
                overlay.classList.remove('active');
            }

            // Event listeners
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                togglePopup();
            });

            overlay.addEventListener('click', closePopup);

            // Close popup when clicking contact buttons
            const contactBtns = popup.querySelectorAll('.contact-btn');
            contactBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    setTimeout(closePopup, 100);
                });
            });

            // Close popup when clicking outside
            document.addEventListener('click', function(e) {
                if (!toggleBtn.contains(e.target) && !popup.contains(e.target)) {
                    closePopup();
                }
            });

            // Keyboard accessibility
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePopup();
                }
            });

            // Pause slider on hover (only on non-touch devices)
            if (!('ontouchstart' in window)) {
                toggleBtn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                    stopIconSlider();
                });

                toggleBtn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    startIconSlider();
                });
            }

            // Handle visibility change (pause when tab is not active)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopIconSlider();
                } else {
                    startIconSlider();
                }
            });
        });
    </script>
    @if (App\Helpers\SettingHelper::get('custom_js'))
        <script>
            {!! App\Helpers\SettingHelper::get('custom_js') !!}
        </script>
    @endif
</body>

</html>
