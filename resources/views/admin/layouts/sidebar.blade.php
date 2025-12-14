<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" 
       class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transition-transform duration-300 lg:static lg:inset-0 lg:translate-x-0 shadow-2xl flex flex-col border-r border-gray-800">
    
    <div class="flex items-center justify-center h-20 border-b border-gray-800 bg-gray-900">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-lg bg-gray-800 border border-brand-gold/30 flex items-center justify-center text-brand-gold group-hover:bg-brand-gold group-hover:text-gray-900 transition-all duration-300 shadow-lg shadow-brand-gold/10">
                <i class="fa-solid fa-crown text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-bold font-serif tracking-wide text-white leading-none">
                    Luxury<span class="text-brand-gold">Stay</span>
                </span>
                <span class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Admin Panel</span>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative overflow-hidden
           {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-brand-gold shadow-md border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-gauge-high w-6 text-center text-lg {{ request()->routeIs('admin.dashboard') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3 tracking-wide">Tổng quan</span>
        </a>

        <div class="pt-8 pb-3 px-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-800 pb-2">Vận hành</p>
        </div>

        <a href="{{ route('admin.dat-phong') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.dat-phong.*') ? 'bg-gray-800 text-brand-gold shadow-md border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-calendar-check w-6 text-center text-lg {{ request()->routeIs('admin.dat-phong.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3 tracking-wide">Đặt phòng</span>
            @if(isset($pendingBookings) && $pendingBookings > 0)
                <span class="ml-auto bg-red-600 text-white py-0.5 px-2 rounded-md text-[10px] font-bold shadow-sm animate-pulse">{{ $pendingBookings }}</span>
            @endif
        </a>

        <div x-data="{ open: {{ request()->routeIs('admin.phong.*') || request()->routeIs('admin.loai-phong.*') || request()->routeIs('admin.tien-nghi.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white transition-all group">
                <div class="flex items-center">
                    <i class="fa-solid fa-door-open w-6 text-center text-lg text-gray-500 group-hover:text-brand-gold"></i>
                    <span class="ml-3 tracking-wide">Phòng nghỉ</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 text-gray-600" :class="open ? 'rotate-180 text-brand-gold' : ''"></i>
            </button>
            
            <div x-show="open" x-collapse class="space-y-1 mt-1 bg-black/20 rounded-xl overflow-hidden py-1">
                <a href="{{ route('admin.phong') }}" 
                   class="flex items-center pl-12 pr-4 py-2.5 text-sm transition-colors {{ request()->routeIs('admin.phong.*') ? 'text-brand-gold bg-white/5 font-bold' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('admin.phong.*') ? 'bg-brand-gold shadow-[0_0_5px_#c5a47e]' : 'bg-gray-700' }}"></span>
                    Danh sách phòng
                </a>
                <a href="{{ route('admin.loai-phong') }}" 
                   class="flex items-center pl-12 pr-4 py-2.5 text-sm transition-colors {{ request()->routeIs('admin.loai-phong.*') ? 'text-brand-gold bg-white/5 font-bold' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('admin.loai-phong.*') ? 'bg-brand-gold shadow-[0_0_5px_#c5a47e]' : 'bg-gray-700' }}"></span>
                    Loại phòng & Giá
                </a>
                <a href="{{ route('admin.tien-nghi') }}" 
                   class="flex items-center pl-12 pr-4 py-2.5 text-sm transition-colors {{ request()->routeIs('admin.tien-nghi.*') ? 'text-brand-gold bg-white/5 font-bold' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('admin.tien-nghi.*') ? 'bg-brand-gold shadow-[0_0_5px_#c5a47e]' : 'bg-gray-700' }}"></span>
                    Tiện nghi
                </a>
            </div>
        </div>

        <a href="{{ route('admin.khuyen-mai') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.khuyen-mai.*') ? 'bg-gray-800 text-brand-gold shadow-md border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-tags w-6 text-center text-lg {{ request()->routeIs('admin.khuyen-mai.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3 tracking-wide">Khuyến mãi</span>
        </a>

        <div class="pt-8 pb-3 px-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-800 pb-2">Hệ thống</p>
        </div>

        <a href="{{ route('admin.feedbacks.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.feedbacks.*') ? 'bg-gray-800 text-brand-gold shadow-md border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-inbox w-6 text-center text-lg {{ request()->routeIs('admin.feedbacks.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3 tracking-wide">Phản hồi</span>
            {{-- THÊM HIỂN THỊ SỐ LƯỢNG PHẢN HỒI CHƯA XỬ LÝ TẠI ĐÂY --}}
            @if(isset($pendingFeedbacksCount) && $pendingFeedbacksCount > 0)
                <span class="ml-auto bg-red-600 text-white py-0.5 px-2 rounded-md text-[10px] font-bold shadow-sm animate-pulse">{{ $pendingFeedbacksCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.users') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-brand-gold shadow-md border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-users w-6 text-center text-lg {{ request()->routeIs('admin.users.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3 tracking-wide">Người dùng</span>
        </a>

        <a href="{{ route('admin.hoa-don') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.hoa-don.*') ? 'bg-gray-800 text-brand-gold shadow-md border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-chart-pie w-6 text-center text-lg {{ request()->routeIs('admin.hoa-don.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3 tracking-wide">Doanh thu</span>
        </a>

    </nav>

    <div class="p-4 border-t border-gray-800 bg-gray-900">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brand-gold to-yellow-200 text-gray-900 flex items-center justify-center font-bold font-serif text-lg shadow-lg">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate font-serif">
                    {{ Auth::user()->name ?? 'Administrator' }}
                </p>
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <p class="text-xs text-gray-400 truncate">Online</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-red-400 hover:bg-gray-800 transition-all" title="Đăng xuất">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>