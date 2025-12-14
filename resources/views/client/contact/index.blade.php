@extends('layouts.app')
@section('title', 'Liên hệ và Phản hồi - Luxury Stay')

@vite(['resources/css/client/contact.css', 'resources/js/client/contact.js'])

@section('content')

<!-- 1. HERO BANNER -->
<div class="contact-hero">
    <img src="{{ asset('uploads/home/home.png') }}" alt="Contact Luxury Stay" class="contact-hero-bg">
    <div class="banner-overlay"></div>
    
    <div class="contact-hero-content">
        <span class="contact-subtitle">Hỗ trợ khách hàng</span>
        <h1 class="contact-title">Liên hệ với chúng tôi</h1>
    </div>
</div>

<!-- 2. CONTACT CONTENT -->
<section class="section-contact">
    <div class="container">
        <div class="contact-grid">
            
            <!-- Cột trái: Thông tin liên hệ -->
            <div class="contact-info-wrapper fade-up">
                
                <!-- Card 1: Thông tin khách sạn -->
                <div class="contact-card">
                    <h3><i class="fa-solid fa-hotel"></i> Luxury Stay Hotel</h3>
                    <ul class="contact-list">
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Long Xuyên, An Giang, Việt Nam</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <a href="tel:+84792008096">+84 792 008 096</a>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:giaudeptrainhat@gmail.com">giaudeptrainhat@gmail.com</a>
                        </li>
                        <li>
                            <i class="fa-solid fa-clock"></i>
                            <span>Phục vụ 24/7</span>
                        </li>
                    </ul>
                </div>

                <!-- Card 2: Thông tin Developer (Cập nhật theo yêu cầu) -->
                <div class="contact-card">
                    <h3><i class="fa-solid fa-code"></i> Đội ngũ phát triển</h3>
                    <ul class="contact-list">
                        <li>
                            <i class="fa-solid fa-user"></i>
                            <strong>Trương Phước Giàu</strong>
                        </li>
                        <li>
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span>Sinh viên khóa 23DH - ĐH An Giang</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-laptop-code"></i>
                            <span>Ngành Công nghệ thông tin</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:giaudeptrainhat@gmail.com">giaudeptrainhat@gmail.com</a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Cột phải: Form liên hệ -->
            <div class="contact-form-card fade-up">
                <div class="form-header">
                    <h2>Gửi tin nhắn</h2>
                    <p>Chúng tôi luôn sẵn sàng lắng nghe ý kiến của bạn. Vui lòng điền vào biểu mẫu dưới đây.</p>
                </div>

                <form id="contact-form" method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="contact-name" class="form-label">Họ và tên</label>
                        <input type="text" id="contact-name" name="name" class="form-input" placeholder="Nhập họ tên của bạn..." required>
                    </div>

                    <div class="form-group">
                        <label for="contact-email" class="form-label">Email</label>
                        <input type="email" id="contact-email" name="email" class="form-input" placeholder="example@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-message" class="form-label">Nội dung tin nhắn</label>
                        <textarea id="contact-message" name="message" rows="5" class="form-textarea" placeholder="Bạn cần hỗ trợ gì?..." required></textarea>
                    </div>

                    <button type="submit" id="contact-submit" class="btn-submit">
                        Gửi phản hồi <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- 3. MAP SECTION -->
<div class="section-map">
    <!-- Nhúng Google Map iframe -->
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3924.627295881372!2d105.43233897489569!3d10.371655866528752!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310a731e7546e26b%3A0x826c65c40639a041!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBBbiBHaWFuZw!5e0!3m2!1svi!2s!4v1709224856789!5m2!1svi!2s" 
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

@endsection