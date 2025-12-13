@extends('admin.layouts.dashboard')
@section('title', 'Thêm Người dùng')
@section('header', 'Tạo tài khoản mới')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">Thông tin tài khoản</h1>
        <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-brand-gold uppercase tracking-wider">Nhập thông tin</h3>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Họ và Tên <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: Nguyễn Văn A"
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Email đăng nhập <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com"
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0909xxxxxx"
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Số CCCD/CMND</label>
                        <input type="text" name="cccd" value="{{ old('cccd') }}" placeholder="12 số căn cước"
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                        @error('cccd') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Vai trò hệ thống</label>
                        <select name="role" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all cursor-pointer">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Khách hàng (User)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Mật khẩu <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-800 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Lưu Người Dùng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection