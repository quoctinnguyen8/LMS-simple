<x-layouts title="Trang chủ">
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
        <h1>Khóa Học Tiếng Anh</h1>
        <div class="course-list">
            @foreach ($courses as $course)
                <div class="course-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                    </div>
                    <div class="card-info">
                        <span class="badge">
                            @php
                                $statusText = match ($course->status) {
                                    'draft' => 'Chưa mở',
                                    'published' => 'Đang mở',
                                    default => 'Đóng',
                                };
                            @endphp
                            {{ $statusText }}
                        </span>
                        <h2>{{ $course->title }}</h2>
                        <p>{{ $course->description }}</p>
                        <p><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>
                        <a href="{{ route('courses.show', $course->id) }}" class="btn">Xem Chi Tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
        <h1>Phòng Học</h1>
        <div class="room-list">
            @foreach ($rooms as $room)
                <div class="room-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                    </div>
                    <div class="card-info">
                        <span class="badge">
                            @php
                                $statusText = match ($room->status) {
                                    'available' => 'Trống',
                                    'maintenance' => 'Bảo trì',
                                    default => 'Đang sử dụng',
                                };
                            @endphp
                            {{ $statusText }}
                        </span>
                        <h2>{{ $room->name }}</h2>
                        <p>{{ $room->description }}</p>
                        <p><strong>Sức chứa:</strong> {{ $room->capacity }} người</p>
                        <a href="{{ route('rooms.show', $room->id) }}" class="btn">Xem Chi Tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <slot:script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const slides = document.querySelectorAll('.slide');
                const dots = document.querySelectorAll('.dot');
                const prevButton = document.querySelector('.slider-prev');
                const nextButton = document.querySelector('.slider-next');
                let currentSlide = 0;

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

                // Handle slide click
                slides.forEach(slide => {
                    slide.addEventListener('click', () => {
                        const url = slide.getAttribute('data-url');
                        if (url) {
                            window.location.href = url;
                        }
                    });
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
    </slot:script>
</x-layouts>