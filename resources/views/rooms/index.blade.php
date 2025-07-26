<x-layouts title="Phòng Học">
    <!-- Hero Section -->
    <section class="classrooms-hero">
        <div class="classrooms-hero-content">
            <h1>Phòng học tại {{ App\Helpers\SettingHelper::get('center_name', 'Trung tâm đào tạo') }}</h1>
            <p>Khám phá các phòng học hiện đại và tiện nghi của chúng tôi, nơi mang đến trải nghiệm học tập tốt nhất cho
                học viên.</p>
        </div>
    </section>

    <!-- Classrooms Section -->
    <section class="classrooms-section">
        <div class="classrooms-container">
            @foreach ($rooms as $room)
                <div class="classroom-card">
                    <div class="classroom-image">
                        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                        <div class="classroom-overlay">
                            <button class="view-btn"
                                onclick="window.location.href='{{ route('rooms.show', $room->id) }}'">Xem chi
                                tiết</button>
                        </div>
                    </div>
                    <div class="classroom-info">
                        <h3>{{ $room->name }}</h3>
                        <div class="classroom-specs">
                                <x-heroicon-o-user-group class="inline w-5 h-5 text-gray-500 align-middle mr-1" />
                                {{ $room->capacity }} chỗ ngồi
                        </div>
                      <div class="classroom-location">
                            <span class="location">
                                <x-heroicon-o-map-pin class="inline w-5 h-5 text-gray-500 align-middle" />
                                {{ Str::limit($room->location, 50, '...') }}
                            </span>
                        </div>
                        <div class="classroom-price">
                            <span class="price">
                                <x-heroicon-o-currency-dollar class="inline w-5 h-5 text-gray-500 align-middle" />
                                {{ number_format($room->price, 0, ',', '.') }} VNĐ/{{ App\Helpers\SettingHelper::get('room_unit', 'giờ') }}
                            </span>
                        </div>
                        <style>
                            .classroom-location {
                                font-size: 0.95rem;
                                color: #555;
                                margin-bottom: 4px;
                            }
                            .classroom-price {
                                font-size: 1rem;
                                color: #f60;
                                font-weight: 500;
                                margin-bottom: 4px;
                            }
                        </style>
                        <div class="classroom-status available">
                            {{ $room->status == 'available' ? 'Có sẵn' : 'Đã đặt' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</x-layouts>
