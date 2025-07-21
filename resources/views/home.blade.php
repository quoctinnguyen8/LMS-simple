<x-layouts>
    @if ($slides->count())
        <section class="slider">
            <div class="slider-container">
                @foreach ($slides as $slide)
                    <div class="slide {{ $loop->first ? 'active' : '' }}" data-url="{{ $slide->link_url }}">
                        <img src="{{ Storage::url($slide->image_url) }}" alt="{{ $slide->title }}">
                        <div class="slide-content">
                            <h2>{{ $slide->title }}</h2>
                            <p>{{ $slide->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="slider-prev">❮</button>
            <button class="slider-next">❯</button>
            <div class="slider-dots">
                @foreach ($slides as $index => $slide)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
        </section>
    @endif
    <section class="courses">
        @if ($news->isNotEmpty())
            <h1>Tin Tức Nổi Bật</h1>
        @endif
        <div class="news-list">
            @foreach ($news as $news)
                <div class="news-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($news->featured_image) }}" alt="{{ $news->title }}">
                    </div>
                    <div class="card-info">
                        <h2>{{ $news->title }}</h2>
                        <p>{{ $news->summary }}</p>
                        <p class="meta">
                            Đăng ngày: {{ $news->published_at->format('d/m/Y') }} |
                            Tác giả: {{ $news->user->name }} |
                            Lượt xem: {{ $news->view_count }}
                        </p>
                        <a href="{{ route('news.show', $news->slug) }}" class="btn">Xem Chi Tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($news->count() > 3)
            <div class="view-more">
                <a href="{{ route('news.index') }}" class="btn">Xem thêm</a>
            </div>
        @endif
        @if ($courses->isNotEmpty())
            <h1>Khóa Học Nổi Bật</h1>
        @endif
        <div class="course-list">
            @foreach ($courses as $course)
                <div class="course-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                    </div>
                    <div class="card-info">
                        <h2>{{ $course->title }}</h2>
                        <p>{{ $course->description }}</p>
                        @if ($course->is_price_visible)
                            <p id="course-price"><strong>Giá:</strong>
                                {{ number_format($course->price, 0, ',', '.') }}
                                VNĐ</p>
                        @else
                            <p id="course-price"><strong>Giá:</strong> Liên hệ để biết thêm chi tiết</p>
                        @endif
                        <a href="{{ route('courses.show', $course->slug) }}" class="btn">Xem Chi Tiết</a>
                    </div>
                </div>
            @endforeach

        </div>
        @if ($courses->count() > 3)
            <div class="view-more">
                <a href="{{ route('courses.index') }}" class="btn">Xem thêm</a>
            </div>
        @endif

        @if ($rooms->isNotEmpty())
            <h1>Phòng Học Nổi Bật</h1>
        @endif
        <div class="room-list">
            @foreach ($rooms as $room)
                <div class="room-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                    </div>
                    <div class="card-info">
                        <h2>{{ $room->name }}</h2>
                        <p><strong>Vị trí:</strong> {{ $room->location }}</p>
                        <p><strong>Sức chứa:</strong> {{ $room->capacity }} người</p>
                        <p><strong>Giá thuê:</strong> {{ number_format($room->price, 0, ',', '.') }}
                            VNĐ/{{ App\Helpers\SettingHelper::get('room_rental_unit') }}</p>
                        <a href="{{ route('rooms.show', $room->id) }}" class="btn">Xem Chi Tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($rooms->count() > 3)
            <div class="view-more">
                <a href="{{ route('rooms.index') }}" class="btn">Xem thêm</a>
            </div>
        @endif
    </section>
    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const slides = document.querySelectorAll('.slide');
                const dots = document.querySelectorAll('.dot');
                const prevButton = document.querySelector('.slider-prev');
                const nextButton = document.querySelector('.slider-next');
                const sliderContainer = document.querySelector('.slider-container');
                let currentSlide = 0;
                let startX = 0;
                let endX = 0;

                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        slide.classList.toggle('active', i === index);
                        dots[i].classList.toggle('active', i === index);
                    });
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(currentSlide);
                }

                function handleSwipe() {
                    const swipeThreshold = 50;
                    const swipeDistance = startX - endX;

                    if (Math.abs(swipeDistance) > swipeThreshold) {
                        if (swipeDistance > 0) {
                            nextSlide();
                        } else {
                            prevSlide();
                        }
                    }
                }

                // Handle slide click
                slides.forEach(slide => {
                    slide.addEventListener('click', () => {
                        const url = slide.getAttribute('data-url');
                        if (url) {
                            window.location.href = url;
                        }
                    });
                });

                // Touch events for swipe
                sliderContainer.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                });

                sliderContainer.addEventListener('touchend', (e) => {
                    endX = e.changedTouches[0].clientX;
                    handleSwipe();
                });

                // Mouse events for desktop swipe
                sliderContainer.addEventListener('mousedown', (e) => {
                    startX = e.clientX;
                    sliderContainer.style.cursor = 'grabbing';
                });

                sliderContainer.addEventListener('mouseup', (e) => {
                    endX = e.clientX;
                    handleSwipe();
                    sliderContainer.style.cursor = 'grab';
                });

                sliderContainer.addEventListener('mouseleave', () => {
                    sliderContainer.style.cursor = 'grab';
                });

                prevButton.addEventListener('click', prevSlide);
                nextButton.addEventListener('click', nextSlide);
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentSlide = index;
                        showSlide(currentSlide);
                    });
                });

                // Auto slide every 10 seconds
                setInterval(nextSlide, 10000);

                // Show initial slide
                showSlide(currentSlide);
            });
        </script>
    </x-slot:scripts>
</x-layouts>
