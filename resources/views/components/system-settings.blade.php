<div class="system-settings">
    {{-- Custom CSS --}}
    @if(!empty($settings['custom_css']))
        <style>
            {!! $settings['custom_css'] !!}
        </style>
    @endif

    {{-- Logo --}}
    @if(!empty($settings['logo']))
        <div class="logo-container">
            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $settings['center_name'] ?? 'Logo' }}" class="logo">
        </div>
    @endif

    {{-- Center Information --}}
    <div class="center-info">
        @if(!empty($settings['center_name']))
            <h1 class="center-name">{{ $settings['center_name'] }}</h1>
        @endif

        @if(!empty($settings['description']))
            <div class="center-description">
                {!! $settings['description'] !!}
            </div>
        @endif

        @if(!empty($settings['address']))
            <div class="address">
                <strong>Địa chỉ:</strong> {{ $settings['address'] }}
            </div>
        @endif

        @if(!empty($settings['phone']))
            <div class="phone">
                <strong>Điện thoại:</strong> 
                <a href="tel:{{ $settings['phone'] }}">{{ $settings['phone'] }}</a>
            </div>
        @endif

        @if(!empty($settings['email']))
            <div class="email">
                <strong>Email:</strong> 
                <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a>
            </div>
        @endif
    </div>

    {{-- Pricing Units --}}
    <div class="pricing-units">
        <h3>Đơn vị tính tiền</h3>
        @if(!empty($settings['course_unit']))
            <div class="pricing-unit">
                <strong>Khóa học:</strong> {{ $settings['course_unit'] }}
            </div>
        @endif

        @if(!empty($settings['room_rental_unit']))
            <div class="pricing-unit">
                <strong>Thuê phòng:</strong> {{ $settings['room_rental_unit'] }}
                @if(!empty($settings['room_unit_to_hour']) && $settings['room_unit_to_hour'] != '1')
                    (1 {{ $settings['room_rental_unit'] }} = {{ $settings['room_unit_to_hour'] }} giờ)
                @endif
            </div>
        @endif
    </div>

    {{-- Google Map --}}
    @if(!empty($settings['google_map']))
        <div class="google-map">
            <h3>Bản đồ</h3>
            <div class="map-container">
                {!! $settings['google_map'] !!}
            </div>
        </div>
    @endif

    {{-- Facebook Fanpage --}}
    @if(!empty($settings['facebook_fanpage']))
        <div class="facebook-fanpage">
            <h3>Facebook Fanpage</h3>
            <div class="fanpage-container">
                {!! $settings['facebook_fanpage'] !!}
            </div>
        </div>
    @endif

    {{-- Zalo --}}
    @if(!empty($settings['zalo_embed']))
        <div class="zalo-embed">
            <h3>Zalo</h3>
            <div class="zalo-container">
                {!! $settings['zalo_embed'] !!}
            </div>
        </div>
    @endif

    {{-- Custom JavaScript --}}
    @if(!empty($settings['custom_js']))
        <script>
            {!! $settings['custom_js'] !!}
        </script>
    @endif
</div>

<style>
.system-settings {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.logo-container {
    text-align: center;
    margin-bottom: 30px;
}

.logo {
    max-width: 200px;
    height: auto;
}

.center-info {
    margin-bottom: 30px;
}

.center-name {
    font-size: 2.5rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.center-description {
    margin-bottom: 20px;
    line-height: 1.6;
}

.address, .phone, .email {
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.phone a, .email a {
    color: #007bff;
    text-decoration: none;
}

.phone a:hover, .email a:hover {
    text-decoration: underline;
}

.pricing-units {
    margin-bottom: 30px;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.pricing-units h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #333;
}

.pricing-unit {
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.google-map, .facebook-fanpage, .zalo-embed {
    margin-bottom: 30px;
}

.google-map h3, .facebook-fanpage h3, .zalo-embed h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #333;
}

.map-container, .fanpage-container, .zalo-container {
    width: 100%;
    overflow: hidden;
}

.map-container iframe, .fanpage-container iframe, .zalo-container iframe {
    width: 100%;
    border: none;
}

@media (max-width: 768px) {
    .center-name {
        font-size: 2rem;
    }
    
    .system-settings {
        padding: 15px;
    }
}
</style>
