<?php

namespace App\Filament\Resources\RoomBookingResource\Pages;

use App\Filament\Resources\RoomBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateRoomBooking extends CreateRecord
{
    protected static string $resource = RoomBookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

    // sau khi tạo thành công thì thêm data cho bảng details
    protected function afterCreate(): void
    {
        $record = $this->record; // Record vừa được tạo
        $data = $this->data; // Data từ form
        $totalDays = 0;
        
        Log::info('afterCreate called', ['record_id' => $record->id, 'data' => $data]);
        
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
                \App\Models\RoomBookingDetail::create([
                    'room_booking_id' => $this->record->id, // ID của booking vừa tạo
                    'booking_date' => $date,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $totalDays++;
            }
        } else{
            // Nếu không có repeat_days thì tạo chi tiết cho từng ngày đơn lẻ
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                \App\Models\RoomBookingDetail::create([
                    'room_booking_id' => $this->record->id, // ID của booking vừa tạo
                    'booking_date' => $date->format('Y-m-d'),
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $totalDays++;
            }
        }
        // Thông báo cho booking thường
        \Filament\Notifications\Notification::make()
            ->title('Tạo đặt phòng thành công')
            ->body('Đã tạo đặt phòng cho ' . $totalDays . ' ngày')
            ->success()
            ->send();
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return null; // Tắt thông báo mặc định
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
