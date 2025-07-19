<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo thay đổi địa chỉ email</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
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
        .change-box {
            background-color: #f0f9ff;
            border: 2px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .change-item {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        .change-label {
            font-weight: 600;
            color: #0369a1;
            min-width: 140px;
        }
        .change-value {
            color: #374151;
            background-color: #ffffff;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            flex: 1;
            font-family: 'Courier New', monospace;
        }
        .old-email {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }
        .new-email {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
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
        .info-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-item {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            color: #4b5563;
            min-width: 120px;
        }
        .info-value {
            color: #374151;
        }
        .contact-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .contact-title {
            font-weight: 600;
            color: #dc2626;
            margin-bottom: 10px;
        }
        .contact-text {
            color: #b91c1c;
            font-size: 14px;
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
            <h1>📧 Thông báo thay đổi email</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Xin chào {{ $userName }},
            </div>
            
            <div class="message">
                Chúng tôi xin thông báo rằng địa chỉ email tài khoản của bạn trên hệ thống <strong>{{ $centerName }}</strong> đã được thay đổi bởi quản trị viên.
            </div>
            
            <div class="change-box">
                <h3 style="margin-top: 0; color: #0369a1;">📋 Thông tin thay đổi:</h3>
                
                <div class="change-item">
                    <span class="change-label">📧 Email cũ:</span>
                    <span class="change-value old-email">{{ $oldEmail }}</span>
                </div>
                
                <div class="change-item">
                    <span class="change-label">📨 Email mới:</span>
                    <span class="change-value new-email">{{ $newEmail }}</span>
                </div>
            </div>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #4b5563;">ℹ️ Chi tiết thay đổi:</h3>
                
                <div class="info-item">
                    <span class="info-label">👤 Tài khoản:</span>
                    <span class="info-value">{{ $userName }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">🔧 Thay đổi bởi:</span>
                    <span class="info-value">{{ $changedByUsername }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">⏰ Thời gian:</span>
                    <span class="info-value">{{ $changeDate }}</span>
                </div>
            </div>
            
            <div class="warning">
                <div class="warning-title">⚠️ Lưu ý quan trọng:</div>
                <div class="warning-text">
                    • Từ nay, bạn sẽ sử dụng email mới để đăng nhập<br>
                    • Email cũ sẽ không còn được sử dụng cho tài khoản này<br>
                    • Tất cả thông báo trong tương lai sẽ được gửi đến email mới<br>
                    • Vui lòng cập nhật thông tin này trong các ứng dụng khác nếu cần
                </div>
            </div>
            
            <div class="contact-box">
                <div class="contact-title">🚨 Bạn không thực hiện thay đổi này?</div>
                <div class="contact-text">
                    Nếu bạn không yêu cầu thay đổi email hoặc nghi ngờ có hoạt động bất thường,<br>
                    vui lòng liên hệ ngay với quản trị viên để được hỗ trợ và bảo mật tài khoản.
                </div>
            </div>
            
            <div class="message">
                <strong>Thông tin đăng nhập mới:</strong><br>
                📧 Email: <code>{{ $newEmail }}</code><br>
                🔑 Mật khẩu: Không thay đổi (sử dụng mật khẩu hiện tại)
            </div>
            
            <div class="message">
                Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.
            </div>
        </div>
        
        <div class="footer">
            <p>
                Email này được gửi đến địa chỉ email cũ để thông báo về việc thay đổi.<br>
                Các email tiếp theo sẽ được gửi đến địa chỉ email mới.
            </p>
            <p>
                © {{ date('Y') }} {{ $centerName }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
