@extends('layouts.app')
@section('title', 'Đăng nhập hệ thống')

@vite(['resources/css/login.css', 'resources/js/login.js'])

@section('content')
<div class="login-wrapper">
    <!-- Background (Hình nền & Lớp phủ) -->
    <div class="login-bg-container">
        <img src="{{ asset('uploads/home/home.png') }}" 
             onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop'" 
             alt="Background" 
             class="login-bg-image">
        <div class="login-bg-overlay"></div>
    </div>

    <!-- Form Container (Khung đăng nhập) -->
    <div class="login-container animate-fade-in-up">
        
        <!-- Header -->
        <div class="login-header">
            <div class="login-icon-wrapper">
                <i class="fa-solid fa-right-to-bracket"></i>
            </div>
            <h2 class="login-title">Chào mừng trở lại</h2>
            <p class="login-subtitle">
                Chưa có tài khoản?
                <a href="{{ route('register') }}" class="login-link">Đăng ký thành viên mới</a>
            </p>
        </div>

        <!-- Form Box -->
        <div class="login-card">
            <form class="login-form-content" action="{{ route('login') }}" method="POST" id="login-form">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-regular fa-envelope"></i></div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="form-input @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="vidu@gmail.com">
                    </div>
                    @error('email')
                        <p class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-solid fa-lock"></i></div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="form-input @error('password') is-invalid @enderror"
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Options (Remember me & Forgot pass) -->
                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }} class="checkbox-custom">
                        <label for="remember" class="checkbox-label">Ghi nhớ đăng nhập</label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="forgot-wrapper">
                            <a href="{{ route('password.request') }}" class="forgot-link">Quên mật khẩu?</a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="form-action">
                    <button type="submit" class="btn-submit group" id="btn-login">
                        <span class="btn-text">ĐĂNG NHẬP NGAY</span>
                        <span class="btn-icon-wrapper">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        </span>
                        <!-- Loading Spinner (Ẩn mặc định) -->
                        <span class="btn-loader hidden">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="divider-wrapper">
                <div class="divider-line"></div>
                <div class="divider-text-wrapper">
                    <span class="divider-text">Hoặc tiếp tục với</span>
                </div>
            </div>
            <!-- đăng nhập bằng google -->
            <div class="social-grid single-item">
                    <a href="{{ route('google.login') }}" class="btn-social google">
                        <i class="fa-brands fa-google"></i> Đăng nhập bằng Google
                    </a>
            </div>
        </div>
        
        <p class="footer-text">
            &copy; {{ date('Y') }} Luxury Stay. Bảo mật tuyệt đối thông tin khách hàng.
        </p>
    </div>
</div>
@endsection