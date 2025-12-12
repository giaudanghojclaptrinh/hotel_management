<nav class="main-nav">
    <a href="{{ route('home') }}" 
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
    
    <a href="#" class="nav-link">
        Liên hệ
    </a>
</nav>