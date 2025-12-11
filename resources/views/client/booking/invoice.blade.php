@extends('layouts.app')
@section('title', 'Chi tiết hóa đơn')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Nút quay lại -->
        <div class="mb-6">
            <a href="{{ route('bookings.history') }}" class="text-sm text-gray-600 hover:text-brand-900 flex items-center transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại lịch sử
            </a>
        </div>

        <!-- Card Hóa đơn -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden print:shadow-none animate-fade-in-up">
            
            <!-- Header Hóa đơn -->
            <div class="bg-brand-900 text-white p-8 text-center relative overflow-hidden">
                <!-- Họa tiết nền mờ -->
                <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                
                <h1 class="text-3xl font-serif font-bold relative z-10 tracking-wide">HÓA ĐƠN DỊCH VỤ</h1>
                <p class="opacity-80 text-sm mt-1 relative z-10 uppercase tracking-widest">Luxury Stay Hotel & Resort</p>
                
                <!-- Mã hóa đơn -->
                <div class="mt-4 inline-block px-4 py-1 border border-white/30 rounded-full text-sm font-mono relative z-10 bg-white/10 backdrop-blur-sm">
                    #{{ $booking->hoaDon ? $booking->hoaDon->ma_hoa_don : 'TMP-'.$booking->id }}
                </div>
            </div>

            <div class="p-8 md:p-12">
                <!-- Thông tin khách hàng & Đơn hàng -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-100">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Khách hàng</h4>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->user->name }}</p>
                        <p class="text-gray-600 text-sm mt-1"><i class="fa-regular fa-envelope mr-2 w-4"></i> {{ $booking->user->email }}</p>
                        <p class="text-gray-600 text-sm mt-1"><i class="fa-solid fa-phone mr-2 w-4"></i> {{ $booking->user->phone ?? 'Chưa cập nhật SĐT' }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Chi tiết đặt phòng</h4>
                        <p class="text-gray-900 text-sm mb-1">
                            <span class="font-bold text-gray-500 mr-2">Ngày đặt:</span> {{ $booking->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-gray-900 text-sm mb-1">
                            <span class="font-bold text-gray-500 mr-2">Nhận phòng:</span> {{ \Carbon\Carbon::parse($booking->ngay_den)->format('d/m/Y') }}
                        </p>
                        <p class="text-gray-900 text-sm mb-2">
                            <span class="font-bold text-gray-500 mr-2">Trả phòng:</span> {{ \Carbon\Carbon::parse($booking->ngay_di)->format('d/m/Y') }}
                        </p>
                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fa-solid fa-credit-card mr-1.5"></i>
                            {{ $booking->payment_method == 'online' ? 'Thanh toán Online (VNPay)' : 'Thanh toán tại khách sạn' }}
                        </div>
                    </div>
                </div>

                <!-- Bảng chi tiết dịch vụ -->
                <div class="mb-8">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Dịch vụ sử dụng</h4>
                    <div class="overflow-x-auto border border-gray-100 rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                                <tr>
                                    <th class="py-3 px-4">Hạng phòng</th>
                                    <th class="py-3 px-4 text-center">Số đêm</th>
                                    <th class="py-3 px-4 text-right">Đơn giá</th>
                                    <th class="py-3 px-4 text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($booking->chiTietDatPhongs as $detail)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 font-medium text-gray-900">
                                        {{ $detail->loaiPhong->ten_loai_phong }}
                                        @if($detail->phong)
                                            <div class="text-xs text-green-600 mt-1 flex items-center font-bold">
                                                <i class="fa-solid fa-door-open mr-1"></i> Phòng {{ $detail->phong->so_phong }}
                                            </div>
                                        @else
                                            <div class="text-xs text-orange-500 mt-1 italic flex items-center">
                                                <i class="fa-solid fa-hourglass-half mr-1"></i> Đang xếp phòng
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->ngay_den)->diffInDays($booking->ngay_di) }}
                                    </td>
                                    <td class="py-4 px-4 text-right text-gray-600">{{ number_format($detail->don_gia, 0, ',', '.') }}đ</td>
                                    <td class="py-4 px-4 text-right font-bold text-gray-900">{{ number_format($detail->thanh_tien, 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tổng kết tài chính -->
                <div class="flex justify-end">
                    <div class="w-full md:w-1/2 space-y-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tạm tính</span>
                            <span class="font-medium">{{ number_format($booking->chiTietDatPhongs->sum('thanh_tien'), 0, ',', '.') }}đ</span>
                        </div>
                        
                        @if($booking->discount_amount > 0)
                        <div class="flex justify-between text-sm text-red-500 font-medium">
                            <span>Giảm giá ({{ $booking->promotion_code }})</span>
                            <span>-{{ number_format($booking->discount_amount, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                        
                        <div class="border-t border-dashed border-gray-200 my-2"></div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Tổng thanh toán</span>
                            <span class="text-2xl font-serif font-bold text-brand-gold">{{ number_format($booking->tong_tien, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <!-- Trạng thái thanh toán (Con dấu) -->
                        <div class="text-right mt-6">
                            @if($booking->payment_status == 'paid')
                                <div class="inline-block px-6 py-2 border-2 border-green-500 text-green-600 font-bold text-sm tracking-widest uppercase transform -rotate-6 opacity-80 mask-image" style="border-radius: 8px;">
                                    ĐÃ THANH TOÁN
                                </div>
                            @elseif($booking->payment_status == 'awaiting_payment')
                                <div class="inline-block px-6 py-2 border-2 border-orange-400 text-orange-500 font-bold text-sm tracking-widest uppercase transform -rotate-6 opacity-80" style="border-radius: 8px;">
                                    CHỜ THANH TOÁN
                                </div>
                            @else
                                <div class="inline-block px-6 py-2 border-2 border-gray-300 text-gray-400 font-bold text-sm tracking-widest uppercase transform -rotate-6 opacity-80" style="border-radius: 8px;">
                                    CHƯA THANH TOÁN
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Hóa đơn -->
                <div class="mt-12 pt-8 border-t border-gray-100 text-center text-xs text-gray-400">
                    <p class="mb-1 font-medium text-gray-500">Cảm ơn quý khách đã lựa chọn Luxury Stay Hotel & Resort.</p>
                    <p>Mọi thắc mắc xin vui lòng liên hệ hotline: <strong class="text-gray-600">1900 1234</strong> hoặc email: <span class="text-brand-900">support@luxurystay.com</span></p>
                    <p class="mt-4 italic opacity-70">Hóa đơn điện tử này có giá trị như hóa đơn giấy.</p>
                </div>
            </div>
        </div>

        <!-- Nút In (Ẩn khi in) -->
        <div class="mt-8 text-center print:hidden">
            <button onclick="window.print()" class="px-8 py-3 bg-white border border-gray-300 rounded-xl text-gray-700 font-bold hover:bg-gray-50 hover:text-brand-900 hover:border-brand-900 transition shadow-sm flex items-center mx-auto gap-2">
                <i class="fa-solid fa-print"></i> In hóa đơn
            </button>
        </div>
    </div>
</div>
@endsection