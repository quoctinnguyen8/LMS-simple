<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt phòng - {{ $centerName }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .message {
            margin-bottom: 25px;
            color: #4b5563;
        }

        .booking-box {
            background-color: #f0fdf4;
            border: 2px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .booking-item {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }

        .booking-label {
            font-weight: 600;
            color: #059669;
            min-width: 160px;
        }

        .booking-value {
            color: #374151;
            background-color: #ffffff;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            flex: 1;
        }

        .status-pending {
            background-color: #fef3c7;
            border-color: #fcd34d;
            color: #92400e;
            font-weight: bold;
        }

        .status-approved {
            background-color: #dcfce7;
            border-color: #86efac;
            color: #166534;
            font-weight: bold;
        }

        .status-rejected {
            background-color: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
            font-weight: bold;
        }

        .status-cancelled {
            background-color: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
            font-weight: bold;
        }

        .booking-code-highlight {
            background-color: #f0f9ff;
            border-color: #0ea5e9;
            color: #0c4a6e;
            font-weight: bold;
            font-size: 16px;
        }

        .details-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .details-table th,
        .details-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .details-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .warning-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 5px;
        }

        .warning-text {
            color: #b45309;
            font-size: 14px;
        }

        .success {
            background-color: #dcfce7;
            border-left: 4px solid #22c55e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .success-title {
            font-weight: 600;
            color: #166534;
            margin-bottom: 5px;
        }

        .success-text {
            color: #15803d;
            font-size: 14px;
        }

        .error {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .error-title {
            font-weight: 600;
            color: #dc2626;
            margin-bottom: 5px;
        }

        .error-text {
            color: #dc2626;
            font-size: 14px;
        }

        .contact-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }

        .contact-title {
            font-weight: 600;
            color: #0c4a6e;
            margin-bottom: 10px;
        }

        .contact-info {
            color: #0369a1;
            font-size: 14px;
            margin: 5px 0;
        }

        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @if ($status === 'pending')
                <h1>Xác nhận đặt phòng - Đang chờ duyệt</h1>
            @elseif($status === 'approved')
                <h1>Xác nhận đặt phòng - Đã phê duyệt</h1>
            @elseif($status === 'rejected') 
                <h1>Xác nhận đặt phòng - Đã từ chối</h1>    
            @elseif($status === 'cancelled_by_admin')
                <h1>Xác nhận đặt phòng - Đã hủy bởi quản trị viên</h1>
            @elseif($status === 'cancelled_by_customer')
                <h1>Xác nhận đặt phòng - Đã hủy theo yêu cầu của bạn</h1>
            @else
                <h1>Xác nhận đặt phòng - {{ ucfirst($status) }}</h1>
            @endif
        </div>

        <div class="content">
            <div class="greeting">
                Kính chào {{ $customerName }},
            </div>

            <div class="message">
                @if ($status === 'pending')
                    Cảm ơn bạn đã đặt phòng tại <strong>{{ $centerName }}</strong>. Yêu cầu đặt phòng của bạn đã được
                    tiếp nhận và đang chờ phê duyệt từ bộ phận quản lý.
                @elseif($status === 'approved')
                    Chúc mừng! Yêu cầu đặt phòng của bạn tại <strong>{{ $centerName }}</strong> đã được phê duyệt
                    thành công.
                @elseif($status === 'rejected')
                    Rất tiếc, yêu cầu đặt phòng của bạn tại <strong>{{ $centerName }}</strong> đã bị từ chối.
                @elseif($status === 'cancelled_by_admin')
                    Yêu cầu đặt phòng của bạn tại <strong>{{ $centerName }}</strong> đã bị hủy bởi quản trị viên.
                @elseif($status === 'cancelled_by_customer')
                    Yêu cầu đặt phòng của bạn tại <strong>{{ $centerName }}</strong> đã được hủy theo yêu cầu của bạn.
                @endif
            </div>

            <div class="booking-box">
                <h3 style="margin-top: 0; color: #059669;">📋 Thông tin đặt phòng:</h3>

                <div class="booking-item">
                    <span class="booking-label">🏷️ Mã đặt phòng:</span>
                    <span class="booking-value booking-code-highlight">{{ $bookingCode }}</span>
                </div>

                <div class="booking-item">
                    <span class="booking-label">🏢 Tên phòng:</span>
                    <span class="booking-value">{{ $roomName }}</span>
                </div>

                @if ($roomLocation)
                    <div class="booking-item">
                        <span class="booking-label">📍 Vị trí:</span>
                        <span class="booking-value">{{ $roomLocation }}</span>
                    </div>
                @endif

                <div class="booking-item">
                    <span class="booking-label">📅 Ngày bắt đầu:</span>
                    <span class="booking-value">{{ $startDate }}</span>
                </div>

                @if ($endDate)
                    <div class="booking-item">
                        <span class="booking-label">📅 Ngày kết thúc:</span>
                        <span class="booking-value">{{ $endDate }}</span>
                    </div>
                @endif

                <div class="booking-item">
                    <span class="booking-label">📊 Trạng thái:</span>
                    <span
                        class="booking-value 
                        @if ($status === 'pending') status-pending
                        @elseif($status === 'approved') status-approved
                        @elseif($status === 'rejected') status-rejected
                        @elseif(in_array($status, ['cancelled_by_admin', 'cancelled_by_customer'])) status-cancelled @endif">
                        @if ($status === 'pending')
                            ⏳ Đang chờ duyệt
                        @elseif($status === 'approved')
                            ✅ Đã phê duyệt
                        @elseif($status === 'rejected')
                            ❌ Đã từ chối
                        @elseif($status === 'cancelled_by_admin')
                            🚫 Đã hủy bởi quản trị viên
                        @elseif($status === 'cancelled_by_customer')
                            🚫 Đã hủy bởi khách hàng
                        @else
                            {{ ucfirst($status) }}
                        @endif
                    </span>
                </div>

                <div class="booking-item">
                    <span class="booking-label">📅 Ngày đặt:</span>
                    <span class="booking-value">{{ $createdAt }}</span>
                </div>
            </div>

            @if (count($bookingDetails) > 0)
                <div class="details-box">
                    <h3 style="margin-top: 0; color: #4b5563;">📅 Chi tiết lịch đặt phòng:</h3>
                    <table class="details-table">
                        <thead>
                            <tr>
                                <th>📅 Ngày</th>
                                <th>⏰ Thời gian</th>
                                <th>📊 Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookingDetails as $detail)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($detail->booking_date)->format('d/m/Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($detail->end_time)->format('H:i') }}
                                    </td>
                                    <td>
                                        @if ($detail->status === 'pending')
                                            ⏳ Đang chờ duyệt
                                        @elseif($detail->status === 'approved')
                                            ✅ Đã phê duyệt
                                        @elseif($detail->status === 'rejected')
                                            ❌ Đã từ chối
                                        @elseif($detail->status === 'cancelled')
                                            🚫 Đã hủy
                                        @else
                                            {{ ucfirst($detail->status) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($status === 'pending')
                <div class="warning">
                    <div class="warning-title">⚠️ Lưu ý quan trọng:</div>
                    <div class="warning-text">
                        • Yêu cầu đặt phòng của bạn đang chờ phê duyệt từ bộ phận quản lý<br>
                        • Bạn sẽ nhận được email thông báo khi yêu cầu được duyệt hoặc từ chối<br>
                        • Thời gian xử lý yêu cầu có thể từ 1h đến 24h tùy vào tình trạng phòng và số lượng yêu cầu<br>
                        • Nếu không nhận được phản hồi trong vòng 24h, vui lòng liên hệ với chúng tôi để được hỗ trợ
                        <br>
                        • Vui lòng giữ mã đặt phòng để tra cứu và liên hệ khi cần
                    </div>
                </div>
            @elseif($status === 'approved')
                <div class="success">
                    <div class="success-title">🎉 Chúc mừng!</div>
                    <div class="success-text">
                        • Yêu cầu đặt phòng của bạn đã được phê duyệt thành công<br>
                        • Bạn có thể sử dụng phòng trong thời gian đã đăng ký<br>
                        • Vui lòng có mặt đúng giờ và tuân thủ các quy định sử dụng phòng<br>
                        • Liên hệ với chúng tôi nếu có thay đổi về lịch sử dụng
                    </div>
                </div>
            @elseif($status === 'rejected')
                <div class="error">
                    <div class="error-title">❌ Yêu cầu bị từ chối</div>
                    <div class="error-text">
                        • Rất tiếc, yêu cầu đặt phòng của bạn đã bị từ chối<br>
                        • Có thể do phòng đã được đặt trong thời gian này hoặc không đáp ứng yêu cầu<br>
                        • Bạn có thể thử đặt phòng khác hoặc thời gian khác<br>
                        • Liên hệ với chúng tôi để được hỗ trợ tìm phòng phù hợp
                    </div>
                </div>
            @elseif(in_array($status, ['cancelled_by_admin', 'cancelled_by_customer']))
                <div class="error">
                    <div class="error-title">🚫 Đặt phòng đã bị hủy</div>
                    <div class="error-text">
                        @if ($status === 'cancelled_by_admin')
                            • Đặt phòng đã bị hủy bởi quản trị viên<br>
                            • Có thể do phòng bảo trì hoặc có sự kiện ưu tiên cao hơn<br>
                        @else
                            • Đặt phòng đã được hủy theo yêu cầu của bạn<br>
                        @endif
                        • Bạn có thể đặt phòng mới nếu cần<br>
                        • Liên hệ với chúng tôi nếu cần hỗ trợ thêm
                    </div>
                </div>
            @endif

            <div class="details-box">
                <h3 style="margin-top: 0; color: #4b5563;">💡 Những bước tiếp theo:</h3>
                <ul style="color: #6b7280; margin: 10px 0; padding-left: 20px;">
                    @if ($status === 'pending')
                        <li>Chờ email xác nhận từ bộ phận quản lý</li>
                        <li>Chuẩn bị tài liệu và thiết bị cần thiết</li>
                        <li>Liên hệ với chúng tôi nếu có thắc mắc</li>
                    @elseif($status === 'approved')
                        <li>Có mặt đúng giờ tại phòng đã đặt</li>
                        <li>Tuân thủ quy định sử dụng phòng</li>
                        <li>Thông báo ngay nếu có thay đổi kế hoạch</li>
                    @elseif($status === 'rejected')
                        <li>Xem xét đặt phòng khác hoặc thời gian khác</li>
                        <li>Liên hệ để được tư vấn về phòng phù hợp</li>
                    @else
                        <li>Liên hệ với chúng tôi nếu cần hỗ trợ</li>
                        <li>Có thể đặt phòng mới nếu cần thiết</li>
                    @endif
                </ul>
            </div>

            <div class="contact-box">
                <div class="contact-title">📞 Thông tin liên hệ</div>
                @if ($centerPhone)
                    <div class="contact-info">☎️ Điện thoại: {{ $centerPhone }}</div>
                @endif
                @if ($centerEmail)
                    <div class="contact-info">📧 Email: {{ $centerEmail }}</div>
                @endif
                @if ($centerAddress)
                    <div class="contact-info">📍 Địa chỉ: {{ $centerAddress }}</div>
                @endif
                <div class="contact-info" style="margin-top: 10px; font-style: italic;">
                    Liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi nào về khóa học
                </div>
            </div>


            <div class="message">
                Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của <strong>{{ $centerName }}</strong>. Chúng tôi cam kết
                mang đến cho bạn dịch vụ tốt nhất!
            </div>
        </div>

        <div class="footer">
            <p>
                Email này là thông báo tự động về việc đặt phòng của bạn.<br>
                Vui lòng không trả lời email này.
            </p>
            <p>
                © {{ date('Y') }} {{ $centerName }}. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
