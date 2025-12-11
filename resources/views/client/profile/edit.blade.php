@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')

<!-- Thêm dòng này để gọi đúng file CSS cho trang Profile -->


@section('content')
<div class="profile-page-wrapper">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('trang_chu') }}">Trang chủ</a>
            <span class="separator">/</span>
            <span class="active">Hồ sơ cá nhân</span>
        </nav>

        <!-- Thông báo đặc biệt (nếu có lỗi từ session) -->
        @if(session('error'))
            <div class="alert-box alert-error">
                <div class="alert-icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="alert-content">
                    <span class="alert-title">{{ session('error') }}</span>
                    <p>Vui lòng hoàn thiện thông tin dưới đây để tiếp tục.</p>
                </div>
            </div>
        @endif

        <div class="profile-layout">
            
            <!-- CỘT TRÁI: Sidebar Cá nhân -->
            <aside class="profile-sidebar">
                <div class="sidebar-header">
                    <div class="user-avatar-circle">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h2 class="sidebar-user-name">{{ $user->name }}</h2>
                    <span class="sidebar-user-role">Thành viên thân thiết</span>
                </div>
                
                <div class="sidebar-menu">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link active">
                        <i class="fa-solid fa-user-pen"></i>
                        Thông tin cá nhân
                    </a>
                    <a href="{{ route('bookings.history') }}" class="sidebar-link">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Lịch sử đặt phòng
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: 0.5rem; border-top: 1px solid var(--border-light); padding-top: 0.5rem;">
                        @csrf
                        <button type="submit" class="sidebar-link btn-logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </aside>

            <!-- CỘT PHẢI: Form Cập nhật -->
            <main class="profile-content">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div>
                            <h1 class="profile-title">Cập nhật thông tin</h1>
                            <p class="profile-desc">Quản lý thông tin hồ sơ của bạn để bảo mật và đặt phòng nhanh hơn.</p>
                        </div>
                        <div class="header-icon">
                            <i class="fa-solid fa-address-card"></i>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="profile-form-grid">
                            
                            <!-- 1. Họ và tên -->
                            <div class="col-span-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">Họ và tên <span class="required">*</span></label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-user input-icon"></i>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                                    </div>
                                    @error('name') <p class="error-msg">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- 2. Email -->
                            <div class="col-span-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Địa chỉ Email <span class="required">*</span></label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-envelope input-icon"></i>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                                    </div>
                                    @error('email') <p class="error-msg">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- 3. Số điện thoại -->
                            <div>
                                <div class="form-group">
                                    <label for="phone" class="form-label">Số điện thoại <span class="required">*</span></label>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-phone input-icon"></i>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="0901234567" required>
                                    </div>
                                    <p class="form-text">Dùng để liên hệ xác nhận đặt phòng.</p>
                                    @error('phone') <p class="error-msg">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- 4. CCCD -->
                            <div>
                                <div class="form-group">
                                    <label for="cccd" class="form-label">CCCD / CMND <span class="required">*</span></label>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-id-card input-icon"></i>
                                        <input type="text" name="cccd" id="cccd" value="{{ old('cccd', $user->cccd) }}" class="form-input" placeholder="12 số căn cước" required>
                                    </div>
                                    <p class="form-text">Bắt buộc để làm thủ tục lưu trú.</p>
                                    @error('cccd') <p class="error-msg">{{ $message }}</p> @enderror
                                </div>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fa-solid fa-save"></i>
                                <span>Lưu thay đổi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection