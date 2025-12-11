@extends('layouts.app')
@section('title', $room->ten_loai_phong)

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500">
            <a href="{{ route('trang_chu') }}" class="hover:text-brand-gold transition">Trang chủ</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('phong.danh-sach') }}" class="hover:text-brand-gold transition">Phòng nghỉ</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-brand-900 font-medium border-b border-brand-gold pb-0.5">{{ $room->ten_loai_phong }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- CỘT TRÁI: Hình ảnh & Thông tin -->
            <div class="lg:col-span-2">
                
                <!-- ẢNH LỚN -->
                <div class="rounded-2xl overflow-hidden shadow-2xl mb-8 group relative h-[500px]">
                    <img src="{{ $room->hinh_anh ? asset($room->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                         class="w-full h-full object-cover transition duration-700 group-hover:scale-105" 
                         alt="{{ $room->ten_loai_phong }}">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-80"></div>
                    
                    <div class="absolute bottom-8 left-8 text-white">
                        <h1 class="font-serif text-3xl md:text-4xl font-bold mb-3 shadow-sm">{{ $room->ten_loai_phong }}</h1>
                        <div class="flex items-center space-x-4 text-sm font-medium">
                            <span><i class="fa-solid fa-user-group text-brand-gold mr-2"></i> {{ $room->so_nguoi }} Khách</span>
                            <span><i class="fa-solid fa-ruler-combined text-brand-gold mr-2"></i> {{ $room->dien_tich ?? '--' }} m²</span>
                            <span><i class="fa-solid fa-bed text-brand-gold mr-2"></i> 1 Giường King</span>
                        </div>
                    </div>
                </div>
                
                <!-- MÔ TẢ -->
                <div class="prose max-w-none text-gray-600 leading-relaxed mb-10">
                    <h3 class="font-serif text-2xl font-bold text-brand-900 mb-4 border-l-4 border-brand-gold pl-4">Mô tả phòng</h3>
                    <p class="mb-4">{{ $room->mo_ta ?? 'Chưa có mô tả chi tiết cho hạng phòng này.' }}</p>
                    <p>Được thiết kế với phong cách hiện đại pha lẫn nét cổ điển, phòng {{ $room->ten_loai_phong }} mang đến không gian nghỉ dưỡng lý tưởng. Nội thất cao cấp, ánh sáng tự nhiên và các tiện ích công nghệ cao sẽ làm hài lòng những vị khách khó tính nhất.</p>
                </div>

                <!-- TIỆN NGHI -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <h3 class="font-serif text-2xl font-bold text-brand-900 mb-6">Tiện nghi cao cấp</h3>
                    
                    @if($room->tienNghis && $room->tienNghis->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-8">
                            @foreach($room->tienNghis as $tn)
                                <div class="flex items-center text-gray-700 group">
                                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-brand-gold mr-3 group-hover:bg-brand-gold group-hover:text-white transition-colors">
                                        <i class="{{ $tn->icon ?? 'fa-solid fa-check' }}"></i>
                                    </div>
                                    <span class="font-medium">{{ $tn->ten_tien_nghi }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Đang cập nhật danh sách tiện nghi...</p>
                    @endif
                </div>
            </div>

            <!-- CỘT PHẢI: Form Đặt phòng (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] border border-gray-100 sticky top-24">
                    <div class="flex items-end justify-between mb-6 pb-6 border-b border-gray-100">
                        <div>
                            <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Giá tốt nhất</span>
                            <div class="flex items-baseline">
                                <span class="text-3xl font-serif font-bold text-brand-900">{{ number_format($room->gia, 0, ',', '.') }}đ</span>
                                <span class="text-gray-500 ml-1">/đêm</span>
                            </div>
                        </div>
                        <div class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded">
                            <i class="fa-solid fa-check"></i> Có ăn sáng
                        </div>
                    </div>

                    <!-- FORM KIỂM TRA & ĐẶT -->
                    <!-- [FIXED] Thêm ID để xử lý JS -->
                    <form action="{{ route('booking.create') }}" method="GET" id="booking-date-form">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <div class="space-y-5 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Ngày nhận phòng</label>
                                <div class="relative">
                                    <input type="date" id="checkin_date" name="checkin" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700 focus:bg-white focus:ring-2 focus:ring-brand-gold focus:border-transparent transition cursor-pointer" required min="{{ date('Y-m-d') }}">
                                    <i class="fa-regular fa-calendar absolute left-3 top-3.5 text-brand-gold"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Ngày trả phòng</label>
                                <div class="relative">
                                    <input type="date" id="checkout_date" name="checkout" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700 focus:bg-white focus:ring-2 focus:ring-brand-gold focus:border-transparent transition cursor-pointer" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <i class="fa-regular fa-calendar-check absolute left-3 top-3.5 text-brand-gold"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Trạng thái phòng & Nút Submit -->
                        @if($phongTrong > 0)
                            <div class="mb-6 bg-green-50 text-green-800 px-4 py-3 rounded-lg flex items-center text-sm border border-green-200">
                                <i class="fa-solid fa-circle-check mr-3 text-lg"></i> 
                                <div>
                                    <span class="font-bold block">Còn phòng!</span>
                                    <span class="text-xs">Hiện có {{ $phongTrong }} phòng trống cho bạn.</span>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-brand-900 text-white font-bold py-4 rounded-xl hover:bg-brand-800 transition duration-300 shadow-xl shadow-brand-900/20 transform hover:-translate-y-1 flex justify-center items-center gap-2 group">
                                <span>ĐẶT PHÒNG NGAY</span>
                                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        @else
                            <div class="mb-6 bg-red-50 text-red-800 px-4 py-3 rounded-lg flex items-center text-sm border border-red-200">
                                <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i> 
                                <div>
                                    <span class="font-bold block">Hết phòng</span>
                                    <span class="text-xs">Rất tiếc, loại phòng này hiện không còn trống.</span>
                                </div>
                            </div>
                            <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-4 rounded-xl cursor-not-allowed border border-gray-200">
                                TẠM HẾT PHÒNG
                            </button>
                        @endif
                        
                        <p class="text-[11px] text-center text-gray-400 mt-4 leading-tight px-4">
                            * Bạn sẽ được yêu cầu đăng nhập và cập nhật hồ sơ để hoàn tất đặt phòng.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT KIỂM TRA NGÀY HỢP LỆ --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkinInput = document.getElementById('checkin_date');
        const checkoutInput = document.getElementById('checkout_date');
        const bookingForm = document.getElementById('booking-date-form');

        // 1. Tự động cập nhật min date cho Checkout khi chọn Checkin
        checkinInput.addEventListener('change', function() {
            if (this.value) {
                // Ngày trả phòng ít nhất phải sau ngày nhận phòng 1 ngày
                const checkinDate = new Date(this.value);
                checkinDate.setDate(checkinDate.getDate() + 1);
                
                // Format YYYY-MM-DD
                const minCheckout = checkinDate.toISOString().split('T')[0];
                checkoutInput.min = minCheckout;
                
                // Nếu ngày checkout hiện tại nhỏ hơn ngày checkin mới, reset nó
                if (checkoutInput.value && checkoutInput.value <= this.value) {
                    checkoutInput.value = minCheckout;
                }
            }
        });

        // 2. Validate khi bấm nút Submit
        bookingForm.addEventListener('submit', function(e) {
            const checkin = checkinInput.value;
            const checkout = checkoutInput.value;

            if (!checkin || !checkout) {
                e.preventDefault();
                alert('Vui lòng chọn đầy đủ ngày nhận và trả phòng!');
                return;
            }

            const d1 = new Date(checkin);
            const d2 = new Date(checkout);

            if (d1 >= d2) {
                e.preventDefault();
                alert('Ngày trả phòng phải lớn hơn ngày nhận phòng ít nhất 1 ngày!');
            }
        });
    });
</script>
@endpush

@endsection