<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản trị hệ thống') - Luxury Stay Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                            900: '#111827', // Đen nền chính (Gray-900)
                            800: '#1f2937', // Đen nền phụ (Gray-800)
                            gold: '#c5a47e', // Vàng Luxury
                            'gold-hover': '#b08d55', 
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Tùy chỉnh Scrollbar Luxury */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #c5a47e; }

        /* Tiện ích nền tối */
        .lux-card { background-color: #111827; border: 1px solid #374151; }
        
        /* Hiệu ứng focus cho input toàn trang */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #c5a47e !important;
            --tw-ring-color: rgba(197, 164, 126, 0.5) !important;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-300 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        @include('admin.layouts.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden relative bg-gray-900">
            
            <header class="h-16 flex items-center justify-between px-6 border-b border-gray-800 bg-gray-900 z-20">
                
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-400 hover:text-brand-gold mr-4 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-white font-serif tracking-wide lg:hidden">
                        @yield('header', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-6 ml-auto">
                    
                    <a href="{{ route('home') }}" target="_blank" class="hidden md:flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-brand-gold transition-colors group">
                        <span>Trang chủ</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                    </a>

                    <div class="h-6 w-px bg-gray-700 hidden md:block"></div>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-3 cursor-pointer focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-brand-gold leading-tight">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold mt-0.5">Administrator</p>
                            </div>
                            <div class="w-9 h-9 rounded-lg bg-gray-800 border border-brand-gold/50 text-brand-gold flex items-center justify-center font-bold font-serif shadow-sm hover:bg-brand-gold hover:text-gray-900 transition-colors">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-xl shadow-xl py-1 z-50 text-sm"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             style="display: none;">
                            
                            <div class="px-4 py-3 border-b border-gray-700">
                                <p class="text-white font-medium truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-gray-500 text-xs truncate">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            
                            <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                <i class="fa-solid fa-user-gear mr-2 text-gray-500"></i> Hồ sơ
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-gray-700 hover:text-red-300 transition-colors">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900 p-6 scroll-smooth">
                
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="mb-6 bg-gray-800 border-l-4 border-green-500 p-4 rounded-lg shadow-lg flex justify-between items-center max-w-7xl mx-auto">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-500/20 text-green-500 rounded-full p-1.5"><i class="fa-solid fa-check text-sm"></i></div>
                            <div>
                                <p class="font-bold text-green-500 text-sm">Thành công!</p>
                                <p class="text-gray-300 text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-gray-500 hover:text-white transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" 
                         class="mb-6 bg-gray-800 border-l-4 border-red-500 p-4 rounded-lg shadow-lg flex justify-between items-center max-w-7xl mx-auto">
                        <div class="flex items-center gap-3">
                            <div class="bg-red-500/20 text-red-500 rounded-full p-1.5"><i class="fa-solid fa-exclamation text-sm"></i></div>
                            <div>
                                <p class="font-bold text-red-500 text-sm">Lỗi!</p>
                                <p class="text-gray-300 text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-gray-500 hover:text-white transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @yield('content')
                
                <div class="h-10"></div>
            </main>
        </div>
        
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/80 z-40 lg:hidden backdrop-blur-sm"></div>
    </div>
    
    <script>
        // Cấu hình Axios
        if (typeof axios !== 'undefined') {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        }

        // Logic Confirm Delete (Global)
        document.addEventListener('DOMContentLoaded', () => {
            // Tự động tắt thông báo sau 5s (fallback nếu Alpine chưa chạy kịp)
            setTimeout(() => {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(el => el.style.display = 'none');
            }, 5000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>