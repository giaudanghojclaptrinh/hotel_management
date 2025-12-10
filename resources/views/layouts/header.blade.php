<header x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="header-wrapper">
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
            <nav class="nav-menu">
                <a href="{{ route('trang_chu') }}" class="nav-link {{ request()->routeIs('trang_chu') ? 'active' : '' }}">
                    Trang chủ
                </a>
                <a href="{{ route('phong') }}" class="nav-link {{ request()->routeIs('phong*') ? 'active' : '' }}">
                    Phòng & Suites
                </a>
                <a href="{{ route('khuyen-mai') }}" class="nav-link {{ request()->routeIs('khuyen-mai*') ? 'active' : '' }}">
                    Ưu đãi
                </a>
                <a href="#" class="nav-link">Về chúng tôi</a>
                <a href="#" class="nav-link">Liên hệ</a>
            </nav>

            <!-- 3. Actions (Login/User) -->
            <div class="header-actions">
                @auth
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
            <a href="{{ route('phong') }}" class="mobile-link">Phòng & Suites</a>
            <a href="{{ route('khuyen-mai') }}" class="mobile-link">Ưu đãi</a>
            
            @auth
                <div class="mobile-user-section">
                    <p class="mobile-greeting">Xin chào, <strong>{{ Auth::user()->name }}</strong></p>
                    <a href="{{ route('profile.edit') }}" class="mobile-user-link">Hồ sơ</a>
                    <a href="{{ route('bookings.history') }}" class="mobile-user-link">Lịch sử</a>
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
</header>