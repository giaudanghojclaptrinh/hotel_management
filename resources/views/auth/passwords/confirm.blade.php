@extends('layouts.app')
@section('title', 'Xác nhận bảo mật')

<!-- Cập nhật: Sử dụng CSS và JS riêng cho các trang Password -->
@vite(['resources/css/password.css', 'resources/js/password.js'])

@section('content')
<div class="login-wrapper">
    <!-- Background -->
    <div class="login-bg-container">
        <img src="{{ asset('uploads/home/home.png') }}" 
             onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop'" 
             alt="Background" 
             class="login-bg-image">
        <div class="login-bg-overlay"></div>
    </div>

    <!-- Form Container -->
    <div class="login-container animate-fade-in-up">
        
        <!-- Header -->
        <div class="login-header">
            <div class="login-icon-wrapper">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2 class="login-title">Xác nhận bảo mật</h2>
            <p class="login-subtitle">
                Vui lòng xác nhận mật khẩu của bạn trước khi tiếp tục thao tác này.
            </p>
        </div>

        <!-- Form Box -->
        <div class="login-card">
            <form class="login-form-content" method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu hiện tại</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-solid fa-lock"></i></div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="form-input @error('password') is-invalid @enderror"
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-action mt-4">
                    <button type="submit" class="btn-submit group">
                        <span class="btn-text">XÁC NHẬN NGAY</span>
                        <span class="btn-icon-wrapper">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </button>
                </div>

                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <div class="form-footer-link text-center mt-3">
                        <a href="{{ route('password.request') }}" class="login-link text-sm">
                            Quên mật khẩu?
                        </a>
                    </div>
                @endif
            </form>
        </div>
        
        <p class="footer-text">
            &copy; {{ date('Y') }} Luxury Stay. Bảo mật tuyệt đối.
        </p>
    </div>
</div>
@endsection