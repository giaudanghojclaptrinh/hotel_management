@extends('layouts.app')
@section('title', 'Xác nhận đặt phòng')

@section('content')
<div class="bg-gray-50 py-16 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-2">Hoàn tất đặt phòng</h1>
            <p class="text-gray-500">Vui lòng kiểm tra lại thông tin trước khi xác nhận.</p>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            <!-- Dữ liệu ẩn quan trọng để gửi về Server -->
            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
            <input type="hidden" name="check_in" value="{{ $checkIn }}">
            <input type="hidden" name="check_out" value="{{ $checkOut }}">

            <!-- CỘT TRÁI: Thông tin khách & Thanh toán -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Box 1: Thông tin khách hàng (Read-only từ Auth) -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">1</span>
                        Thông tin khách hàng
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Họ và tên</label>
                            <input type="text" value="{{ Auth::user()->name }}" disabled class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 font-medium cursor-not-allowed group-hover:bg-gray-100 transition">
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                            <input type="text" value="{{ Auth::user()->email }}" disabled class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 cursor-not-allowed group-hover:bg-gray-100 transition">
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số điện thoại</label>
                            <input type="text" value="{{ Auth::user()->phone }}" disabled class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-blue-900 font-bold cursor-not-allowed">
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">CCCD / CMND</label>
                            <input type="text" value="{{ Auth::user()->cccd }}" disabled class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-blue-900 font-bold cursor-not-allowed">
                        </div>
                    </div>

                    <div class="mt-6 flex items-start p-4 bg-yellow-50 text-yellow-800 rounded-xl text-sm border border-yellow-100">
                        <i class="fa-solid fa-circle-info mt-0.5 mr-3 text-yellow-600 text-lg"></i>
                        <div>
                            <p class="font-bold mb-1">Thông tin trên có chính xác không?</p>
                            <p>Đây là thông tin dùng để làm thủ tục nhận phòng. Nếu sai, vui lòng <a href="{{ route('profile.edit') }}" class="underline font-bold text-yellow-900 hover:text-yellow-700">cập nhật hồ sơ</a> trước khi tiếp tục.</p>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Phương thức thanh toán -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">2</span>
                        Thanh toán
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Option 1: Trả sau -->
                        <label class="relative flex items-start p-5 border-2 rounded-xl cursor-pointer bg-brand-50 border-brand-gold transition-all shadow-sm hover:shadow-md">
                            <div class="flex items-center h-6">
                                <input type="radio" name="payment_method" value="pay_at_hotel" checked class="w-5 h-5 text-brand-900 border-gray-300 focus:ring-brand-900">
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-900 text-lg">Thanh toán tại khách sạn</span>
                                <span class="block text-sm text-gray-600 mt-1">Bạn sẽ thanh toán toàn bộ chi phí khi làm thủ tục nhận phòng tại quầy lễ tân.</span>
                                <div class="mt-3 flex gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-green-100 text-green-800 border border-green-200"><i class="fa-solid fa-money-bill-wave mr-1"></i> Tiền mặt</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"><i class="fa-regular fa-credit-card mr-1"></i> Thẻ tín dụng</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-hotel absolute top-5 right-5 text-2xl text-brand-900/10"></i>
                        </label>

                        <!-- Option 2: VNPay (Disabled) -->
                        <label class="flex items-center p-5 border rounded-xl cursor-not-allowed bg-gray-50 opacity-60">
                            <input type="radio" disabled class="w-5 h-5 text-gray-400 border-gray-300">
                            <div class="ml-4">
                                <span class="block font-bold text-gray-500">Thanh toán Online (VNPay / Momo)</span>
                                <span class="block text-sm text-gray-400">Tính năng đang bảo trì</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: Tóm tắt đơn hàng (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 text-center uppercase tracking-wider text-xs">Chi tiết đơn đặt</h3>
                    
                    <!-- Thông tin phòng -->
                    <div class="flex gap-4 mb-6">
                        <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=200&q=80" class="w-20 h-20 object-cover rounded-lg shadow-sm border border-gray-200">
                        <div>
                            <h4 class="font-bold text-brand-900 leading-tight mb-1">{{ $roomType->ten_loai_phong }}</h4>
                            <p class="text-xs text-gray-500"><i class="fa-solid fa-user mr-1"></i> 2 Người lớn</p>
                        </div>
                    </div>

                    <!-- Thời gian -->
                    <div class="space-y-4 text-sm text-gray-600 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex justify-between items-center">
                            <div class="text-left">
                                <span class="block text-[10px] text-gray-400 uppercase font-bold">Nhận phòng</span>
                                <span class="font-bold text-gray-900 text-base">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-gray-300"><i class="fa-solid fa-arrow-right-long"></i></div>
                            <div class="text-right">
                                <span class="block text-[10px] text-gray-400 uppercase font-bold">Trả phòng</span>
                                <span class="font-bold text-gray-900 text-base">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3 text-center">
                            <span class="inline-block bg-white px-3 py-1 rounded-full text-xs font-bold shadow-sm border border-gray-200 text-brand-gold">
                                <i class="fa-regular fa-clock mr-1"></i> {{ $days }} Đêm lưu trú
                            </span>
                        </div>
                    </div>

                    <!-- Chi phí -->
                    <div class="space-y-3 text-sm mb-8">
                        <div class="flex justify-between text-gray-600">
                            <span>Giá phòng</span>
                            <span>{{ number_format($roomType->gia_dem, 0, ',', '.') }}đ x {{ $days }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Phí dịch vụ</span>
                            <span class="text-green-600 font-medium">Miễn phí</span>
                        </div>
                        <div class="border-t border-dashed border-gray-200 my-2"></div>
                        <div class="flex justify-between items-center pt-1">
                            <span class="font-bold text-lg text-gray-800">Tổng cộng</span>
                            <span class="font-bold text-2xl text-brand-gold">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <!-- Nút Submit -->
                    <button type="submit" class="w-full bg-brand-900 text-white font-bold py-4 rounded-xl hover:bg-brand-800 transition duration-300 shadow-xl shadow-brand-900/20 flex items-center justify-center gap-2 group">
                        <span>XÁC NHẬN ĐẶT PHÒNG</span>
                        <i class="fa-solid fa-check group-hover:scale-125 transition-transform"></i>
                    </button>
                    
                    <p class="text-[10px] text-center text-gray-400 mt-4 leading-normal px-2">
                        Bằng việc xác nhận, bạn đồng ý với <a href="#" class="underline hover:text-brand-gold">Điều khoản & Chính sách</a> của Luxury Stay.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection