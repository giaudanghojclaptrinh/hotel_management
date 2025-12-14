@extends('layouts.invoice')
@section('title', 'Chi tiết hóa đơn')

@push('styles')
    @vite(['resources/css/client/invoice.css'])
@endpush

@push('scripts')
    @vite(['resources/js/client/invoice.js'])
@endpush

@section('content')
<div class="invoice-page-wrapper">
    <!-- Print-only view; no server-side PDF injection -->
    <div class="invoice-container">
        
        <!-- Nút quay lại -->
        <div class="mb-6">
            {{-- Thẻ a đã được xóa logic JS inline và dùng ID để lắng nghe trong invoice.js --}}
            <a href="{{ url()->previous() }}" id="back-to-previous" class="back-link">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>

        <!-- Card Hóa đơn -->
        <div class="invoice-card">
            
            <!-- Header Hóa đơn -->
            <div class="invoice-header">
                <!-- Họa tiết nền mờ -->
                <div class="header-pattern"></div>
                
                <h1 class="header-title">HÓA ĐƠN DỊCH VỤ</h1>
                <p class="header-subtitle">Luxury Stay Hotel & Resort</p>
                
                <!-- Mã hóa đơn -->
                <div class="invoice-code">
                    #{{ $booking->hoaDon ? $booking->hoaDon->ma_hoa_don : 'TMP-'.$booking->id }}
                </div>
            </div>

            <div class="invoice-body">
                @if(isset($error))
                    <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 text-sm">
                        {{ $error }}
                    </div>
                @endif
                <!-- Thông tin khách hàng & Đơn hàng -->
                <div class="detail-grid">
                    <div>
                        <h4 class="detail-heading">Khách hàng</h4>
                        <p class="customer-name">{{ $booking->user->name }}</p>
                        <p class="info-item"><i class="fa-regular fa-envelope"></i> {{ $booking->user->email }}</p>
                        <p class="info-item"><i class="fa-solid fa-phone"></i> {{ $booking->user->phone ?? 'Chưa cập nhật SĐT' }}</p>
                    </div>
                    <div class="booking-info">
                        <h4 class="detail-heading">Chi tiết đặt phòng</h4>
                        <p>
                            <strong>Ngày đặt:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p>
                            <strong>Nhận phòng:</strong> {{ \Carbon\Carbon::parse($booking->ngay_den)->format('d/m/Y') }}
                        </p>
                        <p class="mb-2">
                            <strong>Trả phòng:</strong> {{ \Carbon\Carbon::parse($booking->ngay_di)->format('d/m/Y') }}
                        </p>
                        <div class="payment-method-tag">
                            <i class="fa-solid fa-credit-card mr-1.5"></i>
                            {{ $booking->payment_method == 'online' ? 'Thanh toán Online (VNPay)' : 'Thanh toán tại khách sạn' }}
                        </div>
                    </div>
                </div>

                <!-- Bảng chi tiết dịch vụ -->
                <div class="service-table-wrapper">
                    <h4 class="detail-heading">Dịch vụ sử dụng</h4>
                    <div class="table-responsive">
                        <table class="invoice-table">
                            <thead>
                                <tr>
                                    <th>Hạng phòng</th>
                                    <th class="text-center">Số đêm</th>
                                    <th class="text-right">Đơn giá</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->chiTietDatPhongs as $detail)
                                <tr>
                                    <td class="font-medium">
                                        {{ $detail->loaiPhong->ten_loai_phong }}
                                        @if($detail->phong)
                                            <div class="room-detail-info">
                                                <i class="fa-solid fa-door-open mr-1"></i> Phòng {{ $detail->phong->so_phong }}
                                            </div>
                                        @else
                                            <div class="room-detail-pending">
                                                <i class="fa-solid fa-hourglass-half mr-1"></i> Đang xếp phòng
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($booking->ngay_den)->diffInDays($booking->ngay_di) }}</td>
                                    <td class="text-right">{{ number_format($detail->don_gia, 0, ',', '.') }}đ</td>
                                    <td class="text-right table-amount">{{ number_format($detail->thanh_tien, 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tổng kết tài chính -->
                <div class="summary-container">
                    <div class="summary-box">
                        
                        <div class="summary-row">
                            <span>Tạm tính (trước giảm giá)</span>
                            <span class="font-medium">{{ number_format($booking->chiTietDatPhongs->sum('thanh_tien'), 0, ',', '.') }}đ</span>
                        </div>
                        
                        @if($booking->discount_amount > 0)
                        <div class="summary-row summary-discount">
                            <span>Giảm giá ({{ $booking->promotion_code }})</span>
                            <span>-{{ number_format($booking->discount_amount, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                        
                        <div class="summary-row">
                            <span>Tạm tính (sau giảm giá)</span>
                            <span class="font-medium">{{ number_format($booking->subtotal ?? ($booking->chiTietDatPhongs->sum('thanh_tien') - $booking->discount_amount), 0, ',', '.') }}đ</span>
                        </div>
                        
                        <div class="summary-row" style="color: #10b981;">
                            <span>Thuế VAT (8%)</span>
                            <span class="font-medium">{{ number_format($booking->vat_amount ?? 0, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <div class="summary-separator"></div>
                        
                        <div class="summary-total">
                            <span class="total-label">Tổng thanh toán</span>
                            <span class="total-amount">{{ number_format($booking->tong_tien, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <!-- Trạng thái thanh toán (Con dấu) -->
                        <div class="status-stamp-wrapper">
                            @if($booking->payment_status == 'paid')
                                <div class="status-stamp status-paid">
                                    ĐÃ THANH TOÁN
                                </div>
                            @elseif($booking->payment_status == 'awaiting_payment')
                                <div class="status-stamp status-awaiting">
                                    CHỜ THANH TOÁN
                                </div>
                            @else
                                <div class="status-stamp status-unpaid">
                                    CHƯA THANH TOÁN
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Hóa đơn -->
                <div class="invoice-footer">
                    <p class="footer-highlight">Cảm ơn quý khách đã lựa chọn Luxury Stay Hotel & Resort.</p>
                    <p>Mọi thắc mắc xin vui lòng liên hệ hotline: <strong class="footer-highlight">1900 1234</strong> hoặc email: <span class="footer-brand">support@luxurystay.com</span></p>
                    <p class="footer-italic">Hóa đơn điện tử này có giá trị như hóa đơn giấy.</p>
                </div>
            </div>
        </div>

        <!-- Nút In (Ẩn khi in) -->
        <div class="print-button-container print:hidden">
            <div class="inline-flex gap-3">
                {{-- Nút In đã được xóa logic JS inline và dùng ID để lắng nghe trong invoice.js --}}
                <button id="print-invoice-btn" class="print-button">
                    <i class="fa-solid fa-print"></i> In hóa đơn
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 