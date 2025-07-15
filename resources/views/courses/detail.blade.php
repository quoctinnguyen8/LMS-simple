<x-layouts title="Chi tiết khóa học">
    <section class="course-detail">
        <div class="course-header">
            <div class="course-info">
                <h1 id="course-title">{{ $course->title }}</h1>
                <p id="course-price"><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>
                <p id="course-duration"><strong>Ngày bắt đầu:</strong> {{ $course->start_date->format('d/m/Y') }}</p>
                <p id="course-duration"><strong>Ngày kết thúc:</strong> {{ $course->end_date ? $course->end_date->format('d/m/Y') : 'Chưa xác định' }}</p>
                <p id="course-status"><strong>Trạng thái:</strong>
                    @php
                        $statusText = match ($course->status) {
                            'published' => 'Đang hoạt động',
                            'draft' => 'Chưa công bố',
                            default => 'Không hoạt động',
                        };
                    @endphp
                    {{ $statusText }}
                </p>
                <p id="course-description"><strong>Mô tả:</strong> {{ $course->description }}</p>
            </div>
            <div class="course-image">
                <img id="course-image" src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
            </div>
        </div>
        <div class="course-content">
            <h2>Nội dung khóa học</h2>
            <p id="course-content">{{ $course->content }}</p>
        </div>
        <form class="course-enroll" action="{{ route('courses.registration') }}" method="POST">
            @csrf
            <h2>Đăng ký khóa học</h2>
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="form-row">
                <div class="form-group half">
                   <x-app-input name="name" label="Họ và tên" required />
                </div>
                <div class="form-group half">
                    <x-app-input name="email" type="email" label="Email" required />
                </div>
                <div class="form-group half">
                    <x-app-input name="phone" type="tel" label="Số điện thoại" required />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label for="enroll-gender">Giới tính</label>
                    <select id="enroll-gender" name="gender" required>
                        <option value="">Chọn giới tính</option>
                        <option value="male">Nam</option>
                        <option value="female">Nữ</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                <div class="form-group half">
                    <x-app-input name="dob" type="date" label="Ngày sinh" required />
                </div>
            </div>
            <div class="form-group">
                <label for="enroll-address">Địa chỉ</label>
                <textarea id="enroll-address" name="address" rows="3" placeholder="Địa chỉ" ></textarea>
            </div>
            <button type="submit" class="btn-submit">Đăng ký</button>
        </form>
    </section>
</x-layouts>
