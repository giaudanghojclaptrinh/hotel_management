@extends('layouts.app')
@section('title', 'Đặt phòng thành công')

@push('styles')
    @vite(['resources/css/client/booking.css'])
@endpush

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
        
        @php
            // Ưu tiên lấy từ biến $booking được truyền sang
            // Nếu không có (F5 mất biến), thử lấy từ session
            $currentBookingId = null;
            if(isset($booking) && $booking) {
                $currentBookingId = $booking->id;
            } elseif(session('booking_id')) {
                $currentBookingId = session('booking_id');
            }
        @endphp

        <!-- Mã đơn -->
        <div class="order-code-box">
            <span class="code-label">Mã đơn đặt phòng</span>
            <div class="code-value">#BK-{{ $currentBookingId ?? 'N/A' }}</div>
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
            @if($currentBookingId)
                {{-- Nút xem Hóa đơn (Chỉ hiện khi có ID) --}}
                <a href="{{ route('bookings.invoice', ['id' => $currentBookingId]) }}" 
                   class="btn btn-primary" 
                   style="color: #000; width: 100%; box-sizing: border-box; text-decoration: none; display: flex; justify-content: center; align-items: center;">
                    <i class="fa-solid fa-file-invoice-dollar" style="margin-right: 8px;"></i> Xem chi tiết hóa đơn
                </a>
            @endif

            {{-- Nút xem Lịch sử (Luôn hiện để backup) --}}
            <a href="{{ route('bookings.history') }}" 
               class="btn btn-view-order" 
               style="margin-top: 10px; display: flex; justify-content: center; align-items: center;">
               <i class="fa-solid fa-clock-rotate-left" style="margin-right: 8px;"></i> Xem lịch sử đặt phòng
            </a>
            
            <a href="{{ route('trang_chu') }}" class="btn-view-order" style="margin-top: 10px;">
                Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection