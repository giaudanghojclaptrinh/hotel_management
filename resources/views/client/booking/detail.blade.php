@extends('layouts.app')
@section('title', 'Chi tiết đặt phòng')

@vite(['resources/css/client/booking-detail.css'])

@section('content')
<div class="booking-detail-wrapper">
    <div class="container">
        
        <!-- Breadcrumb & Back -->
        <div class="page-nav">
            <a href="{{ route('bookings.history') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <!-- Main Content -->
        <div class="detail-layout">
            
            <!-- Left Column: Booking Info -->
            <div class="detail-main">
                
                <!-- Header Card -->
                <div class="info-card header-card">
                    <div class="header-top">
                        <div>
                            <h1 class="booking-code">#BK-{{ $booking->id }}</h1>
                            <p class="booking-created">Đặt ngày {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @php
                            $statusMap = [
                                'pending'   => ['class' => 'status-pending', 'label' => 'Chờ duyệt', 'icon' => 'fa-clock'],
                                'confirmed' => ['class' => 'status-confirmed', 'label' => 'Đã xác nhận', 'icon' => 'fa-check-circle'],
                                'completed' => ['class' => 'status-completed', 'label' => 'Hoàn thành', 'icon' => 'fa-star'],
                                'cancelled' => ['class' => 'status-cancelled', 'label' => 'Đã hủy', 'icon' => 'fa-circle-xmark'],
                            ];
                            
                            $statusKey = $booking->trang_thai ?? 'pending';
                            $st = $statusMap[$statusKey] ?? $statusMap['pending'];
                        @endphp
                        
                        <span class="status-badge {{ $st['class'] }}">
                            <i class="fa-solid {{ $st['icon'] }}"></i> {{ $st['label'] }}
                        </span>
                    </div>
                    
                    @if($booking->trang_thai == 'cancelled' && $booking->cancel_reason)
                        <div class="cancel-reason">
                            <i class="fa-solid fa-info-circle"></i>
                            <div>
                                <strong>Lý do hủy:</strong> {{ $booking->cancel_reason }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Room Info -->
                @foreach($booking->chiTietDatPhongs as $detail)
                <div class="info-card room-card">
                    <div class="room-layout">
                        <img src="{{ $detail->loaiPhong->hinh_anh ? asset($detail->loaiPhong->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                             alt="{{ $detail->loaiPhong->ten_loai_phong }}" class="room-image">
                        
                        <div class="room-content">
                            <h2 class="room-title">{{ $detail->loaiPhong->ten_loai_phong }}</h2>
                            
                            <div class="room-specs">
                                <div class="spec-item">
                                    <i class="fa-solid fa-user-group"></i>
                                    <span>{{ $detail->loaiPhong->so_nguoi }} Khách</span>
                                </div>
                                <div class="spec-item">
                                    <i class="fa-solid fa-ruler-combined"></i>
                                    <span>{{ $detail->loaiPhong->dien_tich }}m²</span>
                                </div>
                                @if($detail->phong)
                                    <div class="spec-item">
                                        <i class="fa-solid fa-door-open"></i>
                                        <span>Phòng {{ $detail->phong->so_phong }}</span>
                                    </div>
                                @else
                                    <div class="spec-item" style="color: var(--text-muted);">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                        <span>Đang xếp phòng</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="room-price-info">
                                <div>
                                    <span class="price-label">Đơn giá</span>
                                    <span class="price-value">{{ number_format($detail->don_gia, 0, ',', '.') }}đ/đêm</span>
                                </div>
                                <div>
                                    <span class="price-label">Thành tiền</span>
                                    <span class="price-value highlight">{{ number_format($detail->thanh_tien, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Guest Info -->
                <div class="info-card">
                    <h3 class="card-title">
                        <i class="fa-solid fa-user"></i> Thông tin khách hàng
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Họ tên:</span>
                            <span class="info-value">{{ $booking->user->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $booking->user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Số điện thoại:</span>
                            <span class="info-value">{{ $booking->user->phone ?? 'Chưa cập nhật' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($booking->ghi_chu)
                <div class="info-card">
                    <h3 class="card-title">
                        <i class="fa-solid fa-note-sticky"></i> Ghi chú đặc biệt
                    </h3>
                    <p class="note-content">{{ $booking->ghi_chu }}</p>
                </div>
                @endif

                <!-- Help Info -->
                <div class="info-card help-card">
                    <h3 class="card-title">
                        <i class="fa-solid fa-headset"></i> Cần hỗ trợ?
                    </h3>
                    <p class="help-text">Liên hệ với chúng tôi để được tư vấn và hỗ trợ tốt nhất</p>
                    <div class="contact-info">
                        <a href="tel:0792008096" class="contact-item">
                            <i class="fa-solid fa-phone"></i>
                            <div class="contact-content">
                                <span class="contact-label">Hotline</span>
                                <span class="contact-value">0792008096</span>
                            </div>
                        </a>
                        <a href="mailto:giaudeptrainhat@gmail.com" class="contact-item">
                            <i class="fa-solid fa-envelope"></i>
                            <div class="contact-content">
                                <span class="contact-label">Email</span>
                                <span class="contact-value">giaudeptrainhat@gmail.com</span>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right Column: Summary -->
            <div class="detail-sidebar">
                
                <!-- Check-in/out Info -->
                <div class="info-card sticky-card">
                    <h3 class="card-title">
                        <i class="fa-solid fa-calendar-days"></i> Thông tin lưu trú
                    </h3>
                    
                    <div class="date-range">
                        <div class="date-item">
                            <div class="date-icon checkin">
                                <i class="fa-solid fa-calendar-plus"></i>
                            </div>
                            <div>
                                <span class="date-label">Nhận phòng</span>
                                <span class="date-value">{{ \Carbon\Carbon::parse($booking->ngay_den)->format('d/m/Y') }}</span>
                                <span class="date-time">14:00</span>
                            </div>
                        </div>
                        
                        <div class="date-separator">
                            <div class="night-badge">
                                <i class="fa-solid fa-moon"></i>
                                {{ \Carbon\Carbon::parse($booking->ngay_den)->diffInDays($booking->ngay_di) }} đêm
                            </div>
                        </div>
                        
                        <div class="date-item">
                            <div class="date-icon checkout">
                                <i class="fa-solid fa-calendar-minus"></i>
                            </div>
                            <div>
                                <span class="date-label">Trả phòng</span>
                                <span class="date-value">{{ \Carbon\Carbon::parse($booking->ngay_di)->format('d/m/Y') }}</span>
                                <span class="date-time">12:00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="payment-info">
                        <div class="payment-method">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>{{ $booking->payment_method == 'online' ? 'Thanh toán Online (VNPay)' : 'Thanh toán tại khách sạn' }}</span>
                        </div>
                        
                        @if($booking->payment_status == 'paid')
                            <div class="payment-status paid">
                                <i class="fa-solid fa-check-circle"></i> Đã thanh toán
                            </div>
                        @elseif($booking->payment_status == 'awaiting_payment')
                            <div class="payment-status awaiting">
                                <i class="fa-solid fa-hourglass-half"></i> Chờ thanh toán
                            </div>
                        @else
                            <div class="payment-status unpaid">
                                <i class="fa-regular fa-circle"></i> Chưa thanh toán
                            </div>
                        @endif
                    </div>

                    <!-- Price Summary -->
                    <div class="price-summary">
                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <span>{{ number_format($booking->chiTietDatPhongs->sum('thanh_tien'), 0, ',', '.') }}đ</span>
                        </div>
                        
                        @if($booking->discount_amount > 0)
                        <div class="summary-row discount">
                            <span>Giảm giá ({{ $booking->promotion_code }})</span>
                            <span>-{{ number_format($booking->discount_amount, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                        
                        <div class="summary-row">
                            <span>Sau giảm giá</span>
                            <span>{{ number_format($booking->subtotal ?? ($booking->chiTietDatPhongs->sum('thanh_tien') - $booking->discount_amount), 0, ',', '.') }}đ</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>VAT (8%)</span>
                            <span>{{ number_format($booking->vat_amount ?? 0, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <div class="summary-divider"></div>
                        
                        <div class="summary-total">
                            <span>Tổng thanh toán</span>
                            <span>{{ number_format($booking->tong_tien, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="action-buttons">
                        <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn-primary">
                            <i class="fa-solid fa-receipt"></i> Xem hóa đơn
                        </a>
                        
                        @if(in_array($booking->trang_thai, ['pending', 'confirmed']) && $booking->payment_status != 'paid')
                            <button type="button" onclick="confirmCancel({{ $booking->id }})" class="btn-danger">
                                <i class="fa-solid fa-xmark"></i> Hủy đơn
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận hủy đơn -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Xác nhận hủy đơn đặt phòng</h3>
            <button type="button" class="modal-close" onclick="closeCancelModal()">&times;</button>
        </div>
        <form id="cancelForm" method="POST">
            @csrf
            <div class="modal-body">
                <p class="warning-text">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    Bạn có chắc chắn muốn hủy đơn đặt phòng này?
                </p>
                <div class="form-group">
                    <label for="cancel_reason">Lý do hủy đơn <span class="required">*</span></label>
                    <textarea id="cancel_reason" name="cancel_reason" rows="4" required 
                              placeholder="Vui lòng cho chúng tôi biết lý do hủy đơn..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeCancelModal()">Đóng</button>
                <button type="submit" class="btn-danger">Xác nhận hủy</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmCancel(bookingId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    form.action = `/dat-phong/huy/${bookingId}`;
    modal.style.display = 'flex';
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.style.display = 'none';
    document.getElementById('cancel_reason').value = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('cancelModal');
    if (event.target == modal) {
        closeCancelModal();
    }
}
</script>
@endsection
