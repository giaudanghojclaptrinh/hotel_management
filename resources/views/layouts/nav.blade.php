<nav class="hidden md:flex space-x-8">
    <!-- 1. Trang chủ -->
    <!-- Kiểm tra route hiện tại để highlight menu -->
    <a href="{{ route('home') }}" 
       class="text-sm font-medium uppercase tracking-wider transition py-2 border-b-2 hover:text-brand-gold hover:border-brand-gold
       {{ request()->routeIs('trang_chu') ? 'text-brand-gold border-brand-gold' : 'text-gray-600 border-transparent' }}">
        Trang chủ
    </a>

    <!-- 2. Phòng & Suites (Danh sách phòng công khai) -->
    <!-- Link này trỏ về route 'phong' (public) thay vì 'dat-phong' (private) -->
    <a href="{{ route('phong') }}" 
       class="text-sm font-medium uppercase tracking-wider transition py-2 border-b-2 hover:text-brand-gold hover:border-brand-gold
       {{ request()->routeIs('phong*') ? 'text-brand-gold border-brand-gold' : 'text-gray-600 border-transparent' }}">
        Phòng & Suites
    </a>

    <!-- 3. Ưu đãi (Khuyến mãi) -->
    <a href="{{ route('khuyen-mai') }}" 
       class="text-sm font-medium uppercase tracking-wider transition py-2 border-b-2 hover:text-brand-gold hover:border-brand-gold
       {{ request()->routeIs('khuyen-mai*') ? 'text-brand-gold border-brand-gold' : 'text-gray-600 border-transparent' }}">
        Ưu đãi
    </a>

    <!-- 4. Giới thiệu / Liên hệ (Tĩnh) -->
    <a href="#" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
        Về chúng tôi
    </a>
    
    <a href="#" class="text-sm font-medium uppercase tracking-wider text-gray-600 hover:text-brand-gold transition py-2 border-b-2 border-transparent hover:border-brand-gold">
        Liên hệ
    </a>
</nav>