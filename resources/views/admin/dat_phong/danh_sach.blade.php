@extends('admin.layouts.dashboard')
@section('title', 'Sơ đồ Phòng')
@section('header', 'Quản lý đặt phòng')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        
        <div class="flex flex-wrap items-center gap-3 text-xs font-medium bg-white px-5 py-3 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-600 border border-gray-200">
                <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span> Trống
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-50 text-red-700 border border-red-200">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span> Chờ duyệt
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 border border-green-200">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Có khách
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-50 text-yellow-700 border border-yellow-200">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Bảo trì
            </div>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            <form method="GET" action="{{ url()->current() }}" class="relative flex items-center gap-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm số phòng hoặc tên khách..." class="w-56 h-10 pl-10 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold" />
                <button type="submit" class="ml-2 h-10 px-4 bg-gray-100 text-gray-700 font-bold rounded-lg">Tìm</button>
                @if(request('q'))
                    <a href="{{ url()->current() }}" class="ml-2 h-10 px-4 bg-white border border-gray-300 text-gray-500 rounded-lg">Đặt lại</a>
                @endif
            </form>
            <a href="{{ route('admin.dat-phong.history') }}" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Lịch sử
            </a>
            <a href="{{ route('admin.dat-phong.trash') }}" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:text-red-600 hover:border-red-300 transition-all shadow-sm">
                <i class="fa-solid fa-trash mr-2"></i> Thùng rác
            </a>
            <a href="{{ route('admin.dat-phong.them') }}" class="flex items-center px-5 py-2 bg-brand-900 text-brand-gold rounded-lg text-sm font-bold hover:bg-gray-800 hover:-translate-y-0.5 transition-all shadow-md">
                <i class="fa-solid fa-plus mr-2"></i> Tạo đơn mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach($paginatedPhongs as $phong)
            @php
                // --- LOGIC TRẠNG THÁI ---
                $activeBooking = null;
                $cardClasses = 'bg-white border-gray-200 text-gray-500 hover:border-brand-gold hover:shadow-lg';
                $icon = 'fa-door-open';
                $statusText = 'Phòng trống';
                
                // Tìm đơn active
                $chiTiet = $phong->chiTietDatPhongs->first(function($detail) {
                    return $detail->datPhong !== null;
                });

                if ($chiTiet) {
                    $activeBooking = $chiTiet->datPhong;
                    
                    if ($activeBooking->trang_thai == 'pending') {
                        $cardClasses = 'bg-red-50/60 border-red-300 text-red-800 shadow-sm ring-1 ring-red-100';
                        $icon = 'fa-clock';
                        $statusText = 'Chờ duyệt';
                    } elseif (in_array($activeBooking->trang_thai, ['confirmed', 'paid', 'awaiting_payment'])) {
                        $cardClasses = 'bg-green-50/60 border-green-300 text-green-800 shadow-sm ring-1 ring-green-100';
                        $icon = 'fa-user-check';
                        $statusText = 'Đang sử dụng';
                    }
                } elseif ($phong->tinh_trang === 'maintenance') {
                    $cardClasses = 'bg-yellow-50/60 border-yellow-300 text-yellow-800 opacity-80';
                    $icon = 'fa-screwdriver-wrench';
                    $statusText = 'Bảo trì';
                }
            @endphp

            <a href="{{ route('admin.dat-phong.room-detail', $phong->id) }}" 
               class="relative group h-48 rounded-2xl border transition-all duration-300 flex flex-col items-center justify-center p-4 cursor-pointer {{ $cardClasses }}">
                
                <span class="text-4xl font-serif font-bold mb-3 tracking-tight {{ $activeBooking ? '' : 'group-hover:text-brand-gold transition-colors' }}">
                    {{ $phong->so_phong }}
                </span>
                
                <div class="flex items-center gap-2 text-sm font-medium uppercase tracking-wide text-xs">
                    <i class="fa-solid {{ $icon }}"></i> <span>{{ $statusText }}</span>
                </div>

                @if($activeBooking)
                    <span class="absolute top-3 right-3 flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $activeBooking->trang_thai == 'pending' ? 'bg-red-400' : 'bg-green-400' }}"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 {{ $activeBooking->trang_thai == 'pending' ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    </span>
                    
                    <div class="absolute bottom-0 inset-x-0 bg-white/80 backdrop-blur-sm p-2 text-center border-t border-gray-100 rounded-b-2xl">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ $activeBooking->user->name ?? 'Guest' }}</p>
                    </div>
                @else
                    <div class="absolute inset-0 bg-brand-900/90 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center px-2">
                        <div>
                            <span class="text-brand-gold font-bold uppercase text-xs tracking-widest block mb-1">Xem chi tiết</span>
                            <span class="text-white text-[10px] block">hoặc tạo đơn mới</span>
                        </div>
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $paginatedPhongs->withQueryString()->links() }}
    </div>
</div>
@endsection