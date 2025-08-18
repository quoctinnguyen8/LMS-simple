<x-layouts title="Khóa Học - {{ $course->title }}" ogTitle="{{ $course->seo_title }}"
    ogDescription="{{ $course->seo_description }}" ogImage="{{ $course->seo_image }}">

    <!-- Course Detail Hero -->
    <section class="course-detail-hero">
        <div class="course-detail-content">
            <h1>{{ $course->title }}</h1>
            <p>{{ $course->description ?? 'Khám phá khóa học chất lượng cao tại ' . App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}
            </p>
        </div>
    </section>

    <!-- Course Detail Section -->
    <section class="course-detail-section">
        <div class="course-detail-container">
            <div class="course-detail-main">
                <div class="course-detail-card">
                    <div class="course-detail-image">
                        <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                        <div class="course-detail-category">{{ $course->category->name }}</div>
                    </div>

                    <div class="course-detail-info">
                        <div class="course-detail-meta">
                            @if ($course->start_date)
                                <span class="detail-meta-item">
                                    <i>
                                        <x-heroicon-o-calendar class="inline w-5 h-5 text-gray-500 align-middle" />
                                    </i>
                                    <strong>Khai giảng:</strong> {{ $course->start_date->format('d/m/Y') }}
                                </span>
                            @endif
                            @if ($course->registration_deadline)
                                <span class="detail-meta-item">
                                    <i>
                                        <x-heroicon-o-clock class="inline w-5 h-5 text-gray-500 align-middle" />
                                    </i>
                                    <strong>Hạn đăng ký:</strong> {{ $course->registration_deadline->format('d/m/Y') }}
                                </span>
                            @endif
                            <span class="detail-meta-item">
                                <i>
                                    <x-heroicon-o-users class="inline w-5 h-5 text-gray-500 align-middle" />
                                </i>
                                <strong>Số lượng tối đa:</strong> {{ $course->max_students }} người
                            </span>
                            <span class="detail-meta-item">
                                <i>
                                    <x-heroicon-o-user class="inline w-5 h-5 text-gray-500 align-middle" />
                                </i>
                                <strong>Trạng thái:</strong>
                                @php
                                    $statusText = match ($course->status) {
                                        'published' => 'Đang hoạt động',
                                        'draft' => 'Chưa công bố',
                                        default => 'Không hoạt động',
                                    };
                                @endphp
                                <span class="status-badge status-{{ $course->status }}">{{ $statusText }}</span>
                            </span>
                        </div>

                        <div class="course-detail-price">
                            @if ($course->is_price_visible)
                                <span class="price-amount">{{ number_format($course->price, 0, ',', '.') }} VNĐ /
                                    {{ App\Helpers\SettingHelper::get('course_rental_unit', 'khóa') }}</span>
                            @else
                                <span class="price-contact">Liên hệ để biết thêm chi tiết</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Course Content -->
                <div class="course-content-section">
                    <h2>Nội dung khóa học</h2>
                    <div class="content-body">
                        {!! $course->content !!}
                    </div>
                </div>
            </div>

            <!-- Registration Form Sidebar -->
            <div class="course-registration-sidebar">
                <div class="registration-form-card">
                    <h3>Đăng ký tư vấn khóa học</h3>
                    <form class="course-registration-form" action="{{ route('courses.registration') }}" method="POST">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">

                        <div class="form-group">
                            <x-app-input name="name" label="Họ và tên" placeholder="Nhập họ và tên" required />
                        </div>

                        <div class="form-group">
                            <x-app-input name="email" type="email" label="Email" placeholder="Nhập email"
                                required />
                        </div>

                        <div class="form-group">
                            <x-app-input name="phone" type="tel" label="Số điện thoại"
                                placeholder="Nhập số điện thoại" required />
                        </div>

                        <!-- reCAPTCHA -->
                        <x-recaptcha form-type="course-registration" />

                        <button type="submit" class="registration-submit-btn">
                            Đăng ký tư vấn!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-slot:scripts>
        @if (config('services.recaptcha.enabled', false))
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endif
    </x-slot:scripts>
</x-layouts>
