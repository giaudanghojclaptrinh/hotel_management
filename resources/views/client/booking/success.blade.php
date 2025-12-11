@extends('layouts.app')
@section('title', 'Đặt phòng thành công')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-md w-full bg-white p-8 md:p-12 rounded-3xl shadow-2xl text-center border-t-8 
        @if(session('error')) border-red-500 @else border-brand-gold @endif
        relative overflow-hidden animate-fade-in-up">
        
        <!-- Background Pattern (Họa tiết chìm) -->
        <div class="absolute top-0 left-0 w-full h-full opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#111827 1px, transparent 1px); background-size: 20px 20px;"></div>

        <!-- Icon Trạng thái -->
        @if(session('error'))
            <div class="relative mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-50 mb-8 animate-shake-x">
                <i class="fa-solid fa-xmark text-5xl text-red-500"></i>
                <div class="absolute inset-0 rounded-full border-4 border-red-100 opacity-50 animate-pulse"></div>
            </div>
        @else
            <div class="relative mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-50 mb-8 animate-bounce-slow">
                <i class="fa-solid fa-check text-5xl text-green-500"></i>
                <div class="absolute inset-0 rounded-full border-4 border-green-100 opacity-50 animate-ping"></div>
            </div>
        @endif
        
        @if(session('error'))
            <h2 class="text-3xl font-serif font-bold text-red-600 mb-2">Thất bại!</h2>
            <p class="text-gray-600 mb-8">{{ session('error') }}</p>
        @else
            <h2 class="text-3xl font-serif font-bold text-brand-900 mb-2">Tuyệt vời!</h2>
            <p class="text-gray-600 mb-8">{{ session('success') ?? 'Yêu cầu đặt phòng của bạn đã được gửi thành công.' }}</p>
        @endif
        
        <!-- Khu vực hiển thị Mã đơn -->
        <div class="bg-brand-50 border border-brand-gold/20 py-5 px-6 rounded-xl mb-8 relative group cursor-pointer transition hover:shadow-md">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2 font-bold">Mã đơn đặt phòng của bạn</p>
            <p class="font-mono text-3xl font-bold text-brand-900 tracking-widest selection:bg-brand-gold selection:text-white">
                #BK-{{ session('booking_id') ?? 'N/A' }}
            </p>
            <!-- Icon trang trí -->
            <div class="absolute -top-3 -right-3 text-brand-gold opacity-20 group-hover:opacity-40 transition-opacity">
                <i class="fa-solid fa-ticket text-6xl transform rotate-12"></i>
            </div>
        </div>

        <p class="text-sm text-gray-500 mb-8 leading-relaxed px-4">
            @if(session('error'))
                Vui lòng thử lại quá trình thanh toán hoặc chọn phương thức thanh toán tại khách sạn.
            @else
                Chúng tôi đã gửi thông tin chi tiết về đơn hàng đến email của bạn.
            @endif
        </p>

        <!-- Các nút điều hướng -->
        <div class="space-y-3">
            @if (Route::has('bookings.history'))
            <a href="{{ route('bookings.history') }}" class="block w-full py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-brand-900 hover:bg-brand-800 transition shadow-lg shadow-brand-900/20 transform hover:-translate-y-0.5">
                Xem chi tiết đơn hàng
            </a>
            @endif
            
            <a href="{{ route('trang_chu') }}" class="block w-full py-3.5 px-4 border border-gray-200 text-sm font-bold rounded-xl text-gray-600 bg-white hover:bg-gray-50 transition hover:border-gray-300">
                Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection