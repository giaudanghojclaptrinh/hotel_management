@extends('admin.layouts.dashboard')
@section('title', 'Sơ đồ Phòng')
@section('header', 'Quản lý đặt phòng')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        
        <div class="flex flex-wrap items-center gap-3 text-xs font-medium bg-gray-900 px-5 py-3 rounded-xl shadow-sm border border-gray-800">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-800 text-gray-400 border border-gray-700">
                <span class="w-2.5 h-2.5 rounded-full bg-gray-500"></span> Trống
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-900/30 text-red-400 border border-red-800">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span> Chờ duyệt
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-900/30 text-green-400 border border-green-800">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Có khách
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-900/30 text-yellow-500 border border-yellow-800">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Bảo trì
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.dat-phong.history') }}" class="flex items-center px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Lịch sử
            </a>
            <a href="{{ route('admin.dat-phong.trash') }}" class="flex items-center px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-red-500 hover:border-red-500 transition-all shadow-sm">
                <i class="fa-solid fa-trash mr-2"></i> Thùng rác
            </a>
            <a href="{{ route('admin.dat-phong.them') }}" class="flex items-center px-5 py-2 bg-brand-gold text-gray-900 rounded-lg text-sm font-bold hover:bg-white transition-all shadow-md">
                <i class="fa-solid fa-plus mr-2"></i> Tạo đơn mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-900/30 border border-green-600 text-green-400 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach($paginatedPhongs as $phong)
            @php
                // --- LOGIC TRẠNG THÁI ---
                $activeBooking = null;
                // Mặc định: Phòng trống (Dark style)
                $cardClasses = 'bg-gray-900 border-gray-800 text-gray-500 hover:border-brand-gold hover:shadow-lg hover:shadow-brand-gold/10';
                $icon = 'fa-door-open';
                $statusText = 'Phòng trống';
                
                $chiTiet = $phong->chiTietDatPhongs->first(function($detail) {
                    return $detail->datPhong !== null;
                });

                if ($chiTiet) {
                    $activeBooking = $chiTiet->datPhong;
                    
                    if ($activeBooking->trang_thai == 'pending') {
                        // Chờ duyệt (Red Dark)
                        $cardClasses = 'bg-red-900/10 border-red-500/50 text-red-400 shadow-sm';
                        $icon = 'fa-clock';
                        $statusText = 'Chờ duyệt';
                    } elseif (in_array($activeBooking->trang_thai, ['confirmed', 'paid', 'awaiting_payment'])) {
                        // Có khách (Green Dark)
                        $cardClasses = 'bg-green-900/10 border-green-500/50 text-green-400 shadow-sm';
                        $icon = 'fa-user-check';
                        $statusText = 'Đang sử dụng';
                    }
                } elseif ($phong->tinh_trang === 'maintenance') {
                    // Bảo trì (Yellow Dark)
                    $cardClasses = 'bg-yellow-900/10 border-yellow-500/50 text-yellow-500 opacity-80';
                    $icon = 'fa-screwdriver-wrench';
                    $statusText = 'Bảo trì';
                }
            @endphp

            <a href="{{ route('admin.dat-phong.room-detail', $phong->id) }}" 
               class="relative group h-48 rounded-2xl border transition-all duration-300 flex flex-col items-center justify-center p-4 cursor-pointer {{ $cardClasses }}">
                
                <span class="text-4xl font-serif font-bold mb-3 tracking-tight {{ $activeBooking ? 'text-white' : 'group-hover:text-brand-gold transition-colors text-gray-600' }}">
                    {{ $phong->so_phong }}
                </span>
                
                <div class="flex items-center gap-2 text-sm font-medium uppercase tracking-wide text-xs">
                    <i class="fa-solid {{ $icon }}"></i> <span>{{ $statusText }}</span>
                </div>

                @if($activeBooking)
                    <span class="absolute top-3 right-3 flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $activeBooking->trang_thai == 'pending' ? 'bg-red-500' : 'bg-green-500' }}"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 {{ $activeBooking->trang_thai == 'pending' ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    </span>
                    
                    <div class="absolute bottom-0 inset-x-0 bg-gray-800/90 backdrop-blur-sm p-2 text-center border-t border-gray-700 rounded-b-2xl">
                        <p class="text-xs font-bold text-gray-200 truncate">{{ $activeBooking->user->name ?? 'Guest' }}</p>
                    </div>
                @else
                    <div class="absolute inset-0 bg-gray-900/90 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center px-2 border border-brand-gold">
                        <div>
                            <span class="text-brand-gold font-bold uppercase text-xs tracking-widest block mb-1">Quản lý</span>
                            <span class="text-gray-300 text-[10px] block">Xem chi tiết / Tạo đơn</span>
                        </div>
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $paginatedPhongs->links() }}
    </div>
</div>
@endsection