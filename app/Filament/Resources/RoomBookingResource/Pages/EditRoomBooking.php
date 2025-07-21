<?php

namespace App\Filament\Resources\RoomBookingResource\Pages;

use App\Filament\Resources\RoomBookingResource;
use App\Services\RoomBookingService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRoomBooking extends EditRecord
{
    protected static string $resource = RoomBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

        // chặn truy cập nếu status không phải là 'pending', 'approved'
    public function mount(int|string $record): void
    {
        parent::mount($record);
        
        // Kiểm tra status và chặn truy cập nếu cần
        $recordModel = $this->getRecord();
        if (!in_array($recordModel->status, ['pending', 'approved'])) {
            Notification::make()
                ->title('Không thể chỉnh sửa')
                ->body('Chỉ có thể chỉnh sửa đặt phòng ở trạng thái "Chờ duyệt" hoặc "Đã duyệt"')
                ->danger()
                ->send();
                
            // Redirect về trang index
            $this->redirect($this->getResource()::getUrl('index'));
            return;
        }
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     // Xóa chi tiết cũ nếu có
    //     (new RoomBookingService())->deleteBookingDetails($data['id'] ?? null);
    //     return $data;
    // }

    // sau khi lưu thành công thì thêm lại data cho bảng details
    protected function afterSave(): void
    {
        $record = $this->record; // Record vừa được tạo
        $data = $this->data; // Data từ form

        if ($record->status == 'pending') {   
            $roomBookingService = new RoomBookingService();
            // Xóa chi tiết cũ nếu có
            $roomBookingService->deleteBookingDetails($record->id ?? null);
            $roomBookingService->createBookingDetails($record, $data, false);
            // Cập nhật trạng thái xung đột
            $roomBookingService->updateDuplicateStatus($record->room_id);
        }
        // Thông báo thành công
        Notification::make()
            ->title('Cập nhật thành công')
            ->body('Đặt phòng đã được cập nhật thành công.')
            ->success()
            ->send();
    }

    // xóa thông báo mặc định sau khi lưu
    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return null; // Tắt thông báo mặc định
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
