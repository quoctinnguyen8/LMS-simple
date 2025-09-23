<x-layouts>
    <!-- Welcome Marquee -->
    <div class="welcome-marquee">
        <marquee behavior="scroll" direction="left" scrollamount="5">
            {!! App\Helpers\SettingHelper::get(
                'welcome_message',
                'Chào mừng bạn đến với ' .
                    App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') .
                    ' - Nơi học tập và phát triển bản thân!',
            ) !!}
        </marquee>
    </div>
    <section class="hero-section" id="home">
        <div class="slider-section">
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
        </div>
    </section>
    <div class="achievements">
        <div class="achievement">
            <x-heroicon-s-check class="icon" />
            <h3>8+</h3>
            <p>Năm kinh nghiệm và phát triển</p>
        </div>
        <div class="achievement">
            <x-heroicon-s-academic-cap class="icon" />
            <h3>100%</h3>
            <p>Giảng viên có chứng chỉ quốc tế</p>
        </div>
        <div class="achievement">
            <x-heroicon-s-users class="icon" />
            <h3>1000+</h3>
            <p>Học viên tin tựởng</p>
        </div>
        <div class="achievement">
            <x-heroicon-s-academic-cap class="icon" />
            <h3>100%</h3>
            <p>Đạt mục tiêu đề ra</p>
        </div>
    </div>
    <!-- About Section -->
    <section class="about-section" id="about">
        <h2>Giới thiệu về {{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</h2>
        <div class="about-content">
            {!! App\Helpers\SettingHelper::get('description', 'Chưa cập nhật') !!}
        </div>
        @if (App\Helpers\SettingHelper::get('youtube_embed'))
            <div class="about-video">
                <iframe src="{{App\Helpers\SettingHelper::get('youtube_embed')}}" title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        @endif
    </section>
    @php
        $feedbacks = json_decode(App\Helpers\SettingHelper::get('feedback', '[]'), true);
        if (!is_array($feedbacks)) {
            $feedbacks = [];
        }
    @endphp
    <section class="feedback-section">
        <h2>Học viên nói gì về chúng tôi</h2>
        <div class="feedback-container">
            <div class="feedback-slider">
                <div class="feedback-slider-wrapper">
                    <div class="feedback-track">
                        @foreach ($feedbacks as $index => $feedback)
                            <div class="feedback-card {{ $index === 0 ? 'active' : '' }}"
                                data-index="{{ $index }}">
                                <div class="feedback-content">
                                    <div class="feedback-avatar">
                                        <img src="{{ Storage::url($feedback['avatar']) }}" alt="{{ $feedback['name'] }}"
                                            class="avatar-img">
                                    </div>

                                    <div class="feedback-info">
                                        <h3 class="feedback-name">{{ $feedback['name'] }}</h3>
                                        <div class="feedback-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star">
                                                    <x-heroicon-s-star class="w-5 h-5 text-yellow-500" />
                                                </span>
                                            @endfor
                                        </div>
                                        <p class="feedback-text">{{ $feedback['content'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="feedback-navigation">
                    <button class="feedback-nav-btn prev-btn" aria-label="Previous feedback">
                        <x-heroicon-s-chevron-left class="w-5 h-5 text-white" />
                    </button>
                    <button class="feedback-nav-btn next-btn" aria-label="Next feedback">
                        <x-heroicon-s-chevron-right class="w-5 h-5 text-white" />
                    </button>
                </div>
            </div>

            <div class="feedback-dots">
                @foreach ($feedbacks as $index => $feedback)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Achievements -->
    @if ($courses->count() > 0)
        <section class="courses-section">
            <h2 class="courses-section-title">Khóa học mới nhất</h2>
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
                                    {{ $course->short_description ?? $course->description }}
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
                    <x-heroicon-s-chevron-left class="w-5 h-5 text-white" />
                </button>
                <button class="courses-slider-control next">
                    <x-heroicon-s-chevron-right class="w-5 h-5 text-white" />
                </button>
            </div>
            @if ($courses->count() > 6)
                <div class="view-all-courses">
                    <button onclick="window.location.href='{{ route('courses.index') }}'">Xem tất cả khóa học</button>
                </div>
            @endif
        </section>
    @endif
    @if ($news->count() > 0)
        <section class="featured-news">
            <h2 class="news-section-title">Tin tức mới nhất</h2>
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
                                    <x-heroicon-s-eye class="inline w-5 h-5 text-gray-500 align-middle" />
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
    @endif
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

            class CoursesSlider {
                constructor(containerId) {
                    this.wrapper = document.querySelector('.courses-slider-wrapper');
                    this.track = document.querySelector('.courses-slider-track');
                    this.prevBtn = document.querySelector('.courses-slider-control.prev');
                    this.nextBtn = document.querySelector('.courses-slider-control.next');

                    if (!this.wrapper || !this.track) return;

                    // Only get slides that actually exist
                    this.slides = [...this.track.children].filter(slide => slide && slide.nodeType === 1);

                    // Don't initialize if no slides exist
                    if (this.slides.length === 0) {
                        this.hideSlider();
                        return;
                    }

                    this.currentIndex = 0;
                    this.slideWidth = 0;
                    this.autoPlayInterval = null;
                    this.isTransitioning = false;
                    this.isHovering = false;
                    this.isDragging = false;
                    this.startX = 0;

                    this.init();
                }

                hideSlider() {
                    if (this.wrapper) {
                        this.wrapper.style.display = 'none';
                    }
                }

                getVisibleCards() {
                    if (window.innerWidth >= 1200) return 3; // Large screens: 3 cards
                    if (window.innerWidth >= 768) return 2; // Medium screens: 2 cards
                    return 1; // Small screens: 1 card
                }

                calculateSlideWidth() {
                    if (this.slides.length === 0) return;

                    this.slideWidth = this.slides[0].offsetWidth + parseInt(getComputedStyle(this.track).gap || '15', 10);
                    this.currentTranslate = -this.currentIndex * this.slideWidth;
                    this.track.style.transform = `translateX(${this.currentTranslate}px)`;
                    this.track.style.transition = 'none';
                }

                init() {
                    if (this.slides.length === 0) {
                        this.hideSlider();
                        return;
                    }

                    this.calculateSlideWidth();
                    this.setupEventListeners();
                    this.updateSlider(false);

                    if (this.slides.length > this.getVisibleCards()) {
                        this.startAutoPlay();
                    }
                }

                setupEventListeners() {
                    // Arrow navigation
                    this.prevBtn?.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.prevSlide();
                    });

                    this.nextBtn?.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.nextSlide();
                    });

                    if (this.slides.length <= this.getVisibleCards()) {
                        return;
                    }

                    // Hover events
                    this.wrapper.addEventListener('mouseenter', () => {
                        this.isHovering = true;
                        this.stopAutoPlay();
                    });

                    this.wrapper.addEventListener('mouseleave', () => {
                        this.isHovering = false;
                        if (this.slides.length > this.getVisibleCards()) {
                            this.startAutoPlay();
                        }
                    });

                    // Touch events
                    this.track.addEventListener('touchstart', (e) => {
                        this.startX = e.touches[0].clientX;
                        this.isDragging = true;
                        this.stopAutoPlay();
                    });

                    this.track.addEventListener('touchmove', (e) => {
                        if (!this.isDragging) return;
                        const currentX = e.touches[0].clientX;
                        const diffX = this.startX - currentX;
                        const threshold = this.slideWidth * 0.3; // 30% of slide width for swipe sensitivity
                        if (Math.abs(diffX) > threshold) {
                            if (diffX > 0) {
                                this.nextSlide();
                            } else if (diffX < 0) {
                                this.prevSlide();
                            }
                            this.isDragging = false;
                        }
                    });

                    this.track.addEventListener('touchend', () => {
                        this.isDragging = false;
                        if (!this.isHovering && this.slides.length > this.getVisibleCards()) {
                            this.startAutoPlay();
                        }
                    });

                    // Window resize
                    window.addEventListener('resize', () => {
                        this.stopAutoPlay();
                        const prevVisibleCards = this.getVisibleCards();
                        this.calculateSlideWidth();
                        const newVisibleCards = this.getVisibleCards();
                        if (prevVisibleCards !== newVisibleCards) {
                            this.currentIndex = Math.min(this.currentIndex, Math.max(0, this.slides.length -
                                newVisibleCards));
                            this.updateSlider(false);
                        }
                        if (!this.isHovering && this.slides.length > newVisibleCards) {
                            this.startAutoPlay();
                        }
                    });
                }

                nextSlide() {
                    if (this.isTransitioning || this.slides.length <= this.getVisibleCards()) return;

                    const visibleCards = this.getVisibleCards();
                    const lastRealIndex = Math.max(0, this.slides.length - visibleCards);
                    const nextIndex = this.currentIndex + 1;

                    if (nextIndex > lastRealIndex) {
                        this.goToSlide(0); // Loop back to start
                    } else {
                        this.goToSlide(nextIndex);
                    }
                }

                prevSlide() {
                    if (this.isTransitioning || this.slides.length <= this.getVisibleCards()) return;

                    const prevIndex = this.currentIndex - 1;
                    const visibleCards = this.getVisibleCards();
                    const lastRealIndex = Math.max(0, this.slides.length - visibleCards);

                    if (prevIndex < 0) {
                        this.goToSlide(lastRealIndex); // Loop to last valid index
                    } else {
                        this.goToSlide(prevIndex);
                    }
                }

                goToSlide(index) {
                    if (this.isTransitioning || this.slides.length <= this.getVisibleCards()) return;

                    this.isTransitioning = true;
                    this.currentIndex = index;
                    this.currentTranslate = -this.currentIndex * this.slideWidth;

                    this.track.style.transition = 'transform 0.5s ease';
                    this.track.style.transform = `translateX(${this.currentTranslate}px)`;

                    setTimeout(() => {
                        this.isTransitioning = false;
                    }, 500);
                }

                updateSlider(animate = true) {
                    if (this.slides.length === 0) return;

                    if (animate) {
                        this.track.style.transition = 'transform 0.5s ease';
                    } else {
                        this.track.style.transition = 'none';
                    }

                    this.currentTranslate = -this.currentIndex * this.slideWidth;
                    this.track.style.transform = `translateX(${this.currentTranslate}px)`;
                }

                startAutoPlay() {
                    if (this.slides.length <= this.getVisibleCards()) return;
                    this.stopAutoPlay();
                    this.autoPlayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 4000);
                }

                stopAutoPlay() {
                    if (this.autoPlayInterval) {
                        clearInterval(this.autoPlayInterval);
                        this.autoPlayInterval = null;
                    }
                }
            }

            // Initialize the courses slider
            document.addEventListener('DOMContentLoaded', () => {
                new CoursesSlider();
            });
            class FeedbackSlider {
                constructor() {
                    this.slider = document.querySelector('.feedback-slider');
                    this.track = document.querySelector('.feedback-track');
                    this.cards = document.querySelectorAll('.feedback-card');
                    this.prevBtn = document.querySelector('.feedback-nav-btn.prev-btn');
                    this.nextBtn = document.querySelector('.feedback-nav-btn.next-btn');
                    this.dots = document.querySelectorAll('.feedback-dots .dot');

                    if (!this.slider || !this.track || this.cards.length === 0) return;

                    this.currentIndex = 0;
                    this.totalSlides = this.cards.length;
                    this.isTransitioning = false;
                    this.autoPlayInterval = null;

                    this.init();
                }

                init() {
                    this.setupEventListeners();
                    this.updateSlider(false);
                    this.startAutoPlay();
                }

                setupEventListeners() {
                    // Arrow navigation
                    this.prevBtn?.addEventListener('click', () => this.prevSlide());
                    this.nextBtn?.addEventListener('click', () => this.nextSlide());

                    // Dot navigation
                    this.dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => this.goToSlide(index));
                    });

                    // Touch/swipe events
                    let startX = 0;
                    let endX = 0;

                    this.track.addEventListener('touchstart', (e) => {
                        startX = e.touches[0].clientX;
                        this.stopAutoPlay();
                    });

                    this.track.addEventListener('touchmove', (e) => {
                        endX = e.touches[0].clientX;
                    });

                    this.track.addEventListener('touchend', () => {
                        const threshold = 50;
                        const diff = startX - endX;

                        if (Math.abs(diff) > threshold) {
                            if (diff > 0) {
                                this.nextSlide();
                            } else {
                                this.prevSlide();
                            }
                        }
                        this.startAutoPlay();
                    });

                    // Mouse drag events for desktop
                    let isDragging = false;
                    let startXMouse = 0;

                    this.track.addEventListener('mousedown', (e) => {
                        isDragging = true;
                        startXMouse = e.clientX;
                        this.track.style.cursor = 'grab';
                        this.stopAutoPlay();
                        e.preventDefault();
                    });

                    document.addEventListener('mousemove', (e) => {
                        if (!isDragging) return;
                        endX = e.clientX;
                    });

                    document.addEventListener('mouseup', () => {
                        if (!isDragging) return;

                        const threshold = 50;
                        const diff = startXMouse - endX;

                        if (Math.abs(diff) > threshold) {
                            if (diff > 0) {
                                this.nextSlide();
                            } else {
                                this.prevSlide();
                            }
                        }

                        isDragging = false;
                        this.track.style.cursor = 'grab';
                        this.startAutoPlay();
                    });

                    // Pause autoplay on hover
                    this.slider.addEventListener('mouseenter', () => this.stopAutoPlay());
                    this.slider.addEventListener('mouseleave', () => this.startAutoPlay());
                }

                nextSlide() {
                    if (this.isTransitioning) return;
                    const nextIndex = (this.currentIndex + 1) % this.totalSlides;
                    this.goToSlide(nextIndex);
                }

                prevSlide() {
                    if (this.isTransitioning) return;
                    const prevIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
                    this.goToSlide(prevIndex);
                }

                goToSlide(index) {
                    if (this.isTransitioning || index === this.currentIndex) return;

                    this.isTransitioning = true;
                    this.currentIndex = index;
                    this.updateSlider(true);

                    setTimeout(() => {
                        this.isTransitioning = false;
                    }, 500);
                }

                updateSlider(animate = true) {
                    if (animate) {
                        this.track.style.transition = 'transform 0.5s ease';
                    } else {
                        this.track.style.transition = 'none';
                    }

                    const translateX = -this.currentIndex * 100;
                    this.track.style.transform = `translateX(${translateX}%)`;

                    // Update active states
                    this.cards.forEach((card, index) => {
                        card.classList.toggle('active', index === this.currentIndex);
                    });

                    this.dots.forEach((dot, index) => {
                        dot.classList.toggle('active', index === this.currentIndex);
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
            }

            // Initialize feedback slider
            document.addEventListener('DOMContentLoaded', () => {
                new FeedbackSlider();
            });
        </script>
    </x-slot:scripts>
</x-layouts>
