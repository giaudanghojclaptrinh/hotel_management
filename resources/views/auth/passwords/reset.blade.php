@extends('layouts.app')
@section('title', 'Cập nhật mật khẩu')

<!-- Cập nhật: Sử dụng CSS và JS riêng cho các trang Password -->
@vite(['resources/css/password.css', 'resources/js/password.js'])

@section('content')
<style>
    /* Fix lỗi icon đè chữ */
    .form-input {
        padding-left: 50px !important; /* Đẩy nội dung sang phải để không bị icon che */
    }
    .input-icon {
        pointer-events: none; /* Giúp click vào icon vẫn focus được input */
    }
</style>

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
                <i class="fa-solid fa-lock-open"></i>
            </div>
            <h2 class="login-title">Đặt lại mật khẩu</h2>
            <p class="login-subtitle">
                Vui lòng nhập mật khẩu mới cho tài khoản của bạn.
            </p>
        </div>

        <!-- Form Box -->
        <div class="login-card">
            <form class="login-form-content" method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email (Readonly) -->
                <div class="form-group">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-regular fa-envelope"></i></div>
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly
                               class="form-input readonly @error('email') is-invalid @enderror"
                               style="background-color: rgba(255, 255, 255, 0.05); cursor: not-allowed;">
                    </div>
                    @error('email')
                        <p class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-solid fa-lock"></i></div>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="form-input @error('password') is-invalid @enderror"
                               placeholder="Nhập mật khẩu mới">
                    </div>
                    @error('password')
                        <p class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password-confirm" class="form-label">Xác nhận mật khẩu</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-solid fa-check-double"></i></div>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="form-input"
                               placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-action mt-4">
                    <button type="submit" class="btn-submit group">
                        <span class="btn-text">ĐỔI MẬT KHẨU</span>
                        <span class="btn-icon-wrapper">
                            <i class="fa-solid fa-rotate"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
        
        <p class="footer-text">
            &copy; {{ date('Y') }} Luxury Stay. Bảo mật tuyệt đối.
        </p>
    </div>
</div>
@endsection