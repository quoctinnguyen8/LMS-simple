<x-layouts title="Liên hệ">
    <div class="course-detail">
        <h1>Liên hệ với chúng tôi</h1>

        <div class="course-content">
            <div class="contact-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Thông tin liên hệ -->
                <div class="contact-info">
                    <h2>Thông tin liên hệ</h2>

                    @if (App\Helpers\SettingHelper::get('center_name'))
                        <div class="contact-item">
                            <strong>Trung tâm:</strong>
                            <span>{{ App\Helpers\SettingHelper::get('center_name') }}</span>
                        </div>
                    @endif

                    @if (App\Helpers\SettingHelper::get('address'))
                        <div class="contact-item">
                            <strong>Địa chỉ:</strong>
                            <span>{{ App\Helpers\SettingHelper::get('address') }}</span>
                        </div>
                    @endif

                    @if (App\Helpers\SettingHelper::get('phone'))
                        <div class="contact-item">
                            <strong>Điện thoại:</strong>
                            <span>{{ App\Helpers\SettingHelper::get('phone') }}</span>
                        </div>
                    @endif

                    @if (App\Helpers\SettingHelper::get('email'))
                        <div class="contact-item">
                            <strong>Email:</strong>
                            <span>{{ App\Helpers\SettingHelper::get('email') }}</span>
                        </div>
                    @endif

                    @if (App\Helpers\SettingHelper::get('working_hours'))
                        <div class="contact-item">
                            <strong>Giờ làm việc:</strong>
                            <span>{{ App\Helpers\SettingHelper::get('working_hours') }}</span>
                        </div>
                    @endif

                    <!-- Social Media -->
                    <div class="social-links">
                        @if (App\Helpers\SettingHelper::get('facebook'))
                            <a href="{{ App\Helpers\SettingHelper::get('facebook') }}" target="_blank"
                                class="social-link">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                        @endif

                        @if (App\Helpers\SettingHelper::get('zalo'))
                            <a href="{{ App\Helpers\SettingHelper::get('zalo') }}" target="_blank" class="social-link">
                                <img src="{{ asset('images/zalo-icon.png') }}" alt="Zalo" width="16"> Zalo
                            </a>
                        @endif
                    </div>

                    <!-- Google Maps -->
                    @if (App\Helpers\SettingHelper::get('google_map'))
                        <div class="google-map">
                            <h3>Vị trí của chúng tôi</h3>
                            <div class="map-container">
                                {!! App\Helpers\SettingHelper::get('google_map') !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mô tả về trung tâm -->
            @if (App\Helpers\SettingHelper::get('description'))
                <h2>Giới thiệu về trung tâm</h2>
                <div class="description-content">
                    {!! App\Helpers\SettingHelper::get('description') !!}
                </div>
            @endif
        </div>
</x-layouts>

<style>
    .contact-item {
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
    }

    .contact-item strong {
        color: var(--dark);
        margin-bottom: 4px;
    }

    .social-links {
        margin-top: 24px;
        display: flex;
        gap: 16px;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--primary);
        color: var(--white);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: var(--accent);
        transform: translateY(-3px);
    }

    .google-map {
        margin-top: 32px;
    }

    .map-container {
        margin-top: 12px;
        border-radius: 8px;
        overflow: hidden;
        height: 300px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .text-danger {
        color: var(--error);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .alert-success {
        background-color: rgba(6, 214, 160, 0.1);
        border: 1px solid var(--success);
        color: var(--success);
    }

    .alert-danger {
        background-color: rgba(239, 71, 111, 0.1);
        border: 1px solid var(--error);
        color: var(--error);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .contact-container {
            grid-template-columns: 1fr !important;
        }

        .social-links {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
