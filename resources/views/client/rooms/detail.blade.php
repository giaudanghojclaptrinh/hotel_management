@extends('layouts.app')
@section('title', $room->ten_loai_phong)

@section('content')

<!-- Container chính cho trang chi tiết -->
<div class="room-detail-container">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('trang_chu') }}">Trang chủ</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('phong.danh-sach') }}">Phòng nghỉ</a>
            <span class="text-gray-300">/</span>
            <span class="active">{{ $room->ten_loai_phong }}</span>
        </nav>

        <!-- Grid Layout (Chia cột trái/phải bằng CSS rooms.css) -->
        <div class="detail-grid">
            
            <!-- CỘT TRÁI: Hình ảnh & Thông tin -->
            <div class="left-column">
                
                <!-- 1. Ảnh lớn & Tiêu đề (Gallery Main) -->
                <div class="room-gallery-main">
                    <img src="{{ $room->hinh_anh ? asset($room->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                         alt="{{ $room->ten_loai_phong }}">
                    
                    <div class="room-gallery-overlay"></div>
                    
                    <div class="room-title-overlay">
                        <h1 class="room-detail-title">{{ $room->ten_loai_phong }}</h1>
                        <div class="room-meta-flex">
                            <span><i class="fa-solid fa-user-group"></i> {{ $room->so_nguoi }} Khách</span>
                            <span><i class="fa-solid fa-ruler-combined"></i> {{ $room->dien_tich ?? '--' }} m²</span>
                            <span><i class="fa-solid fa-bed"></i> 1 Giường King</span>
                        </div>
                    </div>
                </div>
                
                <!-- 2. Mô tả phòng -->
                <div class="room-desc-box">
                    <h3 class="room-desc-title">Mô tả phòng</h3>
                    <div class="desc-content">
                        <p class="mb-4">{{ $room->mo_ta ?? 'Chưa có mô tả chi tiết cho hạng phòng này.' }}</p>
                        <p>Được thiết kế với phong cách hiện đại pha lẫn nét cổ điển, phòng {{ $room->ten_loai_phong }} mang đến không gian nghỉ dưỡng lý tưởng. Nội thất cao cấp, ánh sáng tự nhiên và các tiện ích công nghệ cao sẽ làm hài lòng những vị khách khó tính nhất.</p>
                    </div>
                </div>

                <!-- 3. Tiện nghi (Amenities Box) -->
                <div class="amenities-box">
                    <h3 class="room-desc-title" style="margin-bottom: 2rem;">Tiện nghi cao cấp</h3>
                    
                    @if($room->tienNghis && $room->tienNghis->count() > 0)
                        <div class="amenities-grid">
                            @foreach($room->tienNghis as $tn)
                                <div class="amenity-item">
                                    <div class="amenity-icon-box">
                                        <i class="{{ $tn->icon ?? 'fa-solid fa-check' }}"></i>
                                    </div>
                                    <span>{{ $tn->ten_tien_nghi }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted italic">Đang cập nhật danh sách tiện nghi...</p>
                    @endif
                </div>
            </div>

            <!-- CỘT PHẢI: Form Đặt phòng (Sticky Sidebar) -->
            <div class="right-column">
                <div class="booking-sidebar-card">
                    <div class="booking-price-header">
                        <div>
                            <span class="price-label">Giá tốt nhất</span>
                            <div class="flex items-baseline gap-1">
                                <span class="price-large">{{ number_format($room->gia, 0, ',', '.') }}đ</span>
                                <span class="price-unit">/đêm</span>
                            </div>
                        </div>
                        <div class="badge-breakfast">
                            <i class="fa-solid fa-mug-hot"></i> Ăn sáng
                        </div>
                    </div>

                    <!-- FORM KIỂM TRA & ĐẶT -->
                    <!-- Form gửi lại chính trang này với tham số ngày mới -->
                    <form action="{{ route('phong.chi-tiet', $room->id) }}" method="GET" id="booking-date-form">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <!-- Check-in -->
                        <div class="booking-form-group">
                            <label class="booking-label">Ngày nhận phòng</label>
                            <div class="date-input-wrapper">
                                <i class="fa-regular fa-calendar"></i>
                                <input type="date" 
                                       id="checkin_date" 
                                       name="checkin" 
                                       value="{{ request('checkin') }}" 
                                       onchange="document.getElementById('booking-date-form').submit()"
                                       required 
                                       min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Check-out -->
                        <div class="booking-form-group">
                            <label class="booking-label">Ngày trả phòng</label>
                            <div class="date-input-wrapper">
                                <i class="fa-regular fa-calendar-check"></i>
                                <input type="date" 
                                       id="checkout_date" 
                                       name="checkout" 
                                       value="{{ request('checkout') }}" 
                                       onchange="document.getElementById('booking-date-form').submit()"
                                       required 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                        </div>

                        <!-- [MỚI] HIỂN THỊ TRẠNG THÁI PHÒNG TRỐNG -->
                        @if(request('checkin') && request('checkout'))
                            {{-- Trạng thái hiển thị khi ngày đã được chọn --}}
                            @if($phongTrong > 0)
                                <div class="status-box status-available">
                                    <i class="fa-solid fa-circle-check text-lg"></i>
                                    <div>
                                        <span class="font-bold block">Còn {{ $phongTrong }} phòng trống!</span>
                                        <span class="text-xs">Đã kiểm tra lịch từ {{ \Carbon\Carbon::parse(request('checkin'))->format('d/m') }} - {{ \Carbon\Carbon::parse(request('checkout'))->format('d/m') }}.</span>
                                    </div>
                                </div>
                                <button type="submit" 
                                        formaction="{{ route('booking.create') }}"
                                        formmethod="GET"
                                        class="btn-book-now">
                                    <span>ĐẶT PHÒNG NGAY</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            @else
                                <div class="status-box status-soldout">
                                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                                    <div>
                                        <span class="font-bold block">Hết phòng!</span>
                                        <span class="text-xs">Không còn phòng trống trong khoảng thời gian này.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-book-now btn-disabled" disabled>
                                    TẠM HẾT PHÒNG
                                </button>
                            @endif
                        @else
                            {{-- Trạng thái hiển thị khi chưa chọn ngày --}}
                            <div class="status-box status-available">
                                <i class="fa-solid fa-calendar-alt text-lg"></i>
                                <div>
                                    <span class="font-bold block">Chọn ngày để kiểm tra</span>
                                    <span class="text-xs">Tổng số phòng loại này: {{ $room->phongs->where('tinh_trang', '!=', 'maintenance')->count() }} phòng.</span>
                                </div>
                            </div>
                             <button type="submit" 
                                    formaction="{{ route('booking.create') }}"
                                    formmethod="GET"
                                    class="btn-book-now btn-disabled" 
                                    disabled>
                                <span>ĐẶT PHÒNG NGAY</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        @endif
                        
                        <p class="booking-note">
                            * Bạn sẽ được yêu cầu đăng nhập để hoàn tất đặt phòng.
                        </p>
                    </form>
                </div>
            </div>
            <!-- END CỘT PHẢI -->

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkinInput = document.getElementById('checkin_date');
        const checkoutInput = document.getElementById('checkout_date');
        const bookNowBtn = document.querySelector('.btn-book-now');
        const form = document.getElementById('booking-date-form');

        // Hàm cập nhật min date cho Checkout
        const updateCheckoutMinDate = (checkinVal) => {
            if (checkinVal) {
                const checkinDate = new Date(checkinVal);
                checkinDate.setDate(checkinDate.getDate() + 1);
                
                const minCheckout = checkinDate.toISOString().split('T')[0];
                checkoutInput.min = minCheckout;
                
                if (checkoutInput.value && checkoutInput.value <= checkinVal) {
                    checkoutInput.value = minCheckout;
                }
            }
        };

        // 1. Logic tự động submit và cập nhật min date
        checkinInput.addEventListener('change', function() {
            updateCheckoutMinDate(this.value);
            // Submit form để chạy lại logic đếm phòng trống trong Controller
            if (this.value && checkoutInput.value) {
                 form.submit();
            }
        });

        checkoutInput.addEventListener('change', function() {
            // Submit form để chạy lại logic đếm phòng trống trong Controller
            if (this.value && checkinInput.value) {
                 form.submit();
            }
        });

        // Khởi tạo min date khi trang tải (trường hợp khách quay lại trang)
        updateCheckoutMinDate(checkinInput.value);

        // 2. Chặn submit nếu thiếu ngày (để nút "ĐẶT PHÒNG NGAY" chỉ hoạt động khi có ngày)
        form.addEventListener('submit', function(e) {
            const checkin = checkinInput.value;
            const checkout = checkoutInput.value;

            if (!checkin || !checkout || new Date(checkin) >= new Date(checkout)) {
                e.preventDefault();
                alert('Vui lòng chọn ngày hợp lệ: Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 ngày.');
                return;
            }
        });
        
        // 3. Tự động kích hoạt/vô hiệu hóa nút Đặt phòng
        const toggleBookNowButton = () => {
            const checkin = checkinInput.value;
            const checkout = checkoutInput.value;
            const isDateSelected = checkin && checkout && (new Date(checkin) < new Date(checkout));

            if (bookNowBtn) {
                // Nếu khách chưa chọn ngày, nút "ĐẶT PHÒNG NGAY" (loại 'submit') phải bị disabled
                if (!isDateSelected) {
                    bookNowBtn.disabled = true;
                    bookNowBtn.classList.add('btn-disabled');
                } else {
                    bookNowBtn.disabled = false;
                    bookNowBtn.classList.remove('btn-disabled');
                }
            }
        };
    });
</script>
@endpush