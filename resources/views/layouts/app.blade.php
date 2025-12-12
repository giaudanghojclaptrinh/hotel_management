<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Luxury Stay') - Hệ thống đặt phòng đẳng cấp</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (CDN) - Vẫn giữ để dùng cho các trang con nếu cần -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- GỌI ASSETS QUA VITE -->
    <!-- Đã bao gồm config màu, css tùy chỉnh và js logic -->
    @vite([
        'resources/js/tailwind-config.js',

        // Centralized client assets (single manifest files)
        //client
                //css
                'resources/css/login.css',
                'resources/css/register.css',
                'resources/css/password.css',
                'resources/css/client/home.css',
                'resources/css/client/rooms.css',
                'resources/css/client/profile.css',
                'resources/css/client/booking.css',
                'resources/css/client/about.css',
                'resources/css/client/contact.css',
                'resources/css/client/promo.css',
                'resources/css/client/notifications.css',

                //js
                'resources/js/login.js',
                'resources/js/register.js',
                'resources/js/password.js',
                'resources/js/client/home.js',
                'resources/js/client/rooms.js',
                'resources/js/client/profile.js',
                'resources/js/client/booking.js',
                'resources/js/client/about.js',
                'resources/js/client/contact.js',
                'resources/js/client/promo.js',
                'resources/js/client/notifications.js',
        
    ])

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>

    @include('layouts.header')
    <!-- MAIN CONTENT -->
    <!-- Sử dụng class 'main-content' thay vì các class Tailwind trực tiếp -->
    <main class="main-content">
        
        <!-- Flash Messages (Thông báo) -->
        <!-- Code HTML giờ đây rất sạch sẽ nhờ sử dụng class từ client.css -->
        @if(session('success') || session('error'))
            <div class="flash-container" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                
                @if(session('success'))
                    <div class="flash-success">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-check flash-icon-success"></i>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="btn-close-success"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flash-error">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-exclamation flash-icon-error"></i>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="btn-close-error"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

            </div>
        @endif

        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>