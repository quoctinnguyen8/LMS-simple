<x-layouts title="Khóa học">
    <section class="courses-hero">
        <div class="courses-hero-content">
            @if (isset($category))
                <h1>{{ $category->name }}</h1>
                <p>{{ $category->description }}</p>
            @else
                <h1>Các khóa học chất lượng cao</h1>
                <p>Khám phá các khóa học đa dạng và chất lượng tại {{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</p>
            @endif
        </div>
    </section>

    <!-- All Courses Section -->
    <section class="all-courses-section">
        <div class="courses-container">
            @foreach ($courses as $course)
                <div class="course-card-detailed">
                    <div class="course-image">
                        <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                        <div class="course-level">{{ $course->category->name }}</div>
                    </div>
                    <div class="course-info-detailed">
                        <h3>{{ $course->title }}</h3>
                        <div class="course-meta">
                            @if ($course->start_date)
                                <span class="start-date">🗓️ Khai giảng:
                                    {{ $course->start_date->format('d/m/Y') }}</span>
                            @endif
                            @if ($course->registration_deadline)
                                <span class="registration-deadline">⏳ Hạn đăng ký:
                                    {{ $course->registration_deadline->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        <p>{{ $course->description }}</p>
                        <div class="course-price">
                            @if ($course->is_price_visible)
                                <span class="price">{{ number_format($course->price, 0, ',', '.') }}
                                    VNĐ/{{ App\Helpers\SettingHelper::get('course_unit', 'khóa') }}</span>
                            @else
                                <span class="price">Liên hệ để biết thêm chi tiết</span>
                            @endif
                        </div>
                        <div class="course-actions">
                            <button class="enroll-btn"
                                onclick="window.location.href='{{ route('courses.show', $course->slug) }}'">Xem chi
                                tiết</button>
                            <button class="trial-btn" onclick="window.location.href='{{ route('contacts') }}'">Liên hệ
                                tư vấn</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</x-layouts>
