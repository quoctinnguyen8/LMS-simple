<x-layouts title="Phòng Học - {{ $room->name }}" ogTitle="{{ $room->seo_title }}"
    ogDescription="{{ $room->seo_description }}" ogImage="{{ $room->seo_image }}">

    <!-- Hero Section -->
    <section class="room-detail-hero">
        <div class="room-hero-content">
            <h1>{{ $room->name }}</h1>
        </div>
    </section>

    <!-- Room Detail Section -->
    <section class="room-detail-section">
        <div class="room-detail-container">
            <!-- Room Information Card -->
            <div class="room-detail-card">
                <div class="room-detail-image">
                    <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}">
                    <div class="room-status-badge {{ strtolower($room->status) }}">
                        @php
                            $statusText = match ($room->status) {
                                'available' => 'Có sẵn',
                                'maintenance' => 'Bảo trì',
                                default => 'Không có sẵn',
                            };
                        @endphp
                        {{ $statusText }}
                    </div>
                </div>
                <div class="room-detail-info">
                    <div class="room-specs-grid">
                        <div class="room-spec">
                            <span class="spec-icon">👥</span>
                            <div class="spec-info">
                                <strong>Sức chứa</strong>
                                <span>{{ $room->capacity }} người</span>
                            </div>
                        </div>
                        <div class="room-spec">
                            <span class="spec-icon">📍</span>
                            <div class="spec-info">
                                <strong>Vị trí</strong>
                                <span>{{ $room->location }}</span>
                            </div>
                        </div>
                        <div class="room-spec">
                            <span class="spec-icon">💰</span>
                            <div class="spec-info">
                                <strong>Giá thuê</strong>
                                <span>{{ number_format($room->price, 0, ',', '.') }}
                                    VNĐ/{{ App\Helpers\SettingHelper::get('room_rental_unit') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="room-equipment">
                        <h4>Trang thiết bị có sẵn</h4>
                        <div class="equipment-list">
                            @forelse ($room->equipment as $equipment)
                                <span class="equipment-tag">{{ $equipment->name }}</span>
                            @empty
                                <span class="no-equipment">Không có trang thiết bị</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Description -->
            <div class="room-description-card">
                <h3>Mô tả phòng học</h3>
                <div class="description-content">
                    {!! $room->description !!}
                </div>
            </div>

            <!-- Booking Form -->
            <div class="booking-form-card">
                <form class="room-booking-form" action="{{ route('rooms.bookings') }}" method="POST">
                    @csrf
                    <div class="form-header">
                        <h3>Đặt phòng học</h3>
                        <p>Vui lòng điền đầy đủ thông tin để đặt phòng</p>
                    </div>

                    <input type="hidden" name="room_id" value="{{ $room->id }}">

                    <div class="form-row">
                        <div class="form-group">
                            <x-app-input name="name" label="Họ và tên" required />
                        </div>
                        <div class="form-group">
                            <x-app-input name="email" type="email" label="Email" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <x-app-input name="phone" type="tel" label="Số điện thoại" required />
                        </div>
                        <div class="form-group">
                            <x-app-input name="participants_count" type="number" label="Số người tham gia"
                                value="5" required />
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <x-app-input name="reason" label="Lý do đặt phòng" required />
                    </div>

                    <div class="form-group full-width">
                        <label for="notes">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Ghi chú thêm (không bắt buộc)">{{ old('notes') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="room-type">Loại đặt phòng</label>
                        <select id="room-type" onchange="toggleRecurrence(this.value)" name="room_type">
                            <option value="none" {{ old('room_type') == 'none' ? 'selected' : '' }}>Đặt theo ngày
                            </option>
                            <option value="weekly" {{ old('room_type') == 'weekly' ? 'selected' : '' }}>Đặt theo tuần
                            </option>
                        </select>
                    </div>

                    <div id="recurrence-days" style="display: {{ old('room_type') == 'weekly' ? 'block' : 'none' }};"
                        class="form-group full-width">
                        <label>Chọn ngày trong tuần <span class="required">*</span></label>
                        <div class="checkbox-grid">
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
                            <label class="checkbox-label select-all">
                                <input type="checkbox" name="all_days" value="all" id="all-days-checkbox"
                                    {{ old('all_days') ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Chọn tất cả
                            </label>
                            @foreach ($daysOfWeek as $key => $day)
                                <label class="checkbox-label">
                                    <input type="checkbox" name="repeat_days[]" value="{{ $key }}"
                                        {{ in_array($key, old('repeat_days', [])) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    {{ $day }}
                                </label>
                            @endforeach
                        </div>
                        @error('repeat_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <x-app-input name="start_date" type="date" label="Ngày bắt đầu" required />
                        </div>
                        <div class="form-group">
                            <x-app-input name="end_date" type="date" label="Ngày kết thúc" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <x-app-input name="start_time" type="time" label="Giờ bắt đầu" required />
                        </div>
                        <div class="form-group">
                            <x-app-input name="end_time" type="time" label="Giờ kết thúc" required />
                        </div>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="form-group full-width">
                        <x-recaptcha form-type="room-booking" />
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <span>Đặt phòng ngay</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <x-slot:scripts>
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
            const allDaysCheckbox = document.getElementById('all-days-checkbox');
            allDaysCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('#recurrence-days input[type="checkbox"]');
                if (this.checked) {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                } else {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            });
            let room_type = document.getElementById('room-type');
            let start_date = document.getElementById('start_date');
            let end_date = document.getElementById('end_date');
            start_date.addEventListener('change', function() {
                if (room_type.value === 'none') {
                    end_date.value = start_date.value;
                }
            });
        </script>
    </x-slot:scripts>
</x-layouts>
