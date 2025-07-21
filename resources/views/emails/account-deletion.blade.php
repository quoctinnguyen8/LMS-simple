<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo tài khoản đã bị xóa</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
        .info-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
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
            color: #dc2626;
            min-width: 120px;
        }
        .info-value {
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
            color: #0369a1;
            margin-bottom: 10px;
        }
        .contact-text {
            color: #075985;
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
            <h1>🗑️ Thông báo xóa tài khoản</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Xin chào {{ $userName }},
            </div>
            
            <div class="message">
                Chúng tôi xin thông báo rằng tài khoản của bạn trên hệ thống <strong>{{ $centerName }}</strong> đã bị xóa bởi quản trị viên.
            </div>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #dc2626;">📋 Thông tin tài khoản đã bị xóa:</h3>
                
                <div class="info-item">
                    <span class="info-label">👤 Tên tài khoản:</span>
                    <span class="info-value">{{ $userName }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">📧 Email:</span>
                    <span class="info-value">{{ $userEmail }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">🔒 Xóa bởi:</span>
                    <span class="info-value">{{ $deletedByName }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">⏰ Thời gian xóa:</span>
                    <span class="info-value">{{ $deletionDate }}</span>
                </div>
            </div>
            
            <div class="warning">
                <div class="warning-title">⚠️ Lưu ý quan trọng:</div>
                <div class="warning-text">
                    • Tài khoản của bạn đã bị xóa vĩnh viễn khỏi hệ thống<br>
                    • Tất cả dữ liệu liên quan đến tài khoản đã bị gỡ bỏ<br>
                    • Bạn sẽ không thể đăng nhập vào hệ thống với tài khoản này<br>
                    • Nếu cần khôi phục, vui lòng liên hệ với quản trị viên
                </div>
            </div>
            
            <div class="contact-box">
                <div class="contact-title">📞 Cần hỗ trợ?</div>
                <div class="contact-text">
                    Nếu bạn cho rằng việc xóa tài khoản này là một nhầm lẫn hoặc cần được giải thích thêm,<br>
                    vui lòng liên hệ với bộ phận quản trị viên để được hỗ trợ.
                </div>
            </div>
            
            <div class="message">
                Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.
            </div>
        </div>
        
        <div class="footer">
            <p>
                Email này được gửi tự động từ hệ thống. Vui lòng không trả lời email này.<br>
                Nếu bạn cần hỗ trợ, vui lòng liên hệ với quản trị viên.
            </p>
            <p>
                © {{ date('Y') }} {{ $centerName }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
