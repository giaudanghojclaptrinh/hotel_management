    @extends('layouts.app')
    @section('title', 'Về chúng tôi - Luxury Stay')

    @section('content')

    <!-- 1. HERO SECTION -->
    <div class="about-hero">
        <img src="{{ asset('uploads/home/home.png') }}" alt="About Luxury Stay" class="about-hero-bg">
        <div class="banner-overlay"></div> <!-- Dùng lại overlay của home.css -->
        
        <div class="about-hero-content fade-up">
            <span class="about-subtitle">Câu chuyện của chúng tôi</span>
            <h1 class="about-title">Hành trình kiến tạo <br> Đẳng cấp nghỉ dưỡng</h1>
        </div>
    </div>

    <!-- 2. OUR STORY -->
    <section class="section-story">
        <div class="container">
            <div class="story-grid">
                <!-- Cột trái: Hình ảnh -->
                <div class="story-images fade-up">
                    <div class="img-frame-1">
                        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Luxury Interior">
                    </div>
                    <div class="img-frame-2">
                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Resort Pool">
                    </div>
                </div>

                <!-- Cột phải: Nội dung -->
                <div class="story-content fade-up">
                    <span class="sub-title">Về Luxury Stay</span>
                    <h2>Nơi cảm xúc thăng hoa</h2>
                    <p class="lead">"Chúng tôi không chỉ kinh doanh phòng nghỉ, chúng tôi kiến tạo những kỷ niệm khó quên."</p>
                    <p>
                        Được thành lập vào năm 2015, Luxury Stay bắt đầu với một ước mơ đơn giản: mang đến trải nghiệm nghỉ dưỡng 5 sao đích thực ngay tại trung tâm thành phố. Từ một khách sạn boutique nhỏ, chúng tôi đã vươn mình trở thành biểu tượng của sự sang trọng và lòng hiếu khách.
                    </p>
                    <p>
                        Mỗi góc nhỏ tại Luxury Stay đều được chăm chút tỉ mỉ, từ kiến trúc Art Deco cổ điển đến những tiện nghi công nghệ hiện đại nhất. Chúng tôi tin rằng, sự sang trọng không chỉ nằm ở vẻ bề ngoài lộng lẫy, mà còn ở sự tận tâm phục vụ từ trái tim.
                    </p>
                    <div class="mt-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Signature_sample.svg" alt="Signature" style="height: 50px; filter: invert(1);">
                        <p class="text-sm text-gray-400 mt-2 font-bold">Trương Phước Giàu - Sinh viên, Đại học An Giang (Khóa 23DH)</p>
                        <p class="text-xs text-gray-400">Ngành: Công nghệ thông tin</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats removed as requested -->

    <!-- 4. CORE VALUES -->
    <section class="section-values">
        <div class="container">
            <div class="section-header fade-up">
                <span class="sub-title">Triết lý kinh doanh</span>
                <h2 class="main-title light">Giá trị cốt lõi</h2>
            </div>

            <div class="values-grid">
                <div class="value-card fade-up">
                    <div class="value-icon"><i class="fa-solid fa-heart"></i></div>
                    <h3>Tận tâm</h3>
                    <p>Phục vụ khách hàng bằng cả trái tim, coi khách hàng như người thân trong gia đình.</p>
                </div>
                <div class="value-card fade-up">
                    <div class="value-icon"><i class="fa-solid fa-gem"></i></div>
                    <h3>Đẳng cấp</h3>
                    <p>Không ngừng nâng cao chất lượng dịch vụ để đạt chuẩn mực quốc tế cao nhất.</p>
                </div>
                <div class="value-card fade-up">
                    <div class="value-icon"><i class="fa-solid fa-leaf"></i></div>
                    <h3>Bền vững</h3>
                    <p>Cam kết phát triển du lịch xanh, bảo vệ môi trường và tôn trọng văn hóa địa phương.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Removed team section per request -->

    <!-- 6. CTA (Tái sử dụng từ home.css) -->
    <section class="section-cta">
        <div class="cta-content fade-up">
            <h2 class="cta-title">Trải nghiệm sự khác biệt</h2>
            <p class="cta-text">Hãy để chúng tôi phục vụ kỳ nghỉ trong mơ của bạn.</p>
            <a href="{{ route('phong.danh-sach') }}" class="btn btn-primary" style="background-color: var(--primary-gold); color: #000;">
                ĐẶT PHÒNG NGAY
            </a>
        </div>
    </section>

    @endsection