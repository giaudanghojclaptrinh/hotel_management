@extends('layouts.app')
@section('title', 'Đăng ký thành viên')


{{-- @vite(['resources/css/register.css']) --}}
{{-- Ghi chú: `register.css` đã được include chung trong `layouts.app` (Vite manifest).
    Đã comment lại để tránh tải nhiều lần trên cùng một trang. Nếu muốn tách riêng,
    bỏ comment để kích hoạt lại. --}}

@section('content')
<div class="register-wrapper">
    
    <!-- Background -->
    <div class="register-bg-container">
        {{-- Sử dụng ảnh local của bạn, có fallback ảnh mạng --}}
        <img src="{{ asset('uploads/home/home.png') }}" 
             onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop'" 
             alt="Background" 
             class="register-bg-image">
        <div class="register-bg-overlay"></div>
    </div>

    <!-- Container -->
    <div class="register-container animate-fade-in-up">
        
        <!-- Header -->
        <div class="register-header">
            <div class="register-icon-wrapper">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 class="register-title">Tạo tài khoản mới</h2>
            <p class="register-subtitle">
                Đã là thành viên?
                <a href="{{ route('login') }}" class="register-link">Đăng nhập ngay</a>
            </p>
        </div>

        <!-- Form Box -->
        <div class="register-card">
            <!-- Dải màu trang trí trên cùng -->
            <div class="card-deco-line"></div>

            <form class="register-form-content" action="{{ route('register') }}" method="POST" id="register-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Họ và tên</label>
                    <div class="input-group">
                        <div class="input-icon"><i class="fa-regular fa-user"></i></div>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                               class="form-input @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Ví dụ: Nguyễn Văn A">
                    </div>
                    @error('name')
                        <p class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email đăng nhập</label>
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
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="form-input @error('password') is-invalid @enderror"
                               placeholder="Tối thiểu 8 ký tự">
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
                        <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="form-input"
                               placeholder="Nhập lại mật khẩu">
                    </div>
                </div>

                <!-- Terms -->
                <div class="form-check-group">
                    <div class="checkbox-wrapper">
                        <input id="terms" name="terms" type="checkbox" required class="checkbox-custom">
                        <label for="terms" class="checkbox-label">
                            Tôi đồng ý với <a href="#" class="link-term">Điều khoản dịch vụ</a> và <a href="#" class="link-term">Chính sách bảo mật</a>.
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-action">
                    <button type="submit" class="btn-submit group" id="btn-register">
                        <span class="btn-icon-wrapper">
                            <i class="fa-solid fa-user-plus"></i>
                        </span>
                        <span class="btn-text">ĐĂNG KÝ TÀI KHOẢN</span>
                        <!-- Loading Spinner -->
                        <span class="btn-loader hidden">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- @vite(['resources/js/register.js']) --}}
{{-- Ghi chú: `register.js` đã được include trong `layouts.app` khi build assets.
    Comment để tránh tải trùng; giữ dòng comment để dễ khôi phục. --}}
@endsection