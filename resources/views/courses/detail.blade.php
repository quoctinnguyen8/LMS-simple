<x-layouts title="Khóa Học - {{ $course->title }}" ogTitle="{{ $course->seo_title }}"
    ogDescription="{{ $course->seo_description }}" ogImage="{{ $course->seo_image }}">
    
    <!-- Course Detail Hero -->
    <section class="course-detail-hero">
        <div class="course-detail-content">
            <h1>{{ $course->title }}</h1>
            <p>{{ $course->description ?? 'Khám phá khóa học chất lượng cao tại ' . App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</p>
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
                                    <i>🗓️</i>
                                    <strong>Khai giảng:</strong> {{ $course->start_date->format('d/m/Y') }}
                                </span>
                            @endif
                            @if ($course->registration_deadline)
                                <span class="detail-meta-item">
                                    <i>⏳</i>
                                    <strong>Hạn đăng ký:</strong> {{ $course->registration_deadline->format('d/m/Y') }}
                                </span>
                            @endif
                            <span class="detail-meta-item">
                                <i>👥</i>
                                <strong>Số lượng tối đa:</strong> {{ $course->max_students }} người
                            </span>
                            <span class="detail-meta-item">
                                <i>📊</i>
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
                                <span class="price-amount">{{ number_format($course->price, 0, ',', '.') }} VNĐ / {{ App\Helpers\SettingHelper::get('course_rental_unit', 'khóa') }}</span>
                            @else
                                <span class="price-contact">Liên hệ để biết thêm chi tiết</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Course Content -->
                <div class="course-content-section">
                    <h2>Nội dung khóa học</h2>
                    <div class="course-content-body">
                        {!! $course->content !!}
                    </div>
                </div>
            </div>

            <!-- Registration Form Sidebar -->
            <div class="course-registration-sidebar">
                <div class="registration-form-card">
                    <h3>Đăng ký khóa học</h3>
                    <form class="course-registration-form" action="{{ route('courses.registration') }}" method="POST">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        
                        <div class="form-group">
                            <x-app-input name="name" label="Họ và tên" placeholder="Nhập họ và tên" required />
                        </div>
                        
                        <div class="form-group">
                            <x-app-input name="email" type="email" label="Email" placeholder="Nhập email" required />
                        </div>
                        
                        <div class="form-group">
                            <x-app-input name="phone" type="tel" label="Số điện thoại" placeholder="Nhập số điện thoại" required />
                        </div>
                        
                        <div class="form-group">
                            <x-app-input name="dob" type="date" label="Ngày sinh" required />
                        </div>
                        
                        <div class="form-group">
                            <label for="enroll-gender">Giới tính <span style="color: red;">*</span></label>
                            <select id="enroll-gender" name="gender" required>
                                <option value="">Chọn giới tính</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="enroll-address">Địa chỉ</label>
                            <textarea id="enroll-address" name="address" rows="3" placeholder="Nhập địa chỉ của bạn">{{ old('address') }}</textarea>
                        </div>

                        <!-- reCAPTCHA -->
                        <x-recaptcha form-type="course-registration" />

                        <button type="submit" class="registration-submit-btn">
                            <i>📝</i>
                            Đăng ký ngay
                        </button>
                        
                        <div class="registration-note">
                            <p>📞 Cần tư vấn? <a href="{{ route('contacts') }}">Liên hệ với chúng tôi</a></p>
                        </div>
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
