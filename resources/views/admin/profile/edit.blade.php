@extends('admin.layouts.dashboard')
@section('title', 'Hồ sơ quản trị')
@section('header', 'Quản lý Hồ sơ cá nhân')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-800">
        <h1 class="text-3xl font-serif font-bold text-white">
            <i class="fa-solid fa-user-circle mr-2 text-brand-gold"></i> Hồ sơ quản trị
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <aside class="lg:col-span-1 bg-gray-900 rounded-2xl shadow-lg border border-gray-800 p-6 h-fit sticky top-6">
            <div class="text-center pb-4 border-b border-gray-800">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-brand-gold to-yellow-200 text-gray-900 flex items-center justify-center font-bold text-3xl font-serif shadow-xl shadow-brand-gold/20">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <h2 class="text-xl font-bold text-white mt-3">{{ Auth::user()->name ?? 'Quản trị viên' }}</h2>
                <span class="text-xs text-brand-gold uppercase tracking-widest block mt-1">Admin Level: {{ Auth::user()->role ?? 'root' }}</span>
            </div>
            
            <div class="space-y-1 mt-4">
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors bg-gray-800 text-brand-gold font-bold">
                    <i class="fa-solid fa-user-pen w-6 text-center text-lg"></i>
                    <span class="ml-3">Thông tin cá nhân</span>
                </a>
                <!-- <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors text-gray-400 hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-bell w-6 text-center text-lg"></i>
                    <span class="ml-3">Cài đặt Thông báo</span>
                </a> -->
                
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-800 mt-2">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl w-full text-left text-gray-500 hover:text-red-400 hover:bg-gray-800 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket w-6 text-center text-lg"></i>
                        <span class="ml-3">Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="lg:col-span-3">
            <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
                
                <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="text-md font-bold text-white">Cập nhật Hồ sơ</h3>
                    <i class="fa-solid fa-address-card text-brand-gold opacity-50 text-xl"></i>
                </div>

                <div class="p-8">
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-lg bg-green-900/20 border border-green-800 text-green-400 text-sm shadow-sm flex items-center">
                            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                        @csrf
                        
                        <div class="pb-4 border-b border-gray-800">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Thông tin tài khoản</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        </div>

                        <div class="pt-6 border-t border-gray-800 pb-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Đổi mật khẩu</h4>
                            <p class="text-xs text-gray-600 mt-1">Để trống nếu bạn không muốn thay đổi mật khẩu hiện tại.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-400 mb-2">Mật khẩu mới</label>
                                <input type="password" name="password" placeholder="********"
                                       class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                                @error('password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
        
                            <div>
                                <label class="block text-sm font-bold text-gray-400 mb-2">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" placeholder="********"
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
        </main>
    </div>
</div>
@endsection