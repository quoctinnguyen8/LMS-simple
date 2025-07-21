<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đặt lại mật khẩu</title>
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
        .password-box {
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        .password-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .password {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            background-color: #ffffff;
            padding: 10px 20px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            display: inline-block;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        .login-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            text-decoration: none;
            color: #fffefe!important;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .login-button:hover {
            transform: translateY(-2px);
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
        .note{
            margin: 15px 0;
            font-size: 12px;
            font-style: italic;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            font-style: italic;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Đặt lại mật khẩu</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Xin chào {{ $userName }},
            </div>
            
            <div class="message">
                Một quản trị viên đã đặt lại mật khẩu cho tài khoản của bạn. Dưới đây là thông tin đăng nhập mới:
            </div>
            
            <div class="password-box">
                <div class="password-label">Mật khẩu mới của bạn:</div>
                <div class="password">{{ $newPassword }}</div>
            </div>
            
            <div class="warning">
                <div class="warning-title">⚠️ Lưu ý quan trọng:</div>
                <div class="warning-text">
                    Vì lý do bảo mật, chúng tôi khuyến nghị bạn đổi mật khẩu ngay sau khi đăng nhập lần đầu.
                    Không chia sẻ mật khẩu này với bất kỳ ai.
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="login-button">
                    Đăng nhập ngay
                </a>
            </div>
            <div class="note">
                <p class="message">Email này được gửi tự động từ hệ thống. Vui lòng không trả lời email này.</p>
                <p class="message">Vui lòng liên hệ với quản trị viên nếu bạn cần hỗ trợ.</p>
            </div>
        </div>

        <p class="footer">
            © {{ date('Y') }} {{ \App\Helpers\SettingHelper::get('center_name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
