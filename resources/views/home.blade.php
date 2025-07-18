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
        @if ($courses->isNotEmpty())
            <h1>Khóa học nổi bật</h1>
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
        @if ($courses->count() > 6)
            <div class="view-more">
                <a href="{{ route('courses.index') }}" class="btn">Xem thêm</a>
            </div>
        @endif

        @if ($rooms->isNotEmpty())
            <h1>Phòng học nổi bật</h1>
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
        @if ($rooms->count() > 6)
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
    </x-slot:scripts>
</x-layouts>
