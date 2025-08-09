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
                    </div>
                    <div class="classroom-info">
                        <h3>{{ $room->name }}</h3>
                        <div class="classroom-specs">
                            <x-heroicon-o-user-group class="inline w-5 h-5 text-gray-500 align-middle" />
                            {{ $room->capacity }} chỗ ngồi
                        </div>
                        <div class="room-equipment">
                            <div class="equipment-list">
                                @forelse ($room->equipment as $equipment)
                                    <span class="equipment-tag">{{ $equipment->name }}</span>
                                @empty
                                    <span class="no-equipment">Không có trang thiết bị</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="classroom-actions">
                            <button class="view-btn"
                                onclick="window.location.href='{{ route('rooms.show', $room->id) }}'">Xem chi
                                tiết</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</x-layouts>
