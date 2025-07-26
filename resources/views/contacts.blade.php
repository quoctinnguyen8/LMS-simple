<x-layouts title="Liên hệ">
    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="contact-hero-content">
            <h1>Liên hệ với chúng tôi</h1>
            <p>Hãy để lại thông tin, chúng tôi sẽ liên hệ tư vấn miễn phí cho bạn</p>
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
                    <p>T2-T6: 8:00 - 21:00<br>T7-CN: 8:00 - 17:00</p>
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="contact-form-container">
            <div class="contact-form-wrapper">
                <div class="form-content">
                    <h2>Đăng ký tư vấn miễn phí</h2>
                    <p>Để lại thông tin liên hệ, chúng tôi sẽ gọi lại trong vòng 24h</p>

                    <form class="contact-form" id="contactForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullName">Họ và tên *</label>
                                <input type="text" id="fullName" name="fullName" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="age">Độ tuổi</label>
                                <select id="age" name="age">
                                    <option value="">Chọn độ tuổi</option>
                                    <option value="3-5">3-5 tuổi</option>
                                    <option value="6-12">6-12 tuổi</option>
                                    <option value="13-17">13-17 tuổi</option>
                                    <option value="18+">18+ tuổi</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="course">Khóa học quan tâm</label>
                            <select id="course" name="course">
                                <option value="">Chọn khóa học</option>
                                <option value="thieu-nhi">Tiếng Anh Thiếu nhi</option>
                                <option value="ielts">Luyện thi IELTS</option>
                                <option value="giao-tiep">Tiếng Anh giao tiếp</option>
                                <option value="doanh-nghiep">Tiếng Anh doanh nghiệp</option>
                                <option value="toeic">Luyện thi TOEIC</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="message">Tin nhắn</label>
                            <textarea id="message" name="message" rows="4" placeholder="Chia sẻ mục tiêu học tập hoặc câu hỏi của bạn..."></textarea>
                        </div>

                        <button type="submit" class="submit-btn">
                            <span>Gửi thông tin</span>
                            <div class="btn-loader" style="display: none;"></div>
                        </button>
                    </form>
                </div>

                <div class="form-image">
                    <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/07/hinh-nen-may-tinh-thien-nhien-tuyet-tac-dong-co-xanh.jpg"
                        alt="Tư vấn học tập">
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Map Section -->
    <section class="map-section">
        <div class="map-container">
            <h2>Vị trí trung tâm</h2>
            <div class="map-wrapper">
                <iframe
                    src="{{ App\Helpers\SettingHelper::get('google_map', '')}}"
                    width="100%" height="450" style="border:0;" allowfullscreen=""
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
</x-layouts>