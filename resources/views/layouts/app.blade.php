<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Luxury Stay') - Hệ thống đặt phòng đẳng cấp</title>

    <!-- 1. Fonts: Playfair Display (Sang trọng) & Be Vietnam Pro (Hiện đại) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- 2. Icons: FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Shared components CSS -->
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <!-- 3. Tailwind CSS + Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Be Vietnam Pro"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f4f7f6',
                            800: '#1f2937',
                            900: '#111827',
                            gold: '#c5a47e', // Màu vàng luxury
                            dark: '#1a1a1a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="font-sans text-gray-700 antialiased bg-gray-50 flex flex-col min-h-screen">

    <!-- --- HEADER --- -->
    <header x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" 
            class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                        <span class="w-10 h-10 flex items-center justify-center bg-brand-900 text-brand-gold rounded-full border border-brand-gold group-hover:bg-brand-gold group-hover:text-brand-900 transition duration-500">
                            <i class="fa-solid fa-crown text-xl"></i>
                        </span>
                        <div class="ml-2 flex flex-col">
                            <span class="font-serif text-2xl font-bold text-gray-900 tracking-wide uppercase">Luxury<span class="text-brand-gold">Stay</span></span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu (Dựa trên DB table loai_phongs, khuyen_mais) -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ url('/') }}" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
                        Trang chủ
                    </a>
                    <!-- Link tới danh sách Loại phòng -->
                    <a href="{{ route('loai-phong') }}" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
                        Các hạng phòng
                    </a>

                    <!--link tới danh sách phòng-->
                    <a href="{{ route('phong') }}" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
                        Các phòng
                    </a>

                    <!--link tới danh sách người dùng -->
                    <a href="{{ route('user') }}" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
                        Người dùng
                    </a> 

                    <!-- link tới khuyến mãi -->
                    <a href="{{ route('khuyen-mai') }}" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
                        Khuyến mãi
                    </a>

                    <a href="#" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
                        Giới thiệu
                    </a>
                </nav>

                <!-- Auth / User Action -->
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        <!-- Dropdown User -->
                        <div class="relative">
                            <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="flex items-center space-x-2 text-gray-700 hover:text-brand-gold focus:outline-none">
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-angle-down text-xs"></i>
                                <!-- Avatar giả lập -->
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-300">
                                    <i class="fa-solid fa-user text-gray-500"></i>
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="userDropdownOpen" x-transition.origin.top.right x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 border border-gray-100 ring-1 ring-black ring-opacity-5 z-50">
                                
                                <!-- Kiểm tra Role (Dựa trên cột 'role' trong users table) -->
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold border-b border-gray-100">
                                        <i class="fa-solid fa-gauge-high mr-2"></i> Trang quản trị
                                    </a>
                                @endif

                                {{-- Render profile link only if route exists to avoid RouteNotFoundException --}}
                                @if (Route::has('profile.edit'))
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fa-solid fa-id-card mr-2 text-gray-400"></i> Hồ sơ cá nhân
                                    </a>
                                @endif
                                <!-- Link tới lịch sử đặt phòng (Dựa trên table dat_phongs) -->
                                @if (Route::has('bookings.history'))
                                    <a href="{{ route('bookings.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-400"></i> Lịch sử đặt phòng
                                    </a>
                                @endif
                                
                                <form method="POST" action="{{ route('logout') }}" class="block border-t border-gray-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fa-solid fa-arrow-right-from-bracket mr-2 text-gray-400"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-900 hover:text-brand-gold">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-brand-900 text-brand-gold text-sm font-bold uppercase tracking-wide rounded hover:bg-gray-800 transition shadow-lg">
                            Đặt phòng ngay
                        </a>
                    @endauth
                </div>

                <!-- Mobile Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-900 hover:text-brand-gold p-2">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-white border-t border-gray-100 h-screen">
            <div class="px-4 pt-2 pb-6 space-y-2">
                @auth
                    <div class="px-3 py-3 border-b border-gray-100 mb-2">
                        <p class="text-sm text-gray-500">Xin chào,</p>
                        <p class="font-bold text-lg text-brand-900">{{ Auth::user()->name }}</p>
                    </div>
                @endauth

                <a href="{{ url('/') }}" class="block px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-md">Trang chủ</a>
                <a href="{{ route('loai-phong') }}" class="block px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-md">Các hạng phòng</a>
                {{-- Khuyến mãi (khi có route 'promotions.index' thì mở)
                <a href="{{ route('promotions.index') }}" class="block px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-md">Khuyến mãi</a>
                --}}
                
                @auth
                    @if (Route::has('bookings.history'))
                        <a href="{{ route('bookings.history') }}" class="block px-3 py-3 text-base font-medium text-blue-600 hover:bg-blue-50 rounded-md">Lịch sử đặt phòng</a>
                    @endif
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left block px-3 py-3 text-base font-medium text-red-600 hover:bg-red-50 rounded-md">Đăng xuất</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-4 mt-4 px-3">
                        <a href="{{ route('login') }}" class="text-center py-2 border border-gray-300 rounded text-gray-700 font-semibold">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="text-center py-2 bg-brand-900 text-brand-gold rounded font-semibold">Đăng ký</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- --- MAIN CONTENT --- -->
    <main class="flex-grow pt-20">
        <!-- Flash Messages (Thông báo từ Controller) -->
        @if(session('success') || session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-check text-green-500 mr-3 text-xl"></i>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-600 hover:text-green-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mr-3 text-xl"></i>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <!-- --- FOOTER --- -->
    <footer class="bg-brand-900 text-white mt-20 border-t-4 border-brand-gold">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                         <span class="w-8 h-8 flex items-center justify-center bg-brand-gold text-brand-900 rounded-full">
                            <i class="fa-solid fa-crown text-sm"></i>
                        </span>
                        <span class="font-serif text-2xl font-bold tracking-wide">Luxury<span class="text-brand-gold">Stay</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Nơi giao thoa giữa vẻ đẹp kiến trúc cổ điển và tiện nghi hiện đại. Trải nghiệm kỳ nghỉ khó quên với dịch vụ 5 sao chuẩn quốc tế.
                    </p>
                    <div class="flex space-x-4 pt-2">
                        <a href="#" class="w-10 h-10 rounded bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-brand-900 transition"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-brand-900 transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-brand-900 transition"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Links (Dựa trên tables) -->
                <div>
                    <h3 class="font-serif text-xl font-bold text-brand-gold mb-6">Khám phá</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#" class="hover:text-white transition flex items-center"><i class="fa-solid fa-angle-right text-xs mr-2"></i> Về chúng tôi</a></li>
                        <li><a href="{{ route('loai-phong') }}" class="hover:text-white transition flex items-center"><i class="fa-solid fa-angle-right text-xs mr-2"></i> Các hạng phòng</a></li>
                        {{-- <li><a href="{{ route('promotions.index') }}" class="hover:text-white transition flex items-center"><i class="fa-solid fa-angle-right text-xs mr-2"></i> Ưu đãi đặc biệt</a></li> --}}
                        <li><a href="#" class="hover:text-white transition flex items-center"><i class="fa-solid fa-angle-right text-xs mr-2"></i> Ưu đãi đặc biệt</a></li>
                        <li><a href="#" class="hover:text-white transition flex items-center"><i class="fa-solid fa-angle-right text-xs mr-2"></i> Thư viện ảnh</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="font-serif text-xl font-bold text-brand-gold mb-6">Liên hệ</h3>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-start">
                            <i class="fa-solid fa-location-dot mt-1.5 mr-3 text-brand-gold"></i>
                            <span>123 Đường Hạ Long, Phường Bãi Cháy, TP. Hạ Long, Quảng Ninh</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-phone mr-3 text-brand-gold"></i>
                            <span class="font-semibold text-white">+84 90 123 4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-envelope mr-3 text-brand-gold"></i>
                            <span>booking@luxurystay.com</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="font-serif text-xl font-bold text-brand-gold mb-6">Bản tin</h3>
                    <p class="text-gray-400 text-sm mb-4">Đăng ký để nhận mã khuyến mãi giảm giá 10% cho lần đặt đầu tiên.</p>
                    <form class="flex flex-col gap-2">
                        <input type="email" placeholder="Email của bạn..." class="bg-gray-800 border-none text-white px-4 py-3 rounded focus:ring-1 focus:ring-brand-gold outline-none text-sm placeholder-gray-500">
                        <button type="submit" class="bg-brand-gold text-brand-900 font-bold py-3 rounded hover:bg-white transition uppercase text-xs tracking-widest">Đăng ký</button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; 2025 Luxury Stay Hotel. All rights reserved.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition">Chính sách bảo mật</a>
                    <a href="#" class="hover:text-white transition">Điều khoản sử dụng</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

<!-- Removed duplicate self-extend and sample section to prevent recursive rendering -->