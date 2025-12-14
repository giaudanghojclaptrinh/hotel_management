@extends('layouts.app')
@section('title', 'Khôi phục mật khẩu')

@vite(['resources/css/login.css', 'resources/js/login.js'])

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
                <i class="fa-solid fa-key"></i>
            </div>
            <h2 class="login-title">Quên mật khẩu?</h2>
            <p class="login-subtitle">
                Nhập email của bạn và chúng tôi sẽ gửi liên kết để đặt lại mật khẩu mới.
            </p>
        </div>

        <!-- Form Box -->
        <div class="login-card">
            @if (session('status'))
                <div class="alert alert-success mb-4 text-center p-3 rounded" style="background: rgba(16, 185, 129, 0.1); color: #047857; font-size: 0.9rem;">
                    <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
                </div>
            @endif

            <form class="login-form-content" method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Địa chỉ Email đã đăng ký</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-regular fa-envelope"></i></div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               class="form-input @error('email') is-invalid @enderror"
                               placeholder="vidu@gmail.com">
                    </div>
                    @error('email')
                        <p class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-action mt-4">
                    <button type="submit" class="btn-submit group">
                        <span class="btn-text">GỬI LIÊN KẾT</span>
                        <span class="btn-icon-wrapper">
                            <i class="fa-regular fa-paper-plane"></i>
                        </span>
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="divider-wrapper">
                    <div class="divider-line"></div>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="login-link">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại Đăng nhập
                    </a>
                </div>
            </form>
        </div>
        
        <p class="footer-text">
            &copy; {{ date('Y') }} Luxury Stay. Hỗ trợ khách hàng 24/7.
        </p>
    </div>
</div>
@endsection