@extends('layouts.app')
@section('title', 'Xác nhận đặt phòng')

@section('content')
<div class="bg-gray-50 py-16 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-2">Hoàn tất đặt phòng</h1>
            <p class="text-gray-500">Vui lòng kiểm tra lại thông tin và áp dụng ưu đãi trước khi xác nhận.</p>
            
            {{-- Hiển thị thông báo lỗi/thành công từ Controller --}}
            @if(session('error'))
                <div class="mt-4 bg-red-100 text-red-800 p-3 rounded-lg text-sm">{{ session('error') }}</div>
            @endif
        </div>

        <form action="{{ route('booking.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            <input type="hidden" name="room_id" value="{{ $roomType->id }}">
            <input type="hidden" name="checkin" value="{{ $checkIn }}">
            <input type="hidden" name="checkout" value="{{ $checkOut }}">

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">1</span>
                        Thông tin đăng ký
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Họ và tên</label>
                            <span class="block bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số điện thoại</label>
                            <span class="block bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 font-medium">{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</span>
                        </div>
                        <div class="col-span-full mt-2">
                             <p class="text-xs text-gray-500 italic">
                                Vui lòng đảm bảo thông tin trên là chính xác. Nếu cần thay đổi, bạn có thể <a href="{{ route('profile.edit') }}" class="underline font-bold text-brand-gold hover:text-brand-900">cập nhật hồ sơ</a>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">2</span>
                        Mã khuyến mãi
                    </h3>
                    <div class="flex gap-3">
                        <input type="text" 
                               name="promotion_code" 
                               value="{{ old('promotion_code') }}"
                               placeholder="Nhập mã giảm giá (VD: SUMMER2025)..." 
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm uppercase">
                    </div>
                    @error('promotion_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">3</span>
                        Lựa chọn thanh toán
                    </h3>
                    
                    <div class="space-y-4">
                        <label class="relative flex items-start p-5 border-2 rounded-xl cursor-pointer transition-all {{ old('payment_method') == 'pay_at_hotel' ? 'bg-brand-50 border-brand-gold' : 'border-gray-200 hover:border-brand-gold' }}">
                            <div class="flex items-center h-6">
                                <input type="radio" name="payment_method" value="pay_at_hotel" checked 
                                       class="w-5 h-5 text-brand-900 border-gray-300 focus:ring-brand-900">
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-900 text-lg">Thanh toán tại khách sạn</span>
                                <span class="block text-sm text-gray-600 mt-1">Bạn sẽ thanh toán khi làm thủ tục nhận phòng (Check-in).</span>
                            </div>
                            <i class="fa-solid fa-hotel absolute top-5 right-5 text-2xl text-brand-900/10"></i>
                        </label>

                        <label class="relative flex items-start p-5 border rounded-xl cursor-pointer transition-all {{ old('payment_method') == 'online' ? 'bg-brand-50 border-brand-gold' : 'border-gray-200 hover:border-brand-gold' }}">
                             <div class="flex items-center h-6">
                                <input type="radio" name="payment_method" value="online" 
                                       class="w-5 h-5 text-brand-900 border-gray-300 focus:ring-brand-900">
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-900 text-lg">Thanh toán Online (Sau khi Admin duyệt)</span>
                                <span class="block text-sm text-gray-600 mt-1">Sau khi đơn được duyệt, bạn sẽ nhận link thanh toán qua email.</span>
                            </div>
                            <i class="fa-brands fa-cc-paypal absolute top-5 right-5 text-2xl text-brand-900/10"></i>
                        </label>
                    </div>
                    @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 text-center uppercase tracking-wider text-xs">Chi tiết đơn đặt</h3>
                    
                    <div class="flex gap-4 mb-6">
                        <img src="{{ $roomType->hinh_anh ? asset($roomType->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                             class="w-20 h-20 object-cover rounded-lg shadow-sm border border-gray-200">
                        <div>
                            <h4 class="font-bold text-brand-900 leading-tight mb-1">{{ $roomType->ten_loai_phong }}</h4>
                            <p class="text-xs text-gray-500"><i class="fa-solid fa-user mr-1"></i> {{ $roomType->so_nguoi }} Khách</p>
                        </div>
                    </div>

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

                    <div class="space-y-3 text-sm mb-8">
                        <div class="flex justify-between text-gray-600">
                            <span>Giá phòng</span>
                            <span>{{ number_format($roomType->gia, 0, ',', '.') }}đ x {{ $days }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Giá gốc tạm tính</span>
                            <span class="font-medium text-gray-900">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between text-red-500 font-medium">
                            <span>Giảm giá (Mã KM)</span>
                            <span>- 0đ</span>
                        </div>
                        
                        <div class="border-t border-dashed border-gray-200 my-2"></div>
                        
                        <div class="flex justify-between items-center pt-1">
                            <span class="font-bold text-lg text-gray-800">Tổng cộng (Tạm tính)</span>
                            <span class="font-bold text-2xl text-brand-gold">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Tổng tiền cuối cùng sẽ được xác nhận sau khi mã khuyến mãi được Admin kiểm tra.</p>
                    </div>

                    <button type="submit" class="w-full bg-brand-900 text-white font-bold py-4 rounded-xl hover:bg-brand-800 transition duration-300 shadow-xl shadow-brand-900/20 flex items-center justify-center gap-2 group">
                        <span>GỬI YÊU CẦU ĐẶT PHÒNG</span>
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