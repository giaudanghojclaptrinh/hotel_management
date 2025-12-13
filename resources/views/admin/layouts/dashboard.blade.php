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
                            900: '#111827', // Xanh đen
                            800: '#1f2937', 
                            gold: '#c5a47e', // Vàng Luxury
                            hover: '#b08d55', 
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Scrollbar đẹp */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c5a47e; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #b08d55; }

        /* Active Menu Style */
        .nav-active {
            background-color: rgba(31, 41, 55, 1);
            color: #c5a47e;
            border-left: 4px solid #c5a47e;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        @include('admin.layouts.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <header class="bg-white h-16 flex items-center justify-between px-6 shadow-sm border-b border-gray-100 z-20">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-brand-gold mr-4">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-brand-900 font-serif tracking-wide">
                        @yield('header', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" target="_blank" class="hidden md:flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-brand-gold transition-colors group">
                        <span>Trở về trang chủ</span>
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                    <div class="flex items-center gap-3 cursor-pointer">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-brand-900 leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-brand-gold font-semibold mt-0.5">Admin</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-brand-900 border-2 border-brand-gold text-brand-gold flex items-center justify-center font-bold font-serif shadow-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                         class="mb-6 bg-white border-l-4 border-green-500 p-4 rounded shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-100 text-green-600 rounded-full p-1"><i class="fa-solid fa-check"></i></div>
                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-white border-l-4 border-red-500 p-4 rounded shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="bg-red-100 text-red-600 rounded-full p-1"><i class="fa-solid fa-exclamation"></i></div>
                            <span class="text-red-800 font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @yield('content')
                
                <div class="h-10"></div>
            </main>
        </div>
        
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-brand-900/50 z-40 lg:hidden backdrop-blur-sm"></div>
    </div>
    
    <script>
        // Cấu hình Axios
        if (typeof axios !== 'undefined') {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        }

        // Logic Confirm Delete
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-confirm]').forEach(el => {
                el.addEventListener('click', e => {
                    if (!confirm(el.getAttribute('data-confirm'))) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>