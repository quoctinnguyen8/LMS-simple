<x-layouts title="Phòng Học - {{ $room->name }}"
    ogTitle="{{ $room->seo_title }}"
    ogDescription="{{ $room->seo_description }}"
    ogImage="{{$room->seo_image}}">
    <section class="room-detail">
        <div class="room-header">
            <div class="room-info">
                <h1 id="room-title">{{ $room->name }}</h1>
                <p id="room-capacity"><strong>Sức chứa:</strong> {{ $room->capacity }} người</p>
                <p id="room-status"><strong>Trạng thái:</strong>
                    @php
                        $statusText = match ($room->status) {
                            'available' => 'Trống',
                            'maintenance' => 'Bảo trì',
                            default => 'Đang sử dụng',
                        };
                    @endphp
                    {{ $statusText }}
                </p>
                <p id="room-description"><strong>Mô tả:</strong> {{ $room->description }}</p>
                <p id="room-location"><strong>Vị trí:</strong> {{ $room->location }}</p>
                <p id="room-price"><strong>Giá thuê:</strong> {{ number_format($room->price, 0, ',', '.') }} VNĐ/{{ App\Helpers\SettingHelper::get('room_rental_unit') }}</p>
                <p id="room-equipment"><strong>Trang thiết bị có sẵn:</strong>
                    @foreach ($room->equipment as $equipment)
                        {{ $equipment->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                    @if ($room->equipment->isEmpty())
                        Không có trang thiết bị
                    @endif
                </p>
            </div>
            <div class="room-image">
                <img id="room-image" src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
            </div>
        </div>
        <div class="booking-container">
            <div class="booking-calendar">
                <!-- Calendar will be added here in future -->
                <div class="calendar-placeholder">
                    <h2>Lịch đặt phòng</h2>
                    @if ($bookingDetails->isEmpty())
                        <p>Chưa có lịch sử đặt phòng.</p>
                    @else
                        <ul class="booking-list">
                            @foreach ($bookingDetails as $booking)
                                <li class="booking-item">
                                    <strong>{{ $booking->booking_date->format('d/m/Y') }}</strong>
                                    <span class="booking-time">({{ $booking->start_time->format('H:i') }} -
                                        {{ $booking->end_time->format('H:i') }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <form class="booking-form" action="{{ route('rooms.bookings') }}" method="POST">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">
                <h2>Đặt phòng</h2>
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
                        <x-app-input name="reason" label="Lý do đặt phòng" required />
                    </div>
                    <div class="form-group half">
                        <x-app-input name="participants_count" type="number" label="Số người tham gia" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Ghi chú"></textarea>
                </div>
                <div class="form-group">
                    <label for="room-type">Loại đặt phòng</label>
                    <select id="room-type" onchange="toggleRecurrence(this.value)">
                        <option value="none">Đặt 1 ngày</option>
                        <option value="weekly">Đặt hàng tuần</option>
                    </select>
                </div>

                <div id="recurrence-days" class="form-group hidden">
                    <label>Chọn ngày trong tuần</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="repeat_days[]" value="monday"> Thứ 2</label>
                        <label><input type="checkbox" name="repeat_days[]" value="tuesday"> Thứ 3</label>
                        <label><input type="checkbox" name="repeat_days[]" value="wednesday"> Thứ 4</label>
                        <label><input type="checkbox" name="repeat_days[]" value="thursday"> Thứ 5</label>
                        <label><input type="checkbox" name="repeat_days[]" value="friday"> Thứ 6</label>
                        <label><input type="checkbox" name="repeat_days[]" value="saturday"> Thứ 7</label>
                        <label><input type="checkbox" name="repeat_days[]" value="sunday"> Chủ nhật</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <x-app-input name="start_date" type="date" label="Ngày bắt đầu" required />
                    </div>
                    <div class="form-group half">
                        <x-app-input name="end_date" type="date" label="Ngày kết thúc" required />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <x-app-input name="start_time" type="time" label="Giờ bắt đầu" required />
                    </div>
                    <div class="form-group half">
                        <x-app-input name="end_time" type="time" label="Giờ kết thúc" required />
                    </div>
                </div>

                <button type="submit" class="btn-submit">Đặt phòng</button>
                <p class="notify notify-error" id="room-error">Vui lòng điền đầy đủ thông tin bắt buộc.</p>
            </form>
        </div>
    </section>
    <x-slot:scripts>
        <script>
            function toggleRecurrence(type) {
                const recurrenceDays = document.getElementById('recurrence-days');
                recurrenceDays.style.display = type === 'weekly' ? 'block' : 'none';
            }
        </script>
    </x-slot:scripts>
</x-layouts>
