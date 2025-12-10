@extends('layouts.app')
@section('title', 'Trang chủ - Trải nghiệm nghỉ dưỡng đẳng cấp')

@section('content')

<div class="hero">
    <img src="{{ asset('uploads/home/home.png') }}" 
         alt="Luxury Hotel" class="hero-bg">
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <span class="sub-title" style="color: var(--primary-gold);">Chào mừng đến với Luxury Stay</span>
        <h1 class="hero-heading">
            Nơi Đẳng Cấp Giao Thoa <br> Cùng Sự Bình Yên
        </h1>
        <p class="hero-desc">
            Tận hưởng kỳ nghỉ trong mơ với hệ thống phòng suite sang trọng và dịch vụ 5 sao chuẩn quốc tế tại trung tâm thành phố.
        </p>
        
        <a href="#rooms" class="scroll-down">
            <i class="fa-solid fa-chevron-down"></i>
        </a>
    </div>
</div>

<section id="rooms" class="section-rooms">
    <div class="container">
        <div class="section-header">
            <span class="sub-title">Không gian nghỉ dưỡng</span>
            <h2 class="main-title">Hạng phòng nổi bật</h2>
        </div>

        <div class="room-grid">
            @if(isset($loaiPhongs) && $loaiPhongs->count() > 0)
                @foreach($loaiPhongs as $phong)
                <div class="room-card group">
                    <div class="room-img-wrap">
                        <img src="{{ $phong->hinh_anh ? asset($phong->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                            alt="{{ $phong->ten_loai_phong }}"
                            class="w-full h-full object-cover">
                        
                        <div class="room-price-tag">
                            <span class="room-price-amount">{{ number_format($phong->gia, 0, ',', '.') }}đ</span>
                            <span class="room-price-unit">/đêm</span>
                        </div>
                        
                        <div class="room-overlay-hover"></div>
                    </div>
                    
                    <div class="room-body">
                        <h3 class="room-name">
                            <a href="{{ route('phong.chi-tiet', $phong->id) }}" class="room-title-link">
                                {{ $phong->ten_loai_phong }}
                            </a>
                        </h3>
                        
                        <div class="room-specs">
                            <div class="spec-item" title="Sức chứa">
                                <i class="fa-solid fa-user-group"></i> 
                                <span>{{ $phong->so_nguoi }} Khách</span> </div>
                            <div class="spec-item" title="Diện tích">
                                <i class="fa-solid fa-ruler-combined"></i> 
                                <span>{{ $phong->dien_tich ? $phong->dien_tich.'m²' : '-- m²' }}</span>
                            </div>
                        </div>

                        <p class="room-short-desc">
                            {{ $phong->mo_ta ?? 'Trải nghiệm không gian sang trọng với đầy đủ tiện nghi hiện đại.' }}
                        </p>
                        
                        <a href="{{ route('phong.chi-tiet', $phong->id) }}" class="btn-card-action group/btn">
                            <span>Chi tiết & Đặt phòng</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <p style="text-align: center; color: #999; grid-column: 1/-1; padding: 2rem;">
                    Đang cập nhật danh sách phòng...
                </p>
            @endif
        </div>
        
        <div class="room-btn-wrapper">
            <a href="{{ route('phong.danh-sach') }}" class="btn btn-outline">
                Xem tất cả phòng
            </a>
        </div>
    </div>
</section>

<section class="section-services">
    <div class="service-bg-icon">
        <i class="fa-solid fa-crown"></i>
    </div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="section-header">
            <span class="sub-title">Tiện ích & Dịch vụ</span>
            <h2 class="main-title light">Tận hưởng kỳ nghỉ trọn vẹn</h2>
        </div>

        <div class="service-grid">
            <div class="service-item">
                <div class="service-icon"><i class="fa-solid fa-utensils"></i></div>
                <h3 class="service-title">Nhà hàng 5 sao</h3>
                <p class="service-text">Thưởng thức ẩm thực Á - Âu.</p>
            </div>
            <div class="service-item">
                <div class="service-icon"><i class="fa-solid fa-person-swimming"></i></div>
                <h3 class="service-title">Hồ bơi vô cực</h3>
                <p class="service-text">Tầm nhìn toàn cảnh thành phố.</p>
            </div>
            <div class="service-item">
                <div class="service-icon"><i class="fa-solid fa-spa"></i></div>
                <h3 class="service-title">Luxury Spa</h3>
                <p class="service-text">Liệu trình thư giãn cao cấp.</p>
            </div>
            <div class="service-item">
                <div class="service-icon"><i class="fa-solid fa-martini-glass-citrus"></i></div>
                <h3 class="service-title">Sky Bar</h3>
                <p class="service-text">Cocktail và nhạc sống mỗi tối.</p>
            </div>
        </div>
    </div>
</section>

@if(isset($khuyenMais) && $khuyenMais->count() > 0)
<section class="section-promo">
    <div class="container">
        <div class="promo-header-flex">
            <div>
                <span class="sub-title">Ưu đãi đặc biệt</span>
                <h2 class="main-title">Khuyến mãi dành cho bạn</h2>
            </div>
            <a href="{{ route('khuyen-mai') }}" class="btn-text">
                Xem tất cả <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="promo-grid">
            @foreach($khuyenMais as $km)
            <div class="promo-card">
                <div class="promo-badge">HOT</div>
                
                <div class="promo-icon">
                    <i class="fa-solid fa-gift"></i>
                </div>
                
                <div class="promo-info" style="flex-grow: 1;">
                    <h3>{{ $km->ten_khuyen_mai }}</h3>
                    <p class="promo-desc" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $km->mo_ta }}
                    </p>
                    <div class="promo-meta">
                        <span class="code-box">
                            Mã: <strong>{{ $km->ma_khuyen_mai }}</strong> </span>
                        <span class="discount-val">
                            @if($km->chiet_khau_phan_tram > 0) -{{ $km->chiet_khau_phan_tram }}%
                            @else
                                -{{ number_format($km->so_tien_giam_gia) }}đ
                            @endif
                        </span>
                    </div>
                </div>
                
                <div>
                    <button onclick="navigator.clipboard.writeText('{{ $km->ma_khuyen_mai }}'); alert('Đã sao chép mã!')" class="copy-btn">
                        Sao chép
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section-testimonial">
    <div class="container">
        <div class="section-header">
            <h2 class="main-title">Khách hàng nói gì về chúng tôi?</h2>
        </div>

        <div class="testimonial-grid">
            <div class="testimonial-card">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                <p class="testimonial-text">"Một trải nghiệm tuyệt vời! Phòng ốc sạch sẽ, nhân viên cực kỳ thân thiện và chuyên nghiệp."</p>
                <div class="user-profile">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="user-avatar">
                    <div>
                        <h4 class="user-name">Nguyễn Thu Hà</h4>
                        <div class="star-rating">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                <p class="testimonial-text">"Vị trí khách sạn rất thuận tiện. Đồ ăn sáng ngon và đa dạng. Hồ bơi view đẹp nhất khu vực."</p>
                <div class="user-profile">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="user-avatar">
                    <div>
                        <h4 class="user-name">Trần Minh Tuấn</h4>
                        <div class="star-rating">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                <p class="testimonial-text">"Dịch vụ đẳng cấp 5 sao thực sự. Tôi rất ấn tượng với cách bài trí nội thất trong phòng Suite."</p>
                <div class="user-profile">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User" class="user-avatar">
                    <div>
                        <h4 class="user-name">Sarah Johnson</h4>
                        <div class="star-rating">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-cta">
    <div class="cta-content">
        <h2 class="cta-title">Sẵn sàng cho kỳ nghỉ trong mơ?</h2>
        <p class="cta-text">Đặt phòng trực tiếp trên website để nhận ưu đãi tốt nhất và tích điểm thành viên.</p>
        <a href="{{ route('phong.danh-sach') }}" class="btn btn-primary" style="background-color: var(--primary-dark);">
            ĐẶT PHÒNG NGAY
        </a>
    </div>
</section>

@endsection