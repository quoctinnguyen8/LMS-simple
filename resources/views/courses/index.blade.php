<x-layouts title="Khóa học">
    <section class="courses-hero">
        <div class="courses-hero-content">
            @if (isset($category))
                <h1>{{ $category->name }}</h1>
                <p>{{ $category->description }}</p>
            @else
                <h1>Tất cả các khóa học</h1>
                <p>Khám phá các khóa học chất lượng cao tại trung tâm đào tạo của chúng tôi.</p>
            @endif
        </div>
    </section>

    <!-- All Courses Section -->
    <section class="all-courses-section">
        <div class="courses-container">
            @foreach ($courses as $course)
                <div class="course-card-detailed">
                    <div class="course-image">
                        <a href="{{ route('courses.show', $course->slug) }}">
                            <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                            <div class="course-level">{{ $course->category->name }}</div>
                        </a>
                    </div>
                    <div class="course-info-detailed">
                        <h3>
                            <a href="{{ route('courses.show', $course->slug) }}">
                                {{ $course->title }}
                            </a>
                        </h3>
                        <div class="course-meta">
                            @if ($course->start_date)
                                <span class="start-date">
                                    <x-heroicon-o-calendar class="inline w-5 h-5 text-gray-500 align-middle" />
                                    Khai giảng: {{ $course->start_date->format('d/m/Y') }}
                                </span>
                            @endif
                            @if ($course->end_registration_date)
                                <span class="registration-deadline">
                                    <x-heroicon-o-clock class="inline w-5 h-5 text-gray-500 align-middle" />
                                    Hạn đăng ký: {{ $course->end_registration_date->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                        <p>
                            {{Str::limit($course->short_description ?? $course->description, 150)}}
                        </p>
                        <div class="course-actions">
                            <button class="enroll-btn"
                                onclick="window.location.href='{{ route('courses.show', $course->slug) }}'">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</x-layouts>
