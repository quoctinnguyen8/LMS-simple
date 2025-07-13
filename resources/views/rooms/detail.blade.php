<x-layouts title="Chi tiết phòng học">
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
                <p id="room-equipment"><strong>Trang thiết bị:</strong>
                    @foreach ($room->equipment as $equipment)
                        {{ $equipment->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
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
                    <p>Xem trước lịch đặt phòng của phòng này</p>
                </div>
            </div>

            <form class="booking-form">
                <h2>Đặt phòng</h2>
                <div class="form-group">
                    <label for="room-name">Họ và tên</label>
                    <input type="text" id="room-name" required>
                </div>

                <div class="form-group">
                    <label for="room-email">Email</label>
                    <input type="email" id="room-email" required>
                </div>

                <div class="form-group">
                    <label for="room-title">Tiêu đề đặt phòng</label>
                    <input type="text" id="room-title" required>
                </div>

                <div class="form-group">
                    <label for="room-purpose">Mục đích sử dụng</label>
                    <input type="text" id="room-purpose" required>
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
                        <label><input type="checkbox" name="recurrence-days" value="0"> Chủ nhật</label>
                        <label><input type="checkbox" name="recurrence-days" value="1"> Thứ 2</label>
                        <label><input type="checkbox" name="recurrence-days" value="2"> Thứ 3</label>
                        <label><input type="checkbox" name="recurrence-days" value="3"> Thứ 4</label>
                        <label><input type="checkbox" name="recurrence-days" value="4"> Thứ 5</label>
                        <label><input type="checkbox" name="recurrence-days" value="5"> Thứ 6</label>
                        <label><input type="checkbox" name="recurrence-days" value="6"> Thứ 7</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="room-start-date">Ngày bắt đầu</label>
                        <input type="date" id="room-start-date" min="2025-07-12" required>
                    </div>
                    <div class="form-group half">
                        <label for="room-end-date">Ngày kết thúc</label>
                        <input type="date" id="room-end-date" min="2025-07-12" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="room-start-time">Giờ bắt đầu</label>
                        <input type="time" id="room-start-time" required>
                    </div>
                    <div class="form-group half">
                        <label for="room-end-time">Giờ kết thúc</label>
                        <input type="time" id="room-end-time" required>
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
