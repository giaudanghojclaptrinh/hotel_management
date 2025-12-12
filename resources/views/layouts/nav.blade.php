<nav class="main-nav">
    <a href="{{ route('trang_chu') }}" 
        class="nav-link {{ request()->routeIs('trang_chu') ? 'active' : '' }}">
        Trang chủ
    </a>

    <a href="{{ route('phong.danh-sach') }}" 
        class="nav-link {{ request()->routeIs('phong*') ? 'active' : '' }}">
        Phòng & Suites
    </a>

    <a href="{{ route('ve-chung-toi') }}" 
        class="nav-link {{ request()->routeIs('ve-chung-toi') ? 'active' : '' }}">
        Về chúng tôi
    </a>
    
    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
        Liên hệ
    </a>
</nav>