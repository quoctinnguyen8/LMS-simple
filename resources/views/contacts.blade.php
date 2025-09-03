<x-layouts title="Liên hệ">
    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="contact-hero-content">
            <h1>Liên hệ với chúng tôi</h1>
            <p>Hãy liên hệ với chúng tôi để được tư vấn và hỗ trợ tốt nhất về các dịch vụ đào tạo và học tập.</p>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="contact-info-section">
        <div class="contact-info-container">
            <div class="contact-info-grid">
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <x-heroicon-o-map-pin class="w-8 h-8 text-blue-500" />
                    </div>
                    <h3>Địa chỉ</h3>
                    <p>{{ App\Helpers\SettingHelper::get('address', 'Chưa cập nhật') }}</p>
                </div>
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <x-heroicon-o-phone class="w-8 h-8 text-green-500" />
                    </div>
                    <h3>Điện thoại</h3>
                    <p>{{ App\Helpers\SettingHelper::get('phone', 'Chưa cập nhật') }}</p>
                </div>
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <x-heroicon-o-envelope class="w-8 h-8 text-red-500" />
                    </div>
                    <h3>Email</h3>
                    <p>{{ App\Helpers\SettingHelper::get('email', 'Chưa cập nhật') }}</p>
                </div>
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <x-heroicon-o-clock class="w-8 h-8 text-yellow-500" />
                    </div>
                    <h3>Giờ làm việc</h3>
                    <p><strong>Thứ 2 - Thứ 7</strong></p>
                    <p><strong>Sáng:</strong> 8:00 - 11:30</p>
                    <p><strong>Tối:</strong> 18:00 - 21:00</p>
                    <p><strong>Chủ nhật:</strong> Nghỉ</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="map-container">
            <h2>Vị trí trung tâm</h2>
            <div class="map-wrapper">
               <iframe 
                    src="{{ App\Helpers\SettingHelper::get('google_map', '') }}"
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
</x-layouts>
