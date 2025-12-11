@extends('layouts.app')
@section('title', 'Đặt phòng thành công')


@section('content')
<div class="success-wrapper">
    <div class="success-card">
        
        <!-- Trạng thái -->
        @if(session('error'))
            <div class="success-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3);">
                <i class="fa-solid fa-xmark" style="color: #ef4444;"></i>
            </div>
            <h2 class="success-title" style="color: #ef4444;">Thất bại!</h2>
            <p class="success-msg">{{ session('error') }}</p>
        @else
            <div class="success-icon-wrapper">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 class="success-title">Đặt phòng thành công!</h2>
            <p class="success-msg">{{ session('success') ?? 'Cảm ơn bạn đã lựa chọn Luxury Stay.' }}</p>
        @endif
        
        <!-- Mã đơn -->
        <div class="order-code-box">
            <span class="code-label">Mã đơn đặt phòng</span>
            <div class="code-value">#BK-{{ session('booking_id') ?? 'N/A' }}</div>
        </div>

        <p class="text-muted" style="margin-bottom: 2rem; font-size: 0.9rem;">
            @if(session('error'))
                Vui lòng thử lại hoặc liên hệ hotline để được hỗ trợ.
            @else
                Thông tin chi tiết đã được gửi đến email của bạn. Chúng tôi sẽ liên hệ sớm nhất để xác nhận.
            @endif
        </p>

        <!-- Buttons -->
        <div class="btn-group-vertical">
            @if (Route::has('bookings.history'))
                <a href="{{ route('bookings.history') }}" class="btn btn-primary" style="color: #000; width: 100%; box-sizing: border-box; text-decoration: none;">
                    Xem chi tiết đơn hàng
                </a>
            @endif
            
            <a href="{{ route('trang_chu') }}" class="btn-view-order">
                Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection