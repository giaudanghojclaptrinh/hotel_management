<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - Luxury Stay</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS + Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Be Vietnam Pro"', 'sans-serif'] },
                    colors: {
                        brand: {
                            900: '#111827', // Màu nền sidebar (Đen xanh)
                            800: '#1f2937',
                            gold: '#c5a47e', // Màu điểm nhấn
                        }
                    }
                }
            }
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800" x-data="{ sidebarOpen: false }">

    @include('admin.layouts.sidebar')

        <!-- B. MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-6 lg:px-8">
                <!-- Mobile Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>

                <!-- Page Title (Breadcrumb simplified) -->
                <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>

                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <button class="relative text-gray-500 hover:text-brand-900 transition">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>
                    <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-brand-gold font-medium">
                        Về trang chủ <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </header>

            <!-- Content Body -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 lg:p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                        <div class="flex items-center"><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
        
        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>
    </div>
</body>
</html>