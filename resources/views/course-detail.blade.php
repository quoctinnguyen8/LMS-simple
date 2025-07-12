<x-layouts title="Chi tiết khóa học">
    <section class="course-detail">
            <div class="course-header">
                <div class="course-info">
                    <h1 id="course-title">{{ $course->title }}</h1>
                    <p id="course-price"><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>
                    <p id="course-duration"><strong>Thời gian:</strong> Đang tải...</p>
                    <p id="course-status"><strong>Trạng thái:</strong> Đang tải...</p>
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
                <h1>Đăng ký khóa học</h1>
                <div class="enrollment-form-container">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="enrollment-form-column">
                        <x-app-input name="name" label="Họ và tên" required />
                        <x-app-input name="email" type="email" label="Email" required />
                        <x-app-input name="phone" type="tel" label="Số điện thoại" required />
                    </div>
                    <div class="enrollment-form-column">
                        <div class="form-group">
                            <label for="gender">Giới tính</label>
                            <select id="gender" name="gender" required>
                                <option value="">Chọn giới tính</option>
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        <x-app-input name="dob" type="date" label="Ngày sinh" required />
                        <label for="address">Địa chỉ</label>
                        <textarea name="address" rows="3" placeholder="Địa chỉ"></textarea>
                    </div>
                </div>
                <button type="submit">Đăng ký</button>
                <p class="notify notify-error" id="course-error">Vui lòng điền đầy đủ thông tin bắt buộc.</p>
            </form>
        </section>
</x-layouts>
