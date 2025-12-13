<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" 
       class="fixed inset-y-0 left-0 z-50 w-64 bg-brand-900 text-white transition-transform duration-300 lg:static lg:inset-0 lg:translate-x-0 shadow-xl flex flex-col border-r border-gray-800">
    
    <div class="flex items-center justify-center h-16 border-b border-gray-800 bg-[#0f1523]">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-full border border-brand-gold flex items-center justify-center text-brand-gold group-hover:bg-brand-gold group-hover:text-brand-900 transition-colors">
                <i class="fa-solid fa-crown text-sm"></i>
            </div>
            <span class="text-xl font-bold font-serif tracking-wide text-white">
                Luxury<span class="text-brand-gold">Stay</span>
            </span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-brand-gold border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-gauge-high w-5 text-center {{ request()->routeIs('admin.dashboard') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3">Tổng quan</span>
        </a>

        <div class="pt-6 pb-2 px-4">
            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Vận hành</p>
        </div>

        <a href="{{ route('admin.dat-phong') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.dat-phong.*') ? 'bg-gray-800 text-brand-gold border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-calendar-check w-5 text-center {{ request()->routeIs('admin.dat-phong.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3">Đặt phòng</span>
            <span class="ml-auto bg-brand-gold text-brand-900 py-0.5 px-2 rounded text-[10px] font-bold">New</span>
        </a>

        <div x-data="{ open: {{ request()->routeIs('admin.phong.*') || request()->routeIs('admin.loai-phong.*') || request()->routeIs('admin.tien-nghi.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all group">
                <div class="flex items-center">
                    <i class="fa-solid fa-door-open w-5 text-center text-gray-500 group-hover:text-brand-gold"></i>
                    <span class="ml-3">Phòng nghỉ</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180 text-brand-gold' : ''"></i>
            </button>
            
            <div x-show="open" x-collapse class="space-y-1 mt-1 bg-[#0f1523] rounded-lg overflow-hidden">
                <a href="{{ route('admin.phong') }}" 
                   class="flex items-center pl-11 pr-4 py-2 text-sm transition-colors {{ request()->routeIs('admin.phong.*') ? 'text-brand-gold bg-white/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                   <span class="w-1.5 h-1.5 rounded-full mr-2 {{ request()->routeIs('admin.phong.*') ? 'bg-brand-gold' : 'bg-gray-600' }}"></span>
                   Danh sách phòng
                </a>
                <a href="{{ route('admin.loai-phong') }}" 
                   class="flex items-center pl-11 pr-4 py-2 text-sm transition-colors {{ request()->routeIs('admin.loai-phong.*') ? 'text-brand-gold bg-white/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                   <span class="w-1.5 h-1.5 rounded-full mr-2 {{ request()->routeIs('admin.loai-phong.*') ? 'bg-brand-gold' : 'bg-gray-600' }}"></span>
                   Loại phòng & Giá
                </a>
                <a href="{{ route('admin.tien-nghi') }}" 
                   class="flex items-center pl-11 pr-4 py-2 text-sm transition-colors {{ request()->routeIs('admin.tien-nghi.*') ? 'text-brand-gold bg-white/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                   <span class="w-1.5 h-1.5 rounded-full mr-2 {{ request()->routeIs('admin.tien-nghi.*') ? 'bg-brand-gold' : 'bg-gray-600' }}"></span>
                   Tiện nghi
                </a>
            </div>
        </div>

        <a href="{{ route('admin.khuyen-mai') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.khuyen-mai.*') ? 'bg-gray-800 text-brand-gold border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-tags w-5 text-center {{ request()->routeIs('admin.khuyen-mai.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3">Khuyến mãi</span>
        </a>

        <div class="pt-6 pb-2 px-4">
            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Hệ thống</p>
        </div>

        <a href="{{ route('admin.users') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-brand-gold border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-users w-5 text-center {{ request()->routeIs('admin.users.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3">Người dùng</span>
        </a>

        <a href="{{ route('admin.hoa-don') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.hoa-don.*') ? 'bg-gray-800 text-brand-gold border-l-4 border-brand-gold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('admin.hoa-don.*') ? 'text-brand-gold' : 'text-gray-500 group-hover:text-brand-gold' }}"></i>
            <span class="ml-3">Doanh thu</span>
        </a>

    </nav>

    <div class="p-4 border-t border-gray-800 bg-[#0f1523]">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <div class="w-9 h-9 rounded-full bg-brand-gold text-brand-900 flex items-center justify-center font-bold text-sm shadow-md">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    {{ Auth::user()->name ?? 'Administrator' }}
                </p>
                <p class="text-xs text-gray-500 truncate">Quản lý hệ thống</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors p-1" title="Đăng xuất">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>