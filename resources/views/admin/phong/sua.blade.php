@extends('admin.layouts.dashboard')
@section('title', 'Sửa Phòng')
@section('header', 'Cập nhật phòng')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">
            Cập nhật phòng: <span class="text-brand-gold">{{ $phong->so_phong }}</span>
        </h1>
        <a href="{{ route('admin.phong') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-brand-gold uppercase tracking-wider">Thông tin chi tiết</h3>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.phong.update', ['id' => $phong->id]) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Số phòng <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-door-closed text-gray-500"></i>
                            </div>
                            <input type="text" name="so_phong" required value="{{ old('so_phong', $phong->so_phong) }}"
                                   class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 font-medium font-mono tracking-wide placeholder-gray-600">
                        </div>
                        @error('so_phong') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Thuộc Hạng phòng <span class="text-red-500">*</span></label>
                        <select name="loai_phong_id" required class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 cursor-pointer">
                            <option value="">-- Chọn hạng phòng --</option>
                            @foreach($loaiPhongs as $lp)
                                <option value="{{ $lp->id }}" {{ $phong->loai_phong_id == $lp->id ? 'selected' : '' }}>
                                    {{ $lp->ten_loai_phong }} - {{ number_format($lp->gia ?? 0, 0, ',', '.') }} đ
                                </option>
                            @endforeach
                        </select>
                        @error('loai_phong_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Tình trạng hiện tại</label>
                        <select name="tinh_trang" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 cursor-pointer">
                            <option value="available" {{ $phong->tinh_trang == 'available' ? 'selected' : '' }}>🟢 Sẵn sàng đón khách (Available)</option>
                            <option value="occupied" {{ $phong->tinh_trang == 'occupied' ? 'selected' : '' }}>🔴 Đang có khách (Occupied)</option>
                            <option value="maintenance" {{ $phong->tinh_trang == 'maintenance' ? 'selected' : '' }}>🟡 Đang bảo trì (Maintenance)</option>
                            <option value="cleaning" {{ $phong->tinh_trang == 'cleaning' ? 'selected' : '' }}>🔵 Đang dọn dẹp (Cleaning)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-800 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.phong') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection