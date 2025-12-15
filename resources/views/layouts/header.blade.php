<header x-data="{ 
        mobileMenuOpen: false, 
        userDropdownOpen: false, 
        unreadCount: 0,
        fetchCount() {
            axios.get('{{ route('notifications.count') }}')
                .then(response => {
                    this.unreadCount = response.data.count;
                })
                .catch(error => {
                    console.error('Error fetching unread count:', error);
                    this.unreadCount = 0;
                });
        }
    }" 
    x-init="@auth fetchCount(); setInterval(() => fetchCount(), 60000); @endauth" 
    class="header-wrapper">
    <div class="header-container">
        <div class="header-inner">
            
            <!-- 1. Logo -->
            <a href="{{ route('trang_chu') }}" class="logo-link group">
                <span class="logo-icon">
                    <i class="fa-solid fa-crown"></i>
                </span>
                <div class="logo-text-wrapper">
                    <span class="logo-text">Luxury<span>Stay</span></span>
                </div>
            </a>

            <!-- 2. Desktop Menu -->
            @include('layouts.nav')

            <!-- 3. Actions (Login/User/Notifications) -->
            <div class="header-actions">
                @auth
                    <!-- [MỚI] Notification Bell -->
                    <div class="relative mr-4 lg:mr-6">
                        <a href="{{ route('notifications.index') }}" class="notification-icon-wrapper relative">
                            <i class="fa-solid fa-bell text-xl transition-colors"
                               :class="unreadCount > 0 ? 'notification-active' : 'notification-inactive'"></i>
                            <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 bg-red-600 text-white py-0.5 px-2 rounded-md text-[10px] font-bold shadow-sm animate-pulse" x-text="unreadCount"></span>
                        </a>
                    </div>
                    <!-- Kết thúc Notification Bell -->


                    <!-- Đã đăng nhập -->
                    <div class="relative">
                        <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="user-btn">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-angle-down text-xs"></i>
                            <div class="user-avatar">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userDropdownOpen" x-transition x-cloak class="dropdown-menu">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item dropdown-item-danger">
                                    <i class="fa-solid fa-gauge"></i> Trang quản trị
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fa-solid fa-id-card"></i> Hồ sơ cá nhân
                            </a>
                            <a href="{{ route('bookings.history') }}" class="dropdown-item">
                                <i class="fa-solid fa-clock-rotate-left"></i> Lịch sử đặt phòng
                            </a>
                            
                            {{-- [MỚI] Link tới trang thông báo trong dropdown --}}
                            <a href="{{ route('notifications.index') }}" class="dropdown-item flex justify-between items-center">
                                <span><i class="fa-solid fa-bell"></i> Thông báo</span>
                                <span x-show="unreadCount > 0" class="bg-red-600 text-white py-0.5 px-2 rounded-md text-[10px] font-bold shadow-sm animate-pulse" x-text="unreadCount"></span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left dropdown-item text-red-600">
                                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Chưa đăng nhập -->
                    <a href="{{ route('login') }}" class="btn-login">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn-register">Đăng ký ngay</a>
                @endauth
            </div>

            <!-- 4. Mobile Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="mobile-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition x-cloak class="mobile-menu-wrapper">
        <div class="mobile-menu-container">
            <a href="{{ route('trang_chu') }}" class="mobile-link">Trang chủ</a>
            <a href="{{ route('phong.danh-sach') }}" class="mobile-link">Phòng & Suites</a>
            <a href="{{ route('khuyen-mai') }}" class="mobile-link">Ưu đãi</a>
            
                    @auth
                <div class="mobile-user-section">
                    <p class="mobile-greeting">Xin chào, <strong>{{ Auth::user()->name }}</strong></p>
                    <a href="{{ route('profile.edit') }}" class="mobile-user-link">Hồ sơ</a>
                    <a href="{{ route('bookings.history') }}" class="mobile-user-link">Lịch sử</a>
                    <a href="{{ route('notifications.index') }}" class="mobile-user-link flex items-center justify-between">
                        Thông báo
                        <span x-show="unreadCount > 0" class="bg-red-600 text-white py-0.5 px-2 rounded-md text-[10px] font-bold shadow-sm animate-pulse" x-text="unreadCount"></span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="mobile-logout-btn">Đăng xuất</button>
                    </form>
                </div>
            @else
                <div class="mobile-auth-grid">
                    <a href="{{ route('login') }}" class="mobile-btn-login">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="mobile-btn-register">Đăng ký</a>
                </div>
            @endauth
        </div>
    </div>
</header> {{-- [CẬP NHẬT] Link Thông báo (Sử dụng x-show trực tiếp trên span) --}}