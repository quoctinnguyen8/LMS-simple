<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoomBookingService
{
    public function prepareBookingData(array $data): array
    {
        // Tự động tạo booking code có 6 ký tự ngẫu nhiên
        $data['booking_code'] = 'BK' . substr(strtoupper(md5(time())), 0, 6);
        // Nếu booking code đã tồn tại thì tạo lại code khác
        // Thử tối đa 10 lần để tránh vòng lặp vô hạn
        $attempts = 0;
        while (\App\Models\RoomBooking::where('booking_code', $data['booking_code'])->exists()) {
            $data['booking_code'] = 'BK' . substr(strtoupper(time() . $attempts), 0, 6);
            $attempts++;
            if ($attempts >= 10) {
                throw new \Exception('Không thể tạo booking code duy nhất sau 10 lần thử.');
            }
        }

        $data['created_by'] = Auth::id();

        return $data;
    }

    public function createBookingDetails($record, $data): void
    {
        $totalDays = 0;
        $hasConflicts = false;

        Log::info('createBookingDetails called', ['record_id' => $record->id, 'data' => $data]);

        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $endDate = \Carbon\Carbon::parse($data['end_date']);

        // Nếu có trường repeat_days thì xử lý tạo các ngày lặp lại
        if (isset($data['repeat_days']) && is_array($data['repeat_days']) && !empty($data['repeat_days'])) {
            $repeatDates = [];

            // Lặp qua từng ngày trong khoảng thời gian
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                // Lấy tên ngày trong tuần (monday, tuesday, ...)
                $dayOfWeek = $currentDate->format('l'); // 'Monday', 'Tuesday', ...
                $dayOfWeekLower = strtolower($dayOfWeek); // 'monday', 'tuesday', ...

                // Kiểm tra xem ngày này có trong danh sách repeat_days không
                if (in_array($dayOfWeekLower, $data['repeat_days'])) {
                    $repeatDates[] = $currentDate->format('Y-m-d');
                }

                // Chuyển sang ngày tiếp theo
                $currentDate->addDay();
            }

            // Log hoặc lưu các ngày lặp lại
            Log::info('Repeat dates calculated:', $repeatDates);

            // Tạo các bản ghi chi tiết cho từng ngày lặp lại
            foreach ($repeatDates as $date) {
                // Kiểm tra xung đột lịch
                $conflicts = $this->checkConflict($record->room_id, $data['start_time'], $data['end_time'], $date);
                \App\Models\RoomBookingDetail::create([
                    'room_booking_id' => $record->id,
                    'booking_date' => $date,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                if ($conflicts) {
                    $hasConflicts = true;
                }

                $totalDays++;
            }
        } else {
            // Nếu không có repeat_days thì tạo chi tiết cho từng ngày đơn lẻ
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // Kiểm tra xung đột lịch
                $conflicts = $this->checkConflict($record->room_id, $data['start_time'], $data['end_time'], $date->format('Y-m-d'));
                \App\Models\RoomBookingDetail::create([
                    'room_booking_id' => $record->id,
                    'booking_date' => $date->format('Y-m-d'),
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                if ($conflicts) {
                    $hasConflicts = true;
                }

                $totalDays++;
            }
        }

        // Cập nhật trạng thái xung đột vào booking chính
        if ($hasConflicts) {
            $record->update(['is_duplicate' => true]);
        }

        // Thông báo cho booking thường
        \Filament\Notifications\Notification::make()
            ->title('Tạo đặt phòng thành công')
            ->body('Đã tạo đặt phòng cho ' . $totalDays . ' ngày' .
            ($hasConflicts ? ' (có xung đột lịch)' : ''))
            ->success()
            ->when($hasConflicts, fn ($notification) => $notification->warning()->icon('heroicon-o-exclamation-triangle'))
            ->send();
    }
    //hàm check xung đột lịch
    public function checkConflict($roomId, $startTime, $endTime, $bookingDate): bool
    {
        $conflicts = \App\Models\RoomBookingDetail::whereHas('room_booking', function ($query) use ($roomId) {
            $query->where('room_id', $roomId)
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'rejected');
        })
            ->where('booking_date', $bookingDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->exists();

        return $conflicts;
    }
}
