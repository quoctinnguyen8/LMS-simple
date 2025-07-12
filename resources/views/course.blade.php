<x-layouts title="Khóa học">
      <section class="courses">
            <h1>Danh sách khóa học</h1>
            <div class="course-list">
                @foreach($courses as $course)
                    <div class="course-card">
                        <div class="card-image">
                            <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                        </div>
                        <div class="card-info">
                            <span class="badge">{{ $course->status ? 'Đang mở' : 'Đóng' }}</span>
                            <h2>{{ $course->title }}</h2>
                            <p>{{ $course->description }}</p>
                            <p><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn">Xem Chi Tiết</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
</x-layouts>