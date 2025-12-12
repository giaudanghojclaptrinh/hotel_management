@extends('layouts.app')
@section('title', 'Lịch sử đặt phòng')

<!-- Gọi CSS -->
{{-- Ghi chú: `home.css` đã được include trong `layouts.app` để tránh lặp lại;
    trước đây chúng ta gọi cả `home.css` và `history.css` tại đây dẫn tới tải thừa.
    Dòng dưới đây là bản cũ (đã comment) để bạn dễ khôi phục nếu cần: --}}
{{-- @vite(['resources/css/client/home.css', 'resources/css/client/history.css']) --}}
@vite(['resources/css/client/history.css'])

@section('content')
<div class="history-page-wrapper">
    <div class="container">
        
        <!-- Header -->
        <div class="history-header">
            <div>
                <h1 class="history-title">Đơn đặt phòng của tôi</h1>
                <p class="history-desc">Quản lý và xem lại lịch sử các chuyến đi nghỉ dưỡng của bạn.</p>
            </div>
            <a href="{{ route('phong.danh-sach') }}" class="btn-new-booking group">
                <i class="fa-solid fa-plus"></i> Đặt phòng mới
            </a>
        </div>

        @if($bookings->isEmpty())
            <!-- Trạng thái trống -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fa-regular fa-calendar-xmark"></i>
                </div>
                <h3 class="empty-title">Chưa có đơn đặt phòng nào</h3>
                <p class="empty-desc">
                    Bạn chưa thực hiện đặt phòng nào tại hệ thống của chúng tôi. Hãy khám phá ngay những căn phòng tuyệt vời đang chờ đón bạn.
                </p>
                <a href="{{ route('phong.danh-sach') }}" class="btn-cta-primary">
                    Đặt phòng ngay
                </a>
            </div>
        @else
            <!-- Danh sách đơn hàng -->
            <div class="booking-list">
                @foreach($bookings as $booking)
                    @php
                        // Cấu hình trạng thái & Badge màu sắc (CSS Class đã định nghĩa)
                        $statusMap = [
                            'pending'   => ['class' => 'status-pending', 'label' => 'Chờ duyệt', 'icon' => 'fa-clock'],
                            'confirmed' => ['class' => 'status-confirmed', 'label' => 'Đã xác nhận', 'icon' => 'fa-check-circle'],
                            'completed' => ['class' => 'status-completed', 'label' => 'Hoàn thành', 'icon' => 'fa-star'],
                            'cancelled' => ['class' => 'status-cancelled', 'label' => 'Đã hủy', 'icon' => 'fa-circle-xmark'],
                        ];
                        
                        $statusKey = $booking->trang_thai ?? 'pending';
                        $st = $statusMap[$statusKey] ?? $statusMap['pending'];

                        // Lấy thông tin chi tiết (1 đơn 1 phòng)
                        $detail = $booking->chiTietDatPhongs->first();
                        $roomInfo = $detail ? $detail->loaiPhong : null;
                    @endphp

                    <div class="booking-card">
                        
                        <!-- Header Card -->
                        <div class="card-header">
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <span class="booking-id">#BK-{{ $booking->id }}</span>
                                <span class="booking-date">
                                    <i class="fa-regular fa-calendar-check"></i>
                                    Đặt ngày {{ $booking->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                            
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <!-- Badge Trạng thái Đơn -->
                                <span class="status-badge {{ $st['class'] }}">
                                    <i class="fa-solid {{ $st['icon'] }}"></i> {{ $st['label'] }}
                                </span>

                                <!-- Badge Thanh toán -->
                                @if($booking->payment_status == 'paid')
                                    <span class="status-badge status-completed" title="Đã thanh toán">
                                        <i class="fa-solid fa-money-bill-wave"></i> Đã TT
                                    </span>
                                @elseif($booking->payment_status == 'awaiting_payment')
                                    <span class="status-badge status-pending" title="Chờ thanh toán Online">
                                        <i class="fa-solid fa-hourglass-half"></i> Chờ TT
                                    </span>
                                @else
                                    <span class="status-badge" style="background: rgba(255,255,255,0.1); color: var(--text-muted);" title="Thanh toán sau">
                                        <i class="fa-regular fa-circle"></i> Chưa TT
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Body Card -->
                        <div class="card-body">
                            <!-- Ảnh -->
                            @if($roomInfo)
                                <img src="{{ $roomInfo->hinh_anh ? asset($roomInfo->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                                     class="room-thumb" alt="{{ $roomInfo->ten_loai_phong }}">
                            @else
                                <div class="room-thumb" style="display: flex; align-items: center; justify-content: center; background: #333; color: #666;">
                                    <i class="fa-solid fa-image fa-2x"></i>
                                </div>
                            @endif

                            <!-- Thông tin -->
                            <div class="room-info">
                                <h3>{{ $roomInfo ? $roomInfo->ten_loai_phong : 'Thông tin phòng không khả dụng' }}</h3>
                                <ul class="room-detail-list">
                                    <li>
                                        <i class="fa-regular fa-calendar-plus"></i> 
                                        Nhận: {{ \Carbon\Carbon::parse($booking->ngay_den)->format('d/m/Y') }}
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-calendar-minus"></i> 
                                        Trả: {{ \Carbon\Carbon::parse($booking->ngay_di)->format('d/m/Y') }}
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-credit-card"></i> 
                                        {{ $booking->payment_method == 'online' ? 'Thanh toán Online (VNPay)' : 'Thanh toán tại khách sạn' }}
                                    </li>
                                </ul>
                            </div>

                            <!-- Giá & Action -->
                            <div class="card-actions">
                                <div>
                                    <span class="price-label">Tổng thanh toán</span>
                                    <span class="total-price">{{ number_format($booking->tong_tien, 0, ',', '.') }}đ</span>
                                </div>
                                <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn-view-detail">
                                    Xem chi tiết <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

                {{-- Pagination --}}
                @include('partials.pagination', ['paginator' => $bookings])
        @endif
    </div>
</div>
@endsection