@extends('admin.layouts.dashboard')
@section('title', 'Danh sách Phòng')
@section('header', 'Quản lý Phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Danh sách Phòng</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý số phòng và trạng thái vật lý</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
            <form method="GET" action="{{ route('admin.phong') }}" class="flex flex-col md:flex-row gap-3 flex-1">
                
                <div class="relative min-w-[200px]">
                    <select name="loai_phong_id" class="w-full h-10 pl-3 pr-8 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold cursor-pointer">
                        <option value="">-- Tất cả hạng phòng --</option>
                        @foreach($loaiPhongs as $lp)
                            <option value="{{ $lp->id }}" {{ request('loai_phong_id') == $lp->id ? 'selected' : '' }}>
                                {{ $lp->ten_loai_phong }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative min-w-[160px]">
                    <select name="tinh_trang" class="w-full h-10 pl-3 pr-8 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold cursor-pointer">
                        <option value="">-- Trạng thái --</option>
                        <option value="available" {{ request('tinh_trang') == 'available' ? 'selected' : '' }}>Trống (Available)</option>
                        <option value="occupied" {{ request('tinh_trang') == 'occupied' ? 'selected' : '' }}>Đang ở (Occupied)</option>
                        <option value="maintenance" {{ request('tinh_trang') == 'maintenance' ? 'selected' : '' }}>Bảo trì (Maintenance)</option>
                        <option value="cleaning" {{ request('tinh_trang') == 'cleaning' ? 'selected' : '' }}>Dọn dẹp (Cleaning)</option>
                    </select>
                </div>

                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm số phòng..." 
                           class="w-full h-10 pl-10 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold">
                </div>

                <button type="submit" class="h-10 px-4 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-all flex items-center justify-center">
                    Lọc
                </button>

                <a href="{{ route('admin.phong') }}" class="h-10 px-4 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center justify-center" title="Đặt lại bộ lọc">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Đặt lại
                </a>
            </form>

            <a href="{{ route('admin.phong.them') }}" class="h-10 px-5 bg-brand-900 text-brand-gold rounded-lg text-sm font-bold hover:bg-gray-800 shadow-md transition-all flex items-center justify-center whitespace-nowrap">
                <i class="fa-solid fa-plus mr-2"></i> Thêm phòng
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mt-6">
        <div class="overflow-x-auto">
            @if($phongs->isEmpty())
                {{-- existing empty state handled above --}}
            @else
                {{-- table already rendered above; keep pagination here --}}
            @endif
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">{{ $phongs->withQueryString()->links() }}</div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            @if($phongs->isEmpty())
                <div class="p-12 text-center flex flex-col items-center justify-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-door-open text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Không tìm thấy phòng nào</h3>
                    <p class="text-sm mt-1">Thử thay đổi bộ lọc hoặc thêm phòng mới.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-brand-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Số phòng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Hạng phòng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Giá niêm yết</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-brand-gold uppercase tracking-wider">Tình trạng</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-brand-gold uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($phongs as $phong)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 font-serif font-bold group-hover:bg-brand-gold group-hover:text-white transition-colors">
                                        {{ $phong->so_phong }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $phong->loaiPhong->ten_loai_phong ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ number_format($phong->loaiPhong->gia ?? 0, 0, ',', '.') }} đ</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                    $statusLabel = 'Không xác định';
                                    
                                    switch($phong->tinh_trang) {
                                        case 'available':
                                            $statusClass = 'bg-green-100 text-green-700 border-green-200';
                                            $statusLabel = 'Trống';
                                            break;
                                        case 'occupied':
                                            $statusClass = 'bg-red-100 text-red-700 border-red-200';
                                            $statusLabel = 'Đang ở';
                                            break;
                                        case 'maintenance':
                                            $statusClass = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                                            $statusLabel = 'Bảo trì';
                                            break;
                                        case 'cleaning':
                                            $statusClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                            $statusLabel = 'Dọn dẹp';
                                            break;
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.phong.sua', ['id' => $phong->id]) }}" class="text-gray-400 hover:text-brand-gold transition-colors p-2 rounded-full hover:bg-yellow-50" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </a>
                                    <a href="{{ route('admin.phong.xoa', ['id' => $phong->id]) }}" 
                                       onclick="return confirm('Bạn có chắc muốn xóa phòng này?')" 
                                       class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50" title="Xóa">
                                        <i class="fa-solid fa-trash-can text-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection