<x-layouts title="Khóa học">
    <section class="courses">
        <h1>Danh sách khóa học</h1>
        <div class="course-list">
            @foreach ($courses as $course)
                <div class="course-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                    </div>
                    <div class="card-info">
                        @php
                            $statusConfig = match ($course->status) {
                                'draft' => ['style' => 'background-color: gray; color: white;', 'text' => 'Chưa mở'],
                                'published' => [
                                    'style' => 'background-color: green; color: white;',
                                    'text' => 'Đang mở',
                                ],
                                default => ['style' => 'background-color: red; color: white;', 'text' => 'Đóng'],
                            };
                        @endphp
                        <span class="badge" style="{{ $statusConfig['style'] }}">
                            {{ $statusConfig['text'] }}
                        </span>
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
