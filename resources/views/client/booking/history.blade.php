@extends('layouts.app')
@section('title', 'Lịch sử đặt phòng')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif font-bold text-gray-900">Đơn đặt phòng của tôi</h1>
                <p class="text-gray-500 mt-2">Quản lý và xem lại lịch sử các chuyến đi của bạn.</p>
            </div>
            <a href="{{ route('phong.danh-sach') }}" class="inline-flex items-center px-5 py-2.5 border border-brand-900 text-sm font-bold rounded-xl text-brand-900 bg-white hover:bg-brand-50 transition shadow-sm group">
                <i class="fa-solid fa-plus mr-2 group-hover:rotate-90 transition-transform"></i> Đặt phòng mới
            </a>
        </div>

        @if($bookings->isEmpty())
            <!-- Trạng thái trống -->
            <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100 animate-fade-in-up">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-regular fa-calendar-xmark text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có đơn đặt phòng nào</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Bạn chưa thực hiện đặt phòng nào tại hệ thống của chúng tôi. Hãy khám phá ngay những căn phòng tuyệt vời đang chờ đón bạn.</p>
                <a href="{{ route('phong.danh-sach') }}" class="inline-flex items-center px-8 py-3.5 border border-transparent text-base font-bold rounded-xl shadow-lg shadow-brand-900/20 text-white bg-brand-900 hover:bg-brand-800 transition transform hover:-translate-y-1">
                    Đặt phòng ngay
                </a>
            </div>
        @else
            <!-- Danh sách đơn hàng -->
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    @php
                        // Xử lý trạng thái & Badge màu sắc
                        $statusConfig = [
                            'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'label' => 'Chờ duyệt', 'icon' => 'fa-clock'],
                            'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Đã xác nhận', 'icon' => 'fa-check-circle'],
                            'completed' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Hoàn thành', 'icon' => 'fa-star'],
                            'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Đã hủy', 'icon' => 'fa-circle-xmark'],
                        ];
                        
                        $statusKey = $booking->trang_thai ?? 'pending';
                        $st = $statusConfig[$statusKey] ?? $statusConfig['pending'];

                        // Lấy thông tin chi tiết (1 đơn 1 phòng)
                        $detail = $booking->chiTietDatPhongs->first();
                        $roomInfo = $detail ? $detail->loaiPhong : null;
                        $physicalRoom = $detail ? $detail->phong : null;
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 animate-fade-in-up group relative">
                        
                        <!-- Header Card -->
                        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider bg-white px-2 py-1 rounded border border-gray-200">
                                    #BK-{{ $booking->id }}
                                </span>
                                <span class="text-sm text-gray-500 flex items-center">
                                    <i class="fa-regular fa-calendar-check mr-1.5 text-gray-400"></i> 
                                    Đặt ngày {{ $booking->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <!-- Badge Trạng thái Đơn -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}">
                                    <i class="fa-solid {{ $st['icon'] }} mr-1.5"></i> {{ $st['label'] }}
                                </span>

                                <!-- Badge Thanh toán -->
                                @if($booking->payment_status == 'paid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200" title="Đã thanh toán đủ">
                                        <i class="fa-solid fa-money-bill-wave mr-1.5"></i> Đã TT
                                    </span>
                                @elseif($booking->payment_status == 'awaiting_payment')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200" title="Đang chờ thanh toán Online">
                                        <i class="fa-solid fa-hourglass-half mr-1.5"></i> Chờ TT
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200" title="Thanh toán sau">
                                        <i class="fa-regular fa-circle mr-1.5"></i> Chưa TT
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 md:flex gap-8">
                            <!-- Hình ảnh phòng -->
                            <div class="md:w-1/3 lg:w-1/4 h-48 md:h-auto relative rounded-xl overflow-hidden shadow-sm border border-gray-100 group">
                                @if($roomInfo)
                                    <img src="{{ $roomInfo->hinh_anh ? asset($roomInfo->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700" 
                                         alt="{{ $roomInfo->ten_loai_phong }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image text-4xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Thông tin chi tiết -->
                            <div class="md:w-2/3 lg:w-3/4 flex flex-col justify-between pt-4 md:pt-0">
                                <div>
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-2">
                                        <div>
                                            <h3 class="text-xl font-serif font-bold text-brand-900 hover:text-brand-gold transition-colors">
                                                {{ $roomInfo ? $roomInfo->ten_loai_phong : 'Thông tin phòng không khả dụng' }}
                                            </h3>
                                            
                                            <!-- [MỚI] Hiển thị Số phòng vật lý -->
                                            @if($physicalRoom)
                                                <div class="flex items-center mt-1 text-sm font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded w-fit border border-green-100">
                                                    <i class="fa-solid fa-door-open mr-1.5"></i> 
                                                    Phòng số: <strong class="ml-1">{{ $physicalRoom->so_phong }}</strong>
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-400 mt-1 italic flex items-center">
                                                    <i class="fa-solid fa-door-closed mr-1.5"></i> Đang sắp xếp phòng...
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="text-left md:text-right">
                                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wide">Tổng thanh toán</p>
                                            <span class="block text-2xl font-bold text-brand-gold">{{ number_format($booking->tong_tien, 0, ',', '.') }}đ</span>
                                        </div>
                                    </div>

                                    <!-- Grid thông tin ngày giờ -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                            <span class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Nhận phòng</span>
                                            <div class="flex items-center text-gray-900 font-medium">
                                                <i class="fa-regular fa-calendar-plus text-blue-500 mr-2"></i>
                                                {{ \Carbon\Carbon::parse($booking->ngay_den)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                            <span class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Trả phòng</span>
                                            <div class="flex items-center text-gray-900 font-medium">
                                                <i class="fa-regular fa-calendar-minus text-orange-500 mr-2"></i>
                                                {{ \Carbon\Carbon::parse($booking->ngay_di)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                            <span class="block text-[10px] text-gray-400 uppercase font-bold mb-1">Thanh toán</span>
                                            <div class="flex items-center text-gray-900 font-medium">
                                                <i class="fa-solid fa-credit-card text-purple-500 mr-2"></i>
                                                {{ $booking->payment_method == 'online' ? 'Online (VNPay)' : 'Tại khách sạn' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Ghi chú -->
                                    @if($booking->ghi_chu)
                                        <div class="flex items-start gap-2 text-xs text-gray-500 italic bg-brand-50/50 p-3 rounded-lg border border-brand-100">
                                            <i class="fa-regular fa-note-sticky mt-0.5 text-brand-gold"></i> 
                                            <span>"{{ $booking->ghi_chu }}"</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Footer -->
                                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end items-center gap-3">
                                    @if($booking->trang_thai == 'pending' || $booking->payment_status == 'awaiting_payment')
                                         <span class="text-xs text-gray-400 italic mr-auto hidden sm:block">* Đơn hàng đang được xử lý</span>
                                    @endif

                                    {{-- [QUAN TRỌNG] Nút xem chi tiết hóa đơn --}}
                                    <a href="{{ route('bookings.invoice', $booking->id) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-brand-900 hover:border-brand-900 transition">
                                        <i class="fa-solid fa-file-invoice-dollar mr-2 text-brand-gold"></i> Xem hóa đơn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection