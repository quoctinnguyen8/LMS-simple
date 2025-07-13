<x-layouts title="Phòng học">
    <section class="rooms">
        <h1>Phòng Học</h1>
        <div class="room-list">
            @foreach ($rooms as $room)
                <div class="room-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                    </div>
                    <div class="card-info">
                        <span class="badge">
                            @php
                                $statusText = match ($room->status) {
                                    'available' => 'Trống',
                                    'maintenance' => 'Bảo trì',
                                    default => 'Đang sử dụng',
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
