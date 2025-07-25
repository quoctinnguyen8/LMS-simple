<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đăng ký khóa học - {{ $centerName }}</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
        .registration-box {
            background-color: #eff6ff;
            border: 2px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .registration-item {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        .registration-label {
            font-weight: 600;
            color: #1d4ed8;
            min-width: 160px;
        }
        .registration-value {
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
        .status-confirmed {
            background-color: #dcfce7;
            border-color: #86efac;
            color: #166534;
            font-weight: bold;
        }
        .status-cancelled {
            background-color: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
            font-weight: bold;
        }
        .payment-unpaid {
            background-color: #fef3c7;
            border-color: #fcd34d;
            color: #92400e;
            font-weight: bold;
        }
        .payment-paid {
            background-color: #dcfce7;
            border-color: #86efac;
            color: #166534;
            font-weight: bold;
        }
        .price-highlight {
            background-color: #f0f9ff;
            border-color: #0ea5e9;
            color: #0c4a6e;
            font-weight: bold;
            font-size: 16px;
        }
        .course-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
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
            <h1>📚 Xác nhận đăng ký khóa học thành công</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Kính chào {{ $studentName }},
            </div>
            
            <div class="message">
                Cảm ơn bạn đã đăng ký khóa học tại <strong>{{ $centerName }}</strong>. Thông tin đăng ký của bạn đã được tiếp nhận và đang chờ phê duyệt từ phòng đào tạo.
            </div>
            
            <div class="registration-box">
                <h3 style="margin-top: 0; color: #1d4ed8;">📋 Thông tin đăng ký khóa học:</h3>
                
                <div class="registration-item">
                    <span class="registration-label">📖 Tên khóa học:</span>
                    <span class="registration-value">{{ $courseTitle }}</span>
                </div>
                
                <div class="registration-item">
                    <span class="registration-label">🏷️ Danh mục:</span>
                    <span class="registration-value">{{ $categoryName }}</span>
                </div>
                
                <div class="registration-item">
                    <span class="registration-label">📅 Ngày đăng ký:</span>
                    <span class="registration-value">{{ $registrationDate }}</span>
                </div>
                
                @if($courseStartDate)
                <div class="registration-item">
                    <span class="registration-label">🚀 Ngày khai giảng:</span>
                    <span class="registration-value">{{ $courseStartDate }}</span>
                </div>
                @endif
                
                <div class="registration-item">
                    <span class="registration-label">💰 Học phí:</span>
                    <span class="registration-value price-highlight">{{ $coursePrice }} VNĐ</span>
                </div>
                
                <div class="registration-item">
                    <span class="registration-label">📊 Trạng thái đăng ký:</span>
                    <span class="registration-value 
                        @if($status === 'pending') status-pending
                        @elseif($status === 'confirmed') status-confirmed
                        @elseif($status === 'cancelled') status-cancelled
                        @endif">
                        @if($status === 'pending')
                            ⏳ Đang chờ duyệt
                        @elseif($status === 'confirmed')
                            ✅ Đã xác nhận
                        @elseif($status === 'cancelled')
                            ❌ Đã hủy
                        @elseif($status === 'completed')
                            🏆 Hoàn thành
                        @else
                            {{ ucfirst($status) }}
                        @endif
                    </span>
                </div>
                
                <div class="registration-item">
                    <span class="registration-label">💳 Trạng thái thanh toán:</span>
                    <span class="registration-value 
                        @if($paymentStatus === 'unpaid') payment-unpaid
                        @elseif($paymentStatus === 'paid') payment-paid
                        @endif">
                        @if($paymentStatus === 'unpaid')
                            ⏳ Chưa thanh toán
                        @elseif($paymentStatus === 'paid')
                            ✅ Đã thanh toán
                        @elseif($paymentStatus === 'refunded')
                            🔄 Đã hoàn tiền
                        @else
                            {{ ucfirst($paymentStatus) }}
                        @endif
                    </span>
                </div>
            </div>
            
            @if($status === 'pending')
            <div class="warning">
                <div class="warning-title">⚠️ Lưu ý quan trọng:</div>
                <div class="warning-text">
                    • Đăng ký của bạn đang chờ phê duyệt từ phòng đào tạo<br>
                    • Bạn sẽ nhận được email thông báo khi đăng ký được duyệt hoặc từ chối<br>
                    • Vui lòng chưa thực hiện thanh toán cho đến khi nhận được xác nhận chính thức<br>
                    • Thời gian xử lý đăng ký thường từ 1-3 ngày làm việc
                </div>
            </div>
            @elseif($status === 'confirmed')
            <div class="success">
                <div class="success-title">🎉 Chúc mừng!</div>
                <div class="success-text">
                    • Đăng ký khóa học của bạn đã được xác nhận<br>
                    • Bạn có thể tiến hành thanh toán học phí<br>
                    • Chúng tôi sẽ liên hệ với bạn về thông tin chi tiết lớp học<br>
                    • Vui lòng chuẩn bị đầy đủ tài liệu cần thiết cho khóa học
                </div>
            </div>
            @endif
            
            <div class="course-box">
                <h3 style="margin-top: 0; color: #4b5563;">💡 Những bước tiếp theo:</h3>
                <ul style="color: #6b7280; margin: 10px 0; padding-left: 20px;">
                    @if($status === 'pending')
                    <li>Chờ email xác nhận từ phòng đào tạo</li>
                    <li>Chuẩn bị tài liệu cần thiết cho khóa học</li>
                    <li>Liên hệ với chúng tôi nếu có thắc mắc</li>
                    @elseif($status === 'confirmed')
                    <li>Thực hiện thanh toán học phí theo hướng dẫn</li>
                    <li>Tham gia buổi orientation (nếu có)</li>
                    <li>Chuẩn bị đầy đủ tài liệu và thiết bị học tập</li>
                    @endif
                </ul>
            </div>
            
            <div class="contact-box">
                <div class="contact-title">📞 Thông tin liên hệ</div>
                @if($centerPhone)
                <div class="contact-info">☎️ Điện thoại: {{ $centerPhone }}</div>
                @endif
                @if($centerEmail)
                <div class="contact-info">📧 Email: {{ $centerEmail }}</div>
                @endif
                @if($centerAddress)
                <div class="contact-info">📍 Địa chỉ: {{ $centerAddress }}</div>
                @endif
                <div class="contact-info" style="margin-top: 10px; font-style: italic;">
                    Liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi nào về khóa học
                </div>
            </div>
            
            <div class="message">
                Cảm ơn bạn đã tin tưởng và lựa chọn <strong>{{ $centerName }}</strong> cho hành trình học tập của mình. Chúng tôi cam kết mang đến cho bạn trải nghiệm học tập tốt nhất!
            </div>
        </div>
        
        <div class="footer">
            <p>
                Email này là xác nhận tự động về việc đăng ký khóa học của bạn.<br>
                Vui lòng không trả lời email này.
            </p>
            <p>
                © {{ date('Y') }} {{ $centerName }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>