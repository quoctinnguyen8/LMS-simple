<x-layouts>
    <!-- Slider Section -->
    <section class="slider-section" id="home">
        <div class="slider-container">
            <div class="slider-wrapper" id="sliderWrapper">
                @foreach ($slides as $slide)
                    <div class="slide" data-url="{{ $slide->link_url }}">
                        <div class="slide-content">
                            <h1>{{ $slide->title }}</h1>
                            <p>{{ $slide->description }}</p>
                        </div>
                        <img src="{{ Storage::url($slide->image_url) }}" alt="{{ $slide->title }}">
                    </div>
                @endforeach
            </div>

            <button class="slider-arrows prev-arrow" id="prevBtn">‹</button>
            <button class="slider-arrows next-arrow" id="nextBtn">›</button>

            <div class="slider-nav" id="sliderNav"></div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <h2>Giới thiệu về {{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</h2>
        <div class="about-content">
            <p>{{ App\Helpers\SettingHelper::get('center_description', 'Chưa cập nhật') }}</p>
        </div>
    </section>
    <!-- Achievements -->
    <section class="achievements">
        <div class="achievement">
            <x-heroicon-o-check class="icon" />
            <h3>10+</h3>
            <p>Năm kinh nghiệm và phát triển</p>
        </div>
        <div class="achievement">
            <x-heroicon-o-academic-cap class="icon" />
            <h3>100%</h3>
            <p>Giảng viên có chứng chỉ quốc tế</p>
        </div>
        <div class="achievement">
            <x-heroicon-o-users class="icon" />
            <h3>500+</h3>
            <p>Học viên tin tựởng</p>
        </div>
        <div class="achievement">
            <x-heroicon-o-academic-cap class="icon" />
            <h3>98%</h3>
            <p>Đạt mục tiêu đề ra</p>
        </div>
    </section>

    <h2 class="courses-section-title">Khóa học mới nhất</h2>
    <section class="courses-section">
        <div class="courses-slider-wrapper">
            <div class="courses-slider-track">
                @foreach ($courses->take(6) as $course)
                    <div class="course-card">
                        <div class="course-media">
                            <a href="{{ route('courses.show', $course->slug) }}">
                                <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                                <div class="course-badge">{{ $course->category->name }}</div>
                            </a>
                        </div>
                        <div class="course-content">
                            <h3>
                                <a href="{{ route('courses.show', $course->slug) }}">
                                    {{ $course->title }}
                                </a>
                            </h3>
                            <p>
                                {{ Str::limit($course->short_description ?? $course->description, 50) }}
                            </p>
                            <div class="course-footer">
                                <button class="course-btn"
                                    onclick="window.location.href='{{ route('courses.show', $course->slug) }}'">
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="courses-slider-control prev">
                <x-heroicon-o-chevron-left class="w-5 h-5" />
            </button>
            <button class="courses-slider-control next">
                <x-heroicon-o-chevron-right class="w-5 h-5" />
            </button>
        </div>
        @if ($courses->count() > 3)
            <div class="view-all-courses">
                <button onclick="window.location.href='{{ route('courses.index') }}'">Xem tất cả khóa học</button>
            </div>
        @endif
    </section>
    <h2 class="classrooms-section-title">Phòng học hiện đại</h2>
    <section class="home-classrooms-section">
        <div class="classrooms-container">
            @foreach ($rooms->take(3) as $room)
                <div class="classroom-card">
                    <div class="classroom-image">
                        <a href="{{ route('rooms.show', $room->id) }}">
                            <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                        </a>
                    </div>
                    <div class="classroom-info">
                        <h3>
                            <a href="{{ route('rooms.show', $room->id) }}">
                                {{ $room->name }}
                            </a>
                        </h3>
                        <div class="classroom-specs">
                            <x-heroicon-o-user-group class="inline w-5 h-5 text-gray-500 align-middle" />
                            {{ $room->capacity }} chỗ ngồi
                        </div>
                        <div class="room-equipment">
                            <div class="equipment-list">
                                @forelse ($room->equipment as $equipment)
                                    <span class="equipment-tag">{{ $equipment->name }}</span>
                                @empty
                                    <span class="no-equipment">Không có trang thiết bị</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="classroom-actions">
                            <button class="view-btn"
                                onclick="window.location.href='{{ route('rooms.show', $room->id) }}'">Xem chi
                                tiết</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($rooms->count() > 3)
            <div class="view-all-rooms">
                <button onclick="window.location.href='{{ route('rooms.index') }}'">Xem tất cả phòng học</button>
            </div>
        @endif
    </section>
    <h2 class="news-section-title">Tin tức mới nhất</h2>
    <section class="featured-news">
        <div class="featured-container">
            @foreach ($news as $item)
                <div class="featured-article">
                    <div class="featured-image">
                        <a href="{{ route('news.show', $item->slug) }}">
                            <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}">
                            <div class="featured-category">{{ $item->news_category->name }}</div>
                        </a>
                    </div>
                    <div class="featured-content">
                        <div class="featured-date">{{ $item->published_at?->format('d/m/Y') }}</div>
                        <h3>
                            <a href="{{ route('news.show', $item->slug) }}">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p>{{ Str::limit(strip_tags($item->summary ?? $item->content), 140) }}</p>
                        <div class="featured-stats">
                            <span>
                                <x-heroicon-o-eye class="inline w-5 h-5 text-gray-500 align-middle" />
                                {{ $item->view_count }} lượt xem
                            </span>
                        </div>
                        <a href="{{ route('news.show', $item->slug) }}" class="read-more-link">
                            Xem thêm &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($news->count() > 3)
            <div class="view-all-news">
                <button onclick="window.location.href='{{ route('news.index') }}'">Xem tất cả tin tức</button>
            </div>
        @endif
    </section>
    <x-slot:scripts>
        <script>
            class DynamicSlider {
                constructor(containerId) {
                    this.container = document.getElementById(containerId);
                    if (!this.container) return;

                    this.sliderWrapper = this.container.querySelector('.slider-wrapper');
                    this.slides = this.container.querySelectorAll('.slide');
                    this.prevBtn = this.container.querySelector('.prev-arrow');
                    this.nextBtn = this.container.querySelector('.next-arrow');
                    this.navContainer = this.container.querySelector('.slider-nav');

                    this.currentSlide = 0;
                    this.totalSlides = this.slides.length;
                    this.isTransitioning = false;
                    this.autoPlayInterval = null;
                    this.touchStartX = 0;
                    this.touchEndX = 0;
                    this.minSwipeDistance = 50;

                    // Clone first and last slide for seamless looping
                    if (this.totalSlides > 1) {
                        this.cloneSlides();
                    }

                    this.init();
                }

                cloneSlides() {
                    // Clone first and last slide
                    const firstClone = this.slides[0].cloneNode(true);
                    const lastClone = this.slides[this.slides.length - 1].cloneNode(true);
                    firstClone.classList.add('clone');
                    lastClone.classList.add('clone');
                    this.sliderWrapper.appendChild(firstClone);
                    this.sliderWrapper.insertBefore(lastClone, this.slides[0]);
                    // Update slides NodeList
                    this.slides = this.container.querySelectorAll('.slide');
                    this.totalSlides = this.slides.length - 2; // exclude clones
                    // Set initial position to the first real slide
                    this.sliderWrapper.style.transform = `translateX(-100%)`;
                    this.currentSlide = 0;
                }

                init() {
                    if (this.totalSlides === 0) return;

                    this.createDots();
                    this.setupEventListeners();
                    this.updateSlider(false);
                    this.startAutoPlay();
                }

                createDots() {
                    this.navContainer.innerHTML = '';
                    for (let i = 0; i < this.totalSlides; i++) {
                        const dot = document.createElement('div');
                        dot.className = 'slider-dot';
                        dot.setAttribute('data-slide', i);
                        if (i === 0) dot.classList.add('active');
                        this.navContainer.appendChild(dot);
                    }
                    this.dots = this.navContainer.querySelectorAll('.slider-dot');
                }

                setupEventListeners() {
                    // Arrow navigation
                    this.prevBtn?.addEventListener('click', () => this.prevSlide());
                    this.nextBtn?.addEventListener('click', () => this.nextSlide());

                    // Dot navigation
                    this.dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => this.goToSlide(index));
                    });

                    // Slide click navigation
                    // Only real slides (not clones)
                    for (let i = 1; i <= this.totalSlides; i++) {
                        this.slides[i].addEventListener('click', () => {
                            const url = this.slides[i].dataset.url;
                            if (url) window.open(url, '_blank');
                        });
                    }

                    // Touch events
                    this.sliderWrapper.addEventListener('touchstart', (e) => {
                        this.touchStartX = e.touches[0].clientX;
                        this.stopAutoPlay();
                    }, {
                        passive: true
                    });

                    this.sliderWrapper.addEventListener('touchmove', (e) => {
                        this.touchEndX = e.touches[0].clientX;
                    }, {
                        passive: true
                    });

                    this.sliderWrapper.addEventListener('touchend', () => {
                        this.handleSwipe();
                        this.startAutoPlay();
                    }, {
                        passive: true
                    });

                    // Mouse events for desktop
                    let isDragging = false;
                    this.sliderWrapper.addEventListener('mousedown', (e) => {
                        isDragging = true;
                        this.touchStartX = e.clientX;
                        this.sliderWrapper.style.cursor = 'grabbing';
                        this.stopAutoPlay();
                        e.preventDefault();
                    });

                    this.sliderWrapper.addEventListener('mousemove', (e) => {
                        if (isDragging) {
                            this.touchEndX = e.clientX;
                        }
                    });

                    this.sliderWrapper.addEventListener('mouseup', () => {
                        if (isDragging) {
                            this.handleSwipe();
                            this.sliderWrapper.style.cursor = 'pointer';
                            this.startAutoPlay();
                            isDragging = false;
                        }
                    });

                    this.sliderWrapper.addEventListener('mouseleave', () => {
                        if (isDragging) {
                            this.handleSwipe();
                            this.sliderWrapper.style.cursor = 'pointer';
                            this.startAutoPlay();
                            isDragging = false;
                        }
                    });

                    // Pause auto-play on hover
                    this.container.addEventListener('mouseenter', () => this.stopAutoPlay());
                    this.container.addEventListener('mouseleave', () => this.startAutoPlay());

                    // Handle window resize
                    window.addEventListener('resize', () => this.updateSlider(false));
                }

                handleSwipe() {
                    const swipeDistance = this.touchStartX - this.touchEndX;
                    if (Math.abs(swipeDistance) > this.minSwipeDistance) {
                        if (swipeDistance > 0) {
                            this.nextSlide();
                        } else {
                            this.prevSlide();
                        }
                    }
                    this.touchStartX = 0;
                    this.touchEndX = 0;
                }

                nextSlide() {
                    if (this.isTransitioning || this.totalSlides <= 1) return;
                    this.goToSlide(this.currentSlide + 1);
                }

                prevSlide() {
                    if (this.isTransitioning || this.totalSlides <= 1) return;
                    this.goToSlide(this.currentSlide - 1);
                }

                goToSlide(index) {
                    if (this.isTransitioning) return;
                    this.isTransitioning = true;
                    const slideCount = this.totalSlides;
                    const realIndex = index;

                    this.sliderWrapper.style.transition = 'transform 0.5s ease';
                    this.sliderWrapper.style.transform = `translateX(-${(realIndex + 1) * 100}%)`;

                    // Update dots
                    this.dots?.forEach((dot, i) => {
                        dot.classList.toggle('active', i === ((realIndex + slideCount) % slideCount));
                    });

                    setTimeout(() => {
                        // If at the clone after last slide, jump to first real slide without transition
                        if (realIndex >= slideCount) {
                            this.sliderWrapper.style.transition = 'none';
                            this.sliderWrapper.style.transform = `translateX(-100%)`;
                            this.currentSlide = 0;
                        }
                        // If at the clone before first slide, jump to last real slide without transition
                        else if (realIndex < 0) {
                            this.sliderWrapper.style.transition = 'none';
                            this.sliderWrapper.style.transform = `translateX(-${slideCount * 100}%)`;
                            this.currentSlide = slideCount - 1;
                        } else {
                            this.currentSlide = realIndex;
                        }
                        // Update dots again in case of jump
                        this.dots?.forEach((dot, i) => {
                            dot.classList.toggle('active', i === this.currentSlide);
                        });
                        this.isTransitioning = false;
                    }, 500);
                }

                updateSlider(animate = true) {
                    if (this.totalSlides === 0) return;
                    if (animate) {
                        this.sliderWrapper.style.transition = 'transform 0.5s ease';
                    } else {
                        this.sliderWrapper.style.transition = 'none';
                    }
                    this.sliderWrapper.style.transform = `translateX(-${(this.currentSlide + 1) * 100}%)`;

                    this.dots?.forEach((dot, index) => {
                        dot.classList.toggle('active', index === this.currentSlide);
                    });
                }

                startAutoPlay() {
                    if (this.totalSlides <= 1) return;
                    this.stopAutoPlay();
                    this.autoPlayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 5000);
                }

                stopAutoPlay() {
                    if (this.autoPlayInterval) {
                        clearInterval(this.autoPlayInterval);
                        this.autoPlayInterval = null;
                    }
                }

                addSlide(slideContent) {
                    // Not supported for seamless loop in this version
                }

                removeSlide(index) {
                    // Not supported for seamless loop in this version
                }
            }

            // Initialize the dynamic slider
            document.addEventListener('DOMContentLoaded', () => {
                new DynamicSlider('home');
            });

            // Initialize the courses slider
            document.addEventListener('DOMContentLoaded', () => {
                const wrapper = document.querySelector('.courses-slider-wrapper');
                const track = document.querySelector('.courses-slider-track');
                const prevBtn = document.querySelector('.courses-slider-control.prev');
                const nextBtn = document.querySelector('.courses-slider-control.next');
                const originalSlides = [...track.children].map(node => node.cloneNode(true));
                let currentIndex = 0;
                let slideWidth = 0;
                let numClones = 0;
                let numOriginal = 0;
                let autoTimeout;
                let isHovering = false;
                let isDragging = false;
                let startPos = 0;
                let currentTranslate = 0;
                let prevTranslate = 0;

                function initSlider() {
                    track.innerHTML = '';
                    originalSlides.forEach(slide => track.appendChild(slide.cloneNode(true)));
                    numOriginal = originalSlides.length;
                    const cards = track.children;
                    if (cards.length === 0) return;
                    slideWidth = cards[0].offsetWidth + parseInt(getComputedStyle(track).gap || '0', 10);
                    const wrapperWidth = wrapper.clientWidth;
                    const numVisible = Math.floor(wrapperWidth / slideWidth);
                    numClones = numVisible + 1; // Extra for safety

                    // Prepend clones
                    for (let i = 0; i < numClones; i++) {
                        track.insertBefore(originalSlides[(numOriginal - 1 - i) % numOriginal].cloneNode(true), track
                            .firstChild);
                    }

                    // Append clones
                    for (let i = 0; i < numClones; i++) {
                        track.appendChild(originalSlides[i % numOriginal].cloneNode(true));
                    }

                    currentIndex = numClones;
                    currentTranslate = -currentIndex * slideWidth;
                    track.style.transform = `translateX(${currentTranslate}px)`;
                    track.style.transition = 'none';
                }

                function setSliderPosition() {
                    track.style.transform = `translateX(${currentTranslate}px)`;
                }

                function nextSlide() {
                    currentIndex++;
                    currentTranslate = -currentIndex * slideWidth;
                    track.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    setSliderPosition();
                    setTimeout(() => {
                        if (currentIndex >= numClones + numOriginal) {
                            track.style.transition = 'none';
                            currentIndex = numClones;
                            currentTranslate = -currentIndex * slideWidth;
                            setSliderPosition();
                        }
                    }, 800);
                }

                function prevSlide() {
                    currentIndex--;
                    currentTranslate = -currentIndex * slideWidth;
                    track.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    setSliderPosition();
                    setTimeout(() => {
                        if (currentIndex < numClones) {
                            track.style.transition = 'none';
                            currentIndex = numClones + numOriginal - 1;
                            currentTranslate = -currentIndex * slideWidth;
                            setSliderPosition();
                        }
                    }, 800);
                }

                function autoSlide() {
                    autoTimeout = setTimeout(() => {
                        nextSlide();
                        autoSlide();
                    }, 4000);
                }

                function getPositionX(event) {
                    return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
                }

                function startDrag(event) {
                    if (event.type === 'touchstart') {
                        event.preventDefault();
                    }
                    isDragging = true;
                    startPos = getPositionX(event);
                    prevTranslate = currentTranslate;
                    track.style.transition = 'none';
                    clearTimeout(autoTimeout);
                    wrapper.style.cursor = 'grabbing';
                }

                function dragging(event) {
                    if (!isDragging) return;
                    if (event.type === 'touchmove') {
                        event.preventDefault();
                    }
                    const currentPosition = getPositionX(event);
                    currentTranslate = prevTranslate + currentPosition - startPos;
                    setSliderPosition();
                }

                function endDrag() {
                    if (!isDragging) return;
                    isDragging = false;
                    wrapper.style.cursor = 'default';
                    const movedBy = currentTranslate - (-currentIndex * slideWidth);
                    if (movedBy < -slideWidth / 3) {
                        nextSlide();
                    } else if (movedBy > slideWidth / 3) {
                        prevSlide();
                    } else {
                        track.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                        currentTranslate = -currentIndex * slideWidth;
                        setSliderPosition();
                    }
                    if (!isHovering) autoSlide();
                }

                wrapper.addEventListener('mousedown', startDrag);
                wrapper.addEventListener('touchstart', startDrag);
                wrapper.addEventListener('mousemove', dragging);
                wrapper.addEventListener('touchmove', dragging);
                wrapper.addEventListener('mouseup', endDrag);
                wrapper.addEventListener('mouseleave', endDrag);
                wrapper.addEventListener('touchend', endDrag);

                prevBtn.addEventListener('click', () => {
                    clearTimeout(autoTimeout);
                    prevSlide();
                    if (!isHovering) autoSlide();
                });

                nextBtn.addEventListener('click', () => {
                    clearTimeout(autoTimeout);
                    nextSlide();
                    if (!isHovering) autoSlide();
                });

                wrapper.addEventListener('mouseenter', () => {
                    isHovering = true;
                    clearTimeout(autoTimeout);
                });

                wrapper.addEventListener('mouseleave', () => {
                    isHovering = false;
                    autoSlide();
                });

                window.addEventListener('resize', () => {
                    clearTimeout(autoTimeout);
                    initSlider();
                    if (!isHovering) autoSlide();
                });

                initSlider();
                autoSlide();
            });
        </script>
    </x-slot:scripts>
</x-layouts>
