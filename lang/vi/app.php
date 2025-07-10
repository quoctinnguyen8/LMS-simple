<?php

return [
    // Tên ứng dụng
    'name' => 'Hệ thống quản lý học tập',
    
    // Các label chung
    'actions' => 'Hành động',
    'add' => 'Thêm',
    'edit' => 'Sửa',
    'delete' => 'Xóa',
    'save' => 'Lưu',
    'cancel' => 'Hủy',
    'close' => 'Đóng',
    'search' => 'Tìm kiếm',
    'filter' => 'Lọc',
    'reset' => 'Đặt lại',
    'submit' => 'Gửi',
    'back' => 'Quay lại',
    'next' => 'Tiếp theo',
    'previous' => 'Trước',
    'view' => 'Xem',
    'download' => 'Tải xuống',
    'upload' => 'Tải lên',
    'select' => 'Chọn',
    'choose' => 'Lựa chọn',
    'confirm' => 'Xác nhận',
    'yes' => 'Có',
    'no' => 'Không',
    
    // Trạng thái
    'status' => [
        'active' => 'Hoạt động',
        'inactive' => 'Không hoạt động',
        'pending' => 'Đang chờ',
        'approved' => 'Đã phê duyệt',
        'rejected' => 'Đã từ chối',
        'cancelled' => 'Đã hủy',
        'completed' => 'Hoàn thành',
        'draft' => 'Nháp',
        'published' => 'Đã xuất bản',
        'archived' => 'Đã lưu trữ',
        'available' => 'Có sẵn',
        'unavailable' => 'Không có sẵn',
        'maintenance' => 'Bảo trì',
    ],
    
    // Thời gian
    'time' => [
        'created_at' => 'Ngày tạo',
        'updated_at' => 'Ngày cập nhật',
        'deleted_at' => 'Ngày xóa',
        'start_date' => 'Ngày bắt đầu',
        'end_date' => 'Ngày kết thúc',
        'start_time' => 'Thời gian bắt đầu',
        'end_time' => 'Thời gian kết thúc',
        'date' => 'Ngày',
        'time' => 'Thời gian',
        'datetime' => 'Ngày giờ',
    ],
    
    // Vai trò người dùng
    'roles' => [
        'admin' => 'Quản trị viên',
        'subadmin' => 'Quản trị viên phụ',
        'user' => 'Người dùng',
        'teacher' => 'Giáo viên',
        'student' => 'Học sinh',
        'manager' => 'Quản lý',
    ],
    
    // Thông báo
    'messages' => [
        'success' => 'Thành công!',
        'error' => 'Có lỗi xảy ra!',
        'warning' => 'Cảnh báo!',
        'info' => 'Thông tin',
        'created' => 'Tạo thành công!',
        'updated' => 'Cập nhật thành công!',
        'deleted' => 'Xóa thành công!',
        'not_found' => 'Không tìm thấy!',
        'access_denied' => 'Từ chối truy cập!',
        'invalid_data' => 'Dữ liệu không hợp lệ!',
        'confirm_delete' => 'Bạn có chắc chắn muốn xóa?',
    ],
    
    // Navigation
    'navigation' => [
        'dashboard' => 'Bảng điều khiển',
        'users' => 'Người dùng',
        'categories' => 'Danh mục',
        'courses' => 'Khóa học',
        'course_registrations' => 'Đăng ký khóa học',
        'rooms' => 'Phòng học',
        'room_bookings' => 'Đặt phòng',
        'room_booking_groups' => 'Nhóm đặt phòng',
        'equipment' => 'Thiết bị',
        'settings' => 'Cài đặt',
        'reports' => 'Báo cáo',
        'profile' => 'Hồ sơ',
        'logout' => 'Đăng xuất',
    ],
    
    // Thanh toán
    'payment_status' => [
        'paid' => 'Đã thanh toán',
        'unpaid' => 'Chưa thanh toán',
        'refunded' => 'Đã hoàn tiền',
        'partial' => 'Thanh toán một phần',
        'overdue' => 'Quá hạn',
    ],
    
    // Lặp lại
    'recurrence' => [
        'none' => '1 ngày',
        'daily' => 'Hàng ngày',
        'weekly' => 'Hàng tuần',
        'monthly' => 'Hàng tháng',
        'yearly' => 'Hàng năm',
    ],
];
