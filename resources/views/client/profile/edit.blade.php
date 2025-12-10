@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500">
            <a href="{{ route('trang_chu') }}" class="hover:text-brand-gold transition">Trang chủ</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-brand-900 font-medium">Hồ sơ cá nhân</span>
        </nav>

        <!-- Thông báo đặc biệt (nếu bị redirect từ trang đặt phòng về) -->
        @if(session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-bold">
                            {{ session('error') }}
                        </p>
                        <p class="text-xs text-red-600 mt-1">Vui lòng hoàn thiện thông tin dưới đây để tiếp tục.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- CỘT TRÁI: Sidebar Cá nhân -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <!-- Header Sidebar -->
                    <div class="bg-brand-900 p-6 text-center">
                        <div class="w-20 h-20 mx-auto bg-brand-gold rounded-full flex items-center justify-center text-white text-3xl font-serif font-bold shadow-lg border-4 border-white/10">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h2 class="mt-4 text-white font-bold truncate">{{ $user->name }}</h2>
                        <p class="text-brand-gold text-xs uppercase tracking-wider mt-1">Thành viên thân thiết</p>
                    </div>
                    
                    <!-- Menu Links -->
                    <div class="p-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm font-medium text-brand-900 bg-brand-50 rounded-lg mb-1">
                            <i class="fa-solid fa-user-pen w-6 text-brand-gold"></i>
                            Thông tin cá nhân
                        </a>
                        <a href="{{ route('bookings.history') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-brand-900 rounded-lg transition">
                            <i class="fa-solid fa-clock-rotate-left w-6 text-gray-400"></i>
                            Lịch sử đặt phòng
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-gray-100 pt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                                <i class="fa-solid fa-right-from-bracket w-6"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: Form Cập nhật -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-10">
                    <div class="border-b border-gray-100 pb-6 mb-6 flex justify-between items-center">
                        <div>
                            <h1 class="font-serif text-2xl font-bold text-gray-900">Cập nhật thông tin</h1>
                            <p class="text-sm text-gray-500 mt-1">Quản lý thông tin hồ sơ của bạn để bảo mật và đặt phòng nhanh hơn.</p>
                        </div>
                        <div class="hidden md:block text-brand-gold text-4xl opacity-20">
                            <i class="fa-solid fa-address-card"></i>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- 1. Họ và tên -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold text-gray-500 uppercase mb-2">Họ và tên <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-user"></i>
                                    </span>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-gold focus:border-brand-gold transition" required>
                                </div>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- 2. Email (Read-only hoặc Editable tùy bạn, thường nên cho sửa nhưng phải check unique) -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-xs font-bold text-gray-500 uppercase mb-2">Địa chỉ Email <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-gold focus:border-brand-gold transition" required>
                                </div>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- 3. Số điện thoại (QUAN TRỌNG) -->
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 md:col-span-1">
                                <label for="phone" class="block text-xs font-bold text-blue-800 uppercase mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-500">
                                        <i class="fa-solid fa-phone"></i>
                                    </span>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="pl-10 block w-full border-blue-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white" placeholder="0901234567" required>
                                </div>
                                <p class="text-[10px] text-blue-600 mt-2">Dùng để liên hệ xác nhận đặt phòng.</p>
                                @error('phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <!-- 4. CCCD (QUAN TRỌNG) -->
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 md:col-span-1">
                                <label for="cccd" class="block text-xs font-bold text-blue-800 uppercase mb-2">CCCD / CMND <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-500">
                                        <i class="fa-solid fa-id-card"></i>
                                    </span>
                                    <input type="text" name="cccd" id="cccd" value="{{ old('cccd', $user->cccd) }}" class="pl-10 block w-full border-blue-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white" placeholder="12 số căn cước" required>
                                </div>
                                <p class="text-[10px] text-blue-600 mt-2">Bắt buộc để làm thủ tục lưu trú.</p>
                                @error('cccd') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- 5. Mật khẩu (Optional - Thường tách ra trang riêng hoặc accordion, ở đây để đơn giản ta bỏ qua đổi pass, chỉ tập trung thông tin cơ bản) -->
                        
                        <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                            <button type="submit" class="bg-brand-900 text-white font-bold py-3 px-8 rounded-lg hover:bg-brand-800 transition duration-300 shadow-lg transform hover:-translate-y-0.5 flex items-center gap-2">
                                <i class="fa-solid fa-save"></i>
                                <span>Lưu thay đổi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection