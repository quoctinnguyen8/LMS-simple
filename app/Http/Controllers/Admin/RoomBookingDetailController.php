<?php

namespace App\Http\Controllers\Admin;

use App\Models\RoomBookingDetail;
use App\Services\RoomBookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RoomBookingDetailController extends Controller
{
    public function reject(Request $request, $id)
    {
        try {
            $detail = RoomBookingDetail::findOrFail($id);
            
            // Kiểm tra quyền và điều kiện
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện thao tác này'], 401);
            }
            
            // Chỉ cho phép từ chối nếu status là pending hoặc bị trùng lịch
            if ($detail->status !== 'pending' && !$detail->is_duplicate) {
                return response()->json(['success' => false, 'message' => 'Chỉ có thể từ chối các ngày có trạng thái "Chờ duyệt" hoặc bị trùng lịch'], 400);
            }
            
            // Cập nhật trạng thái
            $detail->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id()
            ]);
            
            // Cập nhật trạng thái is_duplicate cho các booking khác liên quan đến phòng này
            $roomBookingService = new RoomBookingService();
            $roomBookingService->updateDuplicateStatus($detail->room_booking->room_id);
            
            // Kiểm tra xem có cần cập nhật trạng thái của booking chính không
            $this->updateMainBookingStatus($detail->room_booking_id);
            
            Log::info('Room booking detail rejected', [
                'detail_id' => $detail->id,
                'booking_id' => $detail->room_booking_id,
                'booking_code' => $detail->room_booking->booking_code ?? 'Unknown',
                'booking_date' => $detail->booking_date,
                'room_id' => $detail->room_booking->room_id ?? 'Unknown',
                'room_name' => $detail->room_booking->room->name ?? 'Unknown',
                'rejected_by_user_id' => Auth::id(),
                'rejected_by_user_name' => Auth::user()->name ?? 'Unknown',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Đã từ chối ngày đặt phòng thành công']);
            
        } catch (\Exception $e) {
            Log::error('Error rejecting room booking detail', [
                'detail_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
            ]);
            
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi từ chối ngày đặt phòng'], 500);
        }
    }
    
    public function cancel(Request $request, $id)
    {
        try {
            $detail = RoomBookingDetail::findOrFail($id);
            
            // Kiểm tra quyền
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện thao tác này'], 401);
            }
            
            // Chỉ cho phép hủy nếu status là approved
            if ($detail->status !== 'approved') {
                return response()->json(['success' => false, 'message' => 'Chỉ có thể hủy các ngày đã được duyệt'], 400);
            }
            
            // Cho phép hủy các ngày trước đó nhưng không quá 3 ngày
            if ($detail->booking_date < now()->subDays(3)->format('Y-m-d')) {
                return response()->json(['success' => false, 'message' => 'Không thể hủy các ngày đã qua 3 ngày'], 400);
            }
            
            // Cập nhật trạng thái
            $detail->update([
                'status' => 'cancelled',
                'cancelled_by' => Auth::id(),
                'cancelled_by_customer' => false
            ]);
            
            // Cập nhật trạng thái is_duplicate cho các booking khác liên quan đến phòng này
            $roomBookingService = new RoomBookingService();
            $roomBookingService->updateDuplicateStatus($detail->room_booking->room_id);
            
            // Kiểm tra xem có cần cập nhật trạng thái của booking chính không
            $this->updateMainBookingStatus($detail->room_booking_id);
            
            Log::info('Room booking detail cancelled', [
                'detail_id' => $detail->id,
                'booking_id' => $detail->room_booking_id,
                'booking_code' => $detail->room_booking->booking_code ?? 'Unknown',
                'booking_date' => $detail->booking_date,
                'room_id' => $detail->room_booking->room_id ?? 'Unknown',
                'room_name' => $detail->room_booking->room->name ?? 'Unknown',
                'cancelled_by_user_id' => Auth::id(),
                'cancelled_by_user_name' => Auth::user()->name ?? 'Unknown',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Đã hủy ngày đặt phòng thành công']);
            
        } catch (\Exception $e) {
            Log::error('Error cancelling room booking detail', [
                'detail_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
            ]);
            
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi hủy ngày đặt phòng'], 500);
        }
    }
    
    /**
     * Cập nhật trạng thái của booking chính dựa trên trạng thái của các detail
     */
    private function updateMainBookingStatus($bookingId)
    {
        $booking = \App\Models\RoomBooking::find($bookingId);
        if (!$booking) {
            return;
        }
        
        $details = $booking->room_booking_details;
        $totalDetails = $details->count();
        
        if ($totalDetails === 0) {
            return;
        }
        
        $approvedCount = $details->where('status', 'approved')->count();
        $rejectedCount = $details->where('status', 'rejected')->count();
        $cancelledCount = $details->where('status', 'cancelled')->count();
        $pendingCount = $details->where('status', 'pending')->count();
        
        // Nếu tất cả đều bị từ chối
        $updateData = [];
        if ($rejectedCount === $totalDetails) {
            $updateData['status'] = 'rejected';
        }
        // Nếu tất cả đều bị hủy
        elseif ($cancelledCount === $totalDetails) {
            $updateData['status'] = 'cancelled_by_admin';
        }
        // Nếu có ít nhất 1 ngày được duyệt
        elseif ($approvedCount > 0) {
            $updateData['status'] = 'approved';
        }
        // Nếu vẫn còn ngày chờ duyệt
        elseif ($pendingCount > 0) {
            $updateData['status'] = 'pending';
        }
        // nếu không còn record pending nào bị trùng thì cập nhật is_duplicate thành false
        $updateData['is_duplicate'] = true;
        $pendingDuplicates = $details->where('status', 'pending')->where('is_duplicate', true)->count();
        if ($pendingDuplicates == 0) {
            $updateData['is_duplicate'] = false;
        }

        $booking->update($updateData);
    }
}
