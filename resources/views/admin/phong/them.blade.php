@extends('admin.layouts.dashboard')
@section('title', 'Thêm Phòng')
@section('header', 'Thêm phòng mới')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-gray-900">Thông tin phòng</h1>
        <a href="{{ route('admin.phong') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-brand-gold hover:text-brand-gold transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.phong.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Số phòng <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-door-closed text-gray-400"></i>
                            </div>
                            <input type="text" name="so_phong" required placeholder="Ví dụ: 101, 205, VIP-01"
                                   class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 font-medium text-brand-900">
                        </div>
                        @error('so_phong') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Thuộc Hạng phòng <span class="text-red-500">*</span></label>
                        <select name="loai_phong_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11">
                            <option value="">-- Chọn hạng phòng --</option>
                            @foreach($loaiPhongs as $lp)
                                <option value="{{ $lp->id }}">
                                    {{ $lp->ten_loai_phong }} - {{ number_format($lp->gia ?? 0, 0, ',', '.') }} đ
                                </option>
                            @endforeach
                        </select>
                        @error('loai_phong_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tình trạng ban đầu</label>
                        <select name="tinh_trang" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11">
                            <option value="available">Sẵn sàng đón khách (Available)</option>
                            <option value="maintenance">Đang bảo trì (Maintenance)</option>
                            <option value="cleaning">Đang dọn dẹp (Cleaning)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.phong') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition-all">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-900 text-brand-gold rounded-lg font-bold hover:bg-gray-800 shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Lưu Phòng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection