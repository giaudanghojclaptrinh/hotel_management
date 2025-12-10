<div class="flex h-screen overflow-hidden">
    
    <!-- A. SIDEBAR -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-brand-900 text-white transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-gray-800 bg-brand-950">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <i class="fa-solid fa-crown text-brand-gold text-2xl"></i>
                <span class="text-xl font-bold tracking-wide">Luxury<span class="text-brand-gold"> Admin </span></span>
            </a>
        </div>

        <!-- Menu Items -->
        <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 hover:text-brand-gold transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-brand-gold' : 'text-gray-300' }}">
                <i class="fa-solid fa-gauge-high w-6 text-lg"></i>
                <span class="ml-3">Tổng quan</span>
            </a>

            <!-- Quản lý Đặt phòng (Table: dat_phongs) -->
            <p class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Vận hành</p>
            <a href="{{ route('admin.dat-phong') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-gold transition">
                <i class="fa-solid fa-calendar-check w-6 text-lg"></i>
                <span class="ml-3">Đặt phòng</span>
                <!-- Badge thông báo giả lập -->
                <span class="ml-auto bg-red-500 text-white py-0.5 px-2 rounded-full text-xs">3</span>
            </a>
            
            <!-- Quản lý Phòng (Table: phongs & loai_phongs) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center w-full px-4 py-3 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-gold transition justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-door-open w-6 text-lg"></i>
                        <span class="ml-3">Phòng & Giá</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                    <a href="{{ route('admin.phong') }}" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded hover:bg-gray-700">Danh sách phòng</a>
                    <a href="{{ route('admin.loai-phong') }}" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded hover:bg-gray-700">Loại phòng</a>
                </div>
            </div>

             <a href="{{ route('admin.tien-nghi') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-gold transition">
                <i class="fa-solid fa-ticket w-6 text-lg"></i>
                <span class="ml-3">Tiện nghi</span>
            </a>
            

            <!-- Quản lý Khuyến mãi (Table: khuyen_mais) -->
            <a href="{{ route('admin.khuyen-mai') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-gold transition">
                <i class="fa-solid fa-ticket w-6 text-lg"></i>
                <span class="ml-3">Khuyến mãi</span>
            </a>

            <!-- Quản lý Người dùng (Table: users) -->
            <p class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hệ thống</p>
            <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-gold transition">
                <i class="fa-solid fa-users w-6 text-lg"></i>
                <span class="ml-3">Người dùng</span>
            </a>
            
            <!-- Doanh thu / Hóa đơn (Table: hoa_dons) -->
            <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-gold transition">
                <i class="fa-solid fa-chart-line w-6 text-lg"></i>
                <span class="ml-3">Báo cáo doanh thu</span>
            </a>
        </nav>

        <!-- User Info (Bottom Sidebar) -->
        <div class="border-t border-gray-800 p-4">
            <div class="flex items-center">
                <div class="w-9 h-9 rounded-full bg-brand-gold flex items-center justify-center text-brand-900 font-bold">A</div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-xs text-gray-400 hover:text-white">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>