@extends('layouts.app')
@section('title', 'Ưu đãi & Khuyến mãi - Luxury Stay')

<!-- Gọi CSS & JS -->
@vite(['resources/css/client/home.css', 'resources/css/client/promo.css', 'resources/js/client/promo.js'])

@section('content')

<!-- 1. HERO BANNER -->
<div class="promo-hero">
    <!-- Ảnh nền promo (Bạn nên thay bằng ảnh thật) -->
    <img src="{{ asset('uploads/home/home.png') }}" alt="Luxury Promo" class="promo-hero-bg">
    <div class="banner-overlay"></div> <!-- Dùng chung từ home.css -->
    
    <div class="promo-hero-content">
        <span class="promo-subtitle">Ưu đãi độc quyền</span>
        <h1 class="promo-title">Tận hưởng kỳ nghỉ <br> Với giá tốt nhất</h1>
    </div>
</div>

<!-- 2. PROMO LIST -->
<section class="section-promo-list">
    <div class="container">
        
        @if(isset($khuyenMais) && $khuyenMais->count() > 0)
            <div class="promo-grid">
                @foreach($khuyenMais as $km)
                    <!-- Kiểm tra hạn sử dụng -->
                    @php
                        $isExpired = \Carbon\Carbon::now()->gt($km->ngay_ket_thuc);
                    @endphp

                    <div class="voucher-card {{ $isExpired ? 'expired' : '' }}">
                        <div class="voucher-img-wrapper">
                            <!-- Ảnh giả lập hoặc thật -->
                            <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                                 alt="Voucher" class="voucher-img">
                            
                            <div class="discount-tag">
                                @if($km->chiet_khau_phan_tram > 0)
                                    -{{ $km->chiet_khau_phan_tram }}%
                                @else
                                    -{{ number_format($km->so_tien_giam_gia/1000) }}K
                                @endif
                            </div>
                        </div>

                        <div class="voucher-body">
                            <h3 class="voucher-title">{{ $km->ten_khuyen_mai }}</h3>
                            <p class="voucher-desc">{{ $km->mo_ta }}</p>
                            
                            <div class="text-sm text-gray-500 mb-4">
                                <i class="fa-regular fa-clock mr-1"></i> Hạn dùng: {{ \Carbon\Carbon::parse($km->ngay_ket_thuc)->format('d/m/Y') }}
                            </div>

                            <div class="voucher-footer">
                                <span class="code-display">{{ $km->ma_khuyen_mai }}</span>
                                
                                @if(!$isExpired)
                                    <button class="btn-copy" data-code="{{ $km->ma_khuyen_mai }}">
                                        <i class="fa-regular fa-copy"></i> Sao chép
                                    </button>
                                @else
                                    <span class="text-red-500 font-bold text-sm">Hết hạn</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Phân trang nếu có -->
            @if(method_exists($khuyenMais, 'links'))
                <div class="mt-12 flex justify-center">
                    {{ $khuyenMais->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-block p-6 rounded-full bg-gray-800 mb-4">
                    <i class="fa-solid fa-ticket text-4xl text-gray-600"></i>
                </div>
                <h3 class="text-xl text-white font-bold mb-2">Chưa có khuyến mãi nào</h3>
                <p class="text-gray-400">Hiện tại chúng tôi chưa có chương trình ưu đãi mới. Vui lòng quay lại sau.</p>
                <a href="{{ route('trang_chu') }}" class="btn btn-outline mt-6">Về trang chủ</a>
            </div>
        @endif

    </div>
</section>

<!-- 3. CTA (Tái sử dụng từ home) -->
<section class="section-cta" style="margin-top: 0;">
    <div class="cta-content">
        <h2 class="cta-title">Đừng bỏ lỡ cơ hội</h2>
        <p class="cta-text">Đăng ký nhận bản tin để không bỏ lỡ các ưu đãi hấp dẫn nhất.</p>
        
        <!-- Form đăng ký nhận tin (Giả lập) -->
        <form class="max-w-md mx-auto flex gap-2 mt-6">
            <input type="email" placeholder="Email của bạn..." class="form-control" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
            <button type="button" class="btn btn-primary" onclick="alert('Cảm ơn bạn đã đăng ký!')">Đăng ký</button>
        </form>
    </div>
</section>

@endsection