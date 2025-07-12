<x-layouts title="Trang chủ">
    <section class="courses">
            <h1>Khóa Học Tiếng Anh</h1>
            <div class="course-list">
                @foreach($courses as $course)
                    <div class="course-card">
                        <div class="card-image">
                            <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                        </div>
                        <div class="card-info">
                            <span class="badge">
                                {{-- 'draft','published','archived' --}}
                                @php
                                    $statusText = match($course->status) {
                                        'draft' => 'Chưa mở',
                                        'published' => 'Đang mở',
                                        default => 'Đóng'
                                    };
                                @endphp
                                {{ $statusText }}
                            </span>
                            <h2>{{ $course->title }}</h2>
                            <p>{{ $course->description }}</p>
                            <p><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn">Xem Chi Tiết</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <h1>Phòng Học</h1>
            <div class="room-list">
                @foreach($rooms as $room)
                    <div class="room-card">
                        <div class="card-image">
                            <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                        </div>
                        <div class="card-info">
                            <span class="badge">
                                @php
                                    $statusText = match($room->status) {
                                        'available' => 'Trống',
                                        'maintenance' => 'Bảo trì',
                                        default => 'Đang sử dụng'
                                    };
                                @endphp
                                {{ $statusText }}
                            </span>
                            <h2>{{ $room->name }}</h2>
                            <p>{{ $room->description }}</p>
                            <p><strong>Sức chứa:</strong> {{ $room->capacity }} người</p>
                            <a href="{{ route('rooms.show', $room->id) }}" class="btn">Xem Chi Tiết</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
</x-layouts>