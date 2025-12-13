@extends('admin.layouts.dashboard')
@section('title', 'Quản lý Người dùng')
@section('header', 'Danh sách Người dùng')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-white">Người dùng hệ thống</h1>
            <p class="text-sm text-gray-400 mt-1">Quản lý tài khoản khách hàng và quản trị viên</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.user.them') }}" class="flex items-center px-5 py-2.5 bg-brand-gold text-gray-900 rounded-xl font-bold hover:bg-white shadow-md transition-all transform hover:-translate-y-0.5">
                <i class="fa-solid fa-plus mr-2"></i> Thêm người dùng
            </a>
        </div>
    </div>

    <div class="bg-gray-900 p-4 rounded-xl shadow-sm border border-gray-800 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-500"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm theo tên, email, SĐT hoặc CCCD..." 
                       class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white placeholder-gray-500 focus:border-brand-gold focus:ring-brand-gold transition-all h-10 text-sm">
            </div>

            <div class="w-full md:w-48">
                <select name="role" class="w-full h-10 pl-3 pr-8 rounded-lg bg-gray-800 border-gray-700 text-white text-sm focus:border-brand-gold focus:ring-brand-gold cursor-pointer">
                    <option value="">-- Tất cả vai trò --</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Khách hàng</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <button type="submit" class="h-10 px-6 bg-gray-800 border border-gray-700 text-gray-300 font-bold rounded-lg hover:text-brand-gold hover:border-brand-gold transition-all flex items-center justify-center w-full md:w-auto">
                <i class="fa-solid fa-filter mr-2"></i> Lọc
            </button>

            @if(request('q') || request('role'))
                <a href="{{ route('admin.users') }}" class="h-10 px-4 bg-gray-800 border border-gray-700 text-red-400 font-bold rounded-lg hover:bg-gray-700 transition-all flex items-center justify-center" title="Xóa bộ lọc">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-900/30 border border-green-600 text-green-400 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            @if($users->isEmpty())
                <div class="p-16 text-center flex flex-col items-center justify-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-4 border border-gray-700">
                        <i class="fa-solid fa-users-slash text-3xl text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-300">Không tìm thấy người dùng</h3>
                    <p class="text-sm mt-1">Thử thay đổi bộ lọc hoặc thêm mới.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-10">#</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Thông tin cá nhân</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Liên hệ</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Vai trò</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800">
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-800/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-gold font-mono">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center text-brand-gold font-serif font-bold text-lg mr-3 shadow-sm group-hover:bg-brand-gold group-hover:text-gray-900 transition-colors">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 font-mono">CCCD: {{ $user->cccd ?? '---' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-300"><i class="fa-solid fa-envelope text-gray-500 mr-1 text-xs"></i> {{ $user->email }}</div>
                                <div class="text-sm text-gray-400 mt-1"><i class="fa-solid fa-phone text-gray-500 mr-1 text-xs"></i> {{ $user->phone ?? '---' }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900/30 text-purple-400 border border-purple-800">
                                        <i class="fa-solid fa-crown mr-1 text-[10px]"></i> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">
                                        <i class="fa-solid fa-user mr-1"></i> Khách hàng
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium text-green-400 bg-green-900/20 border border-green-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> Online
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.user.sua', ['id' => $user->id]) }}" class="p-2 text-gray-500 hover:text-brand-gold hover:bg-gray-800 rounded-lg transition-all" title="Sửa thông tin">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    @if(Auth::id() !== $user->id) 
                                        <a href="{{ route('admin.user.xoa', ['id' => $user->id]) }}" 
                                           onclick="return confirm('CẢNH BÁO: Xóa người dùng này sẽ xóa toàn bộ lịch sử đặt phòng của họ. Bạn có chắc chắn?')" 
                                           class="p-2 text-gray-500 hover:text-red-500 hover:bg-gray-800 rounded-lg transition-all" title="Xóa tài khoản">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    @else
                                        <span class="p-2 text-gray-700 cursor-not-allowed" title="Không thể xóa chính mình"><i class="fa-solid fa-trash-can"></i></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-800">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection