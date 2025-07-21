<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($action === 'suspend')
            Thông báo tài khoản bị đình chỉ
        @else
            Thông báo kích hoạt lại tài khoản
        @endif
    </title>
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
            @if($action === 'suspend')
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            @else
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            @endif
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
            @if($action === 'suspend')
                background-color: #fef3c7;
                border: 1px solid #fde68a;
            @else
                background-color: #d1fae5;
                border: 1px solid #a7f3d0;
            @endif
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
            @if($action === 'suspend')
                color: #d97706;
            @else
                color: #059669;
            @endif
            min-width: 120px;
        }
        .info-value {
            color: #374151;
        }
        .warning {
            @if($action === 'suspend')
                background-color: #fef2f2;
                border-left: 4px solid #f87171;
            @else
                background-color: #f0f9ff;
                border-left: 4px solid #3b82f6;
            @endif
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-title {
            font-weight: 600;
            @if($action === 'suspend')
                color: #dc2626;
            @else
                color: #1d4ed8;
            @endif
            margin-bottom: 5px;
        }
        .warning-text {
            @if($action === 'suspend')
                color: #b91c1c;
            @else
                color: #1e40af;
            @endif
            font-size: 14px;
        }
        .contact-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .contact-title {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 10px;
        }
        .contact-text {
            color: #6b7280;
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
            @if($action === 'suspend')
                <h1>⚠️ Thông báo đình chỉ tài khoản</h1>
            @else
                <h1>✅ Thông báo kích hoạt lại tài khoản</h1>
            @endif
        </div>
        
        <div class="content">
            <div class="greeting">
                Xin chào {{ $userName }},
            </div>
            
            <div class="message">
                @if($action === 'suspend')
                    Chúng tôi xin thông báo rằng tài khoản của bạn trên hệ thống <strong>{{ $centerName }}</strong> đã bị đình chỉ bởi quản trị viên.
                @else
                    Chúng tôi vui mừng thông báo rằng tài khoản của bạn trên hệ thống <strong>{{ $centerName }}</strong> đã được kích hoạt lại.
                @endif
            </div>
            
            <div class="info-box">
                @if($action === 'suspend')
                    <h3 style="margin-top: 0; color: #d97706;">📋 Thông tin đình chỉ:</h3>
                @else
                    <h3 style="margin-top: 0; color: #059669;">📋 Thông tin kích hoạt:</h3>
                @endif
                
                <div class="info-item">
                    <span class="info-label">👤 Tên tài khoản:</span>
                    <span class="info-value">{{ $userName }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">📧 Email:</span>
                    <span class="info-value">{{ $userEmail }}</span>
                </div>
                
                <div class="info-item">
                    @if($action === 'suspend')
                        <span class="info-label">🔒 Đình chỉ bởi:</span>
                    @else
                        <span class="info-label">🔓 Kích hoạt bởi:</span>
                    @endif
                    <span class="info-value">{{ $suspendedByUsername }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">⏰ Thời gian:</span>
                    <span class="info-value">{{ $actionDate }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">📝 Lý do:</span>
                    <span class="info-value">{{ $reason }}</span>
                </div>
            </div>
            
            <div class="warning">
                @if($action === 'suspend')
                    <div class="warning-title">⚠️ Tài khoản bị đình chỉ:</div>
                    <div class="warning-text">
                        • Bạn sẽ không thể đăng nhập vào hệ thống<br>
                        • Tất cả quyền truy cập đã bị tạm dừng<br>
                        • Liên hệ quản trị viên để biết thêm chi tiết<br>
                        • Tài khoản có thể được kích hoạt lại sau khi giải quyết vấn đề
                    </div>
                @else
                    <div class="warning-title">✅ Tài khoản đã được kích hoạt:</div>
                    <div class="warning-text">
                        • Bạn có thể đăng nhập vào hệ thống như bình thường<br>
                        • Tất cả quyền truy cập đã được khôi phục<br>
                        • Vui lòng tuân thủ các quy định của hệ thống<br>
                        • Liên hệ hỗ trợ nếu gặp bất kỳ vấn đề nào
                    </div>
                @endif
            </div>
            
            <div class="contact-box">
                <div class="contact-title">📞 Cần hỗ trợ?</div>
                <div class="contact-text">
                    @if($action === 'suspend')
                        Nếu bạn cho rằng việc đình chỉ này là một nhầm lẫn hoặc muốn khiếu nại,<br>
                        vui lòng liên hệ với bộ phận quản trị viên để được hỗ trợ.
                    @else
                        Nếu bạn cần hỗ trợ hoặc có thắc mắc về việc sử dụng hệ thống,<br>
                        vui lòng liên hệ với bộ phận hỗ trợ kỹ thuật.
                    @endif
                </div>
            </div>
            
            <div class="message">
                @if($action === 'suspend')
                    Cảm ơn bạn đã hiểu và hợp tác.
                @else
                    Chào mừng bạn quay lại với hệ thống của chúng tôi!
                @endif
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
