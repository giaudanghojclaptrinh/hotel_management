@extends('admin.layouts.dashboard')
@section('title', 'Hồ sơ quản trị')
@section('header', 'Quản lý Hồ sơ cá nhân')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">
            <i class="fa-solid fa-user-circle mr-2 text-brand-gold"></i> Hồ sơ quản trị
        </h1>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-brand-gold uppercase tracking-wider">Cập nhật thông tin tài khoản</h3>
        </div>

        <div class="p-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-900/20 border border-green-800 text-green-400 text-sm shadow-sm flex items-center">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Họ và tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600" 
                           required>
                    @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600" 
                           required>
                    @error('email')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                
                <div class="pt-4 border-t border-gray-800">
                    <h3 class="text-md font-bold text-white mb-4">Đổi mật khẩu</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Mật khẩu mới (Không bắt buộc)</label>
                        <input type="password" name="password" placeholder="Để trống nếu không đổi"
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                        @error('password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
    
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu mới"
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-800 flex items-center justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-check-circle mr-2"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection