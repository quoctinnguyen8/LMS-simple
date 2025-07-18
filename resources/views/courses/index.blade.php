<x-layouts title="Khóa học">
    <section class="courses">
        @if (isset($category))
            <h1>{{ $category->name }}</h1>
            <p class="category-description">{{ $category->description }}</p>
        @else
            <h1>Danh sách Khóa học</h1>
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
                            <p id="course-price"><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }}
                                VNĐ</p>
                        @else
                            <p id="course-price"><strong>Giá:</strong> Liên hệ để biết thêm chi tiết</p>
                        @endif
                        <a href="{{ route('courses.show', $course->slug) }}" class="btn">Xem Chi Tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts>
