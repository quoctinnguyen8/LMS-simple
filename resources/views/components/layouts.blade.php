<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>{{ $attributes['title'] ?? 'LMS' }}</title>
</head>
<body>
   <header>
        <nav>
            <div class="logo">LMS Simple</div>
            <ul>
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a></li>
                <li><a href="{{ route('courses.index') }}" class="{{ request()->is('courses*') ? 'active' : '' }}">Khóa học</a></li>
                <li><a href="{{ route('rooms.index') }}" class="{{ request()->is('rooms*') ? 'active' : '' }}">Phòng học</a></li>
            </ul>
        </nav>
    </header>
    <main>
        {{ $slot }}
    </main>
    <footer>
        <p>&copy; 2023 LMS Simple. All rights reserved.</p>
    </footer>
    {{ $scripts ?? '' }}
</body>
</html>