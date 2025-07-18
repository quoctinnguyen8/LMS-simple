<x-layouts title="Phòng Học - {{ $room->name }}" ogTitle="{{ $room->seo_title }}"
    ogDescription="{{ $room->seo_description }}" ogImage="{{ $room->seo_image }}">
    <section class="room-detail">
        <div class="room-header">
            <div class="room-image">
                <img id="room-image" src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
            </div>
            <div class="room-info">
                <h1 id="room-title">{{ $room->name }}</h1>
                <p id="room-capacity"><strong>Sức chứa:</strong> {{ $room->capacity }} người</p>
                <p id="room-status"><strong>Trạng thái:</strong>
                    @php
                        $statusText = match ($room->status) {
                            'available' => 'Có sẵn',
                            'maintenance' => 'Bảo trì',
                            default => 'Không có sẵn',
                        };
                    @endphp
                    {{ $statusText }}
                </p>
                <p id="room-location"><strong>Vị trí:</strong> {{ $room->location }}</p>
                <p id="room-price"><strong>Giá thuê:</strong> {{ number_format($room->price, 0, ',', '.') }}
                    VNĐ/{{ App\Helpers\SettingHelper::get('room_rental_unit') }}</p>
                <p id="room-equipment"><strong>Trang thiết bị có sẵn:</strong>
                    @foreach ($room->equipment as $equipment)
                        {{ $equipment->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                    @if ($room->equipment->isEmpty())
                        Không có trang thiết bị
                    @endif
                </p>
            </div>
        </div>
        <div class="room-description">
            <h2>Mô tả</h2>
            <div>{!! $room->description !!}</div>
        </div>
        <div class="b-form">
            <form class="booking-form" action="{{ route('rooms.bookings') }}" method="POST">
                @csrf
                <h2>Thông tin đặt phòng</h2>
                <input type="hidden" name="room_id" value="{{ $room->id }}">
                <div class="form-row">
                    <div class="form-group half">
                        <x-app-input name="name" label="Họ và tên" required />
                    </div>
                    <div class="form-group half">
                        <x-app-input name="email" type="email" label="Email" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <x-app-input name="phone" type="tel" label="Số điện thoại" required />
                    </div>
                    <div class="form-group half">
                        <x-app-input name="participants_count" type="number" label="Số người tham gia" required />
                    </div>
                </div>
                <div class="form-group half">
                    <x-app-input name="reason" label="Lý do đặt phòng" required />
                </div>
                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Ghi chú">{{ old('notes') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="room-type">Loại đặt phòng</label>
                    <select id="room-type" onchange="toggleRecurrence(this.value)" name="room_type">
                        <option value="none" {{ old('room_type') == 'none' ? 'selected' : '' }}>Đặt 1 ngày</option>
                        <option value="weekly" {{ old('room_type') == 'weekly' ? 'selected' : '' }}>Đặt hàng tuần
                        </option>
                    </select>
                </div>

                <div id="recurrence-days" class="form-group {{ old('room_type') != 'weekly' ? 'hidden' : '' }}">
                    <label>Chọn ngày trong tuần</label>
                    <div class="checkbox-group">
                        @php
                            $daysOfWeek = [
                                'monday' => 'Thứ 2',
                                'tuesday' => 'Thứ 3',
                                'wednesday' => 'Thứ 4',
                                'thursday' => 'Thứ 5',
                                'friday' => 'Thứ 6',
                                'saturday' => 'Thứ 7',
                                'sunday' => 'Chủ nhật',
                            ];
                        @endphp
                        @foreach ($daysOfWeek as $key => $day)
                            <label>
                                <input type="checkbox" name="repeat_days[]" value="{{ $key }}"
                                    {{ in_array($key, old('repeat_days', [])) ? 'checked' : '' }}>
                                {{ $day }}
                            </label>
                        @endforeach
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

                <!-- reCAPTCHA cho form đặt phòng -->
                <x-recaptcha form-type="room-booking" />

                <button type="submit" class="btn-submit">Đặt phòng</button>
                <p class="notify notify-error" id="room-error">Vui lòng điền đầy đủ thông tin bắt buộc.</p>
            </form>
        </div>

    </section>
    <x-slot:scripts>
        <!-- reCAPTCHA Script chỉ cho trang đặt phòng -->
        @if (config('services.recaptcha.enabled', false))
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endif

        <script>
            function toggleRecurrence(type) {
                const recurrenceDays = document.getElementById('recurrence-days');
                recurrenceDays.style.display = type === 'weekly' ? 'block' : 'none';
                const checkboxes = recurrenceDays.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.disabled = type !== 'weekly';
                    if (type !== 'weekly') {
                        checkbox.checked = false;
                    }
                });
            }
        </script>
    </x-slot:scripts>
</x-layouts>
