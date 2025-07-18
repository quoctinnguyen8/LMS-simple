<x-layouts title="Phòng Học">
    <section class="rooms">
        <h1>Danh sách phòng học</h1>
        <div class="room-list">
            @foreach ($rooms as $room)
                <div class="room-card">
                    <div class="card-image">
                        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                    </div>
                    <div class="card-info">
                        @php
                            $statusConfig = match ($room->status) {
                                'available' => ['style' => 'background-color: green; color: white;', 'text' => 'Có sẵn'],
                                'maintenance' => [
                                    'style' => 'background-color: orange; color: white;',
                                    'text' => 'Bảo trì',
                                ],
                                default => [
                                    'style' => 'background-color: red; color: white;',
                                    'text' => 'Không có sẵn',
                                ],
                            };
                        @endphp
                        <span class="badge" style="{{ $statusConfig['style'] }}">
                            {{ $statusConfig['text'] }}
                        </span>
                        <h2>{{ $room->name }}</h2>
                        <p><strong>Vị trí:</strong> {{ $room->location }}</p>
                        <p><strong>Sức chứa:</strong> {{ $room->capacity }} người</p>
                        <p><strong>Giá thuê:</strong> {{ number_format($room->price, 0, ',', '.') }}
                            VNĐ/{{ App\Helpers\SettingHelper::get('room_rental_unit') }}</p>
                        <a href="{{ route('rooms.show', $room->id) }}" class="btn">Xem Chi Tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts>
