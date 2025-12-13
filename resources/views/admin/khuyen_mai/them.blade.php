@extends('admin.layouts.dashboard')
@section('title', 'Thêm Khuyến mãi')
@section('header', 'Tạo khuyến mãi mới')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-gray-900">Thông tin khuyến mãi</h1>
        <a href="{{ route('admin.khuyen-mai') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-brand-gold hover:text-brand-gold transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.khuyen-mai.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tên chương trình <span class="text-red-500">*</span></label>
                        <input type="text" name="ten_khuyen_mai" value="{{ old('ten_khuyen_mai') }}" required placeholder="Ví dụ: Giảm giá mùa hè..."
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11">
                        @error('ten_khuyen_mai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mã khuyến mãi (Code) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="ma_khuyen_mai" value="{{ old('ma_khuyen_mai') }}" required placeholder="SUMMER2025" style="text-transform: uppercase;"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 pl-10 font-mono font-bold text-brand-900">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-ticket text-gray-400"></i>
                            </div>
                        </div>
                        @error('ma_khuyen_mai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 md:col-span-2">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center"><i class="fa-solid fa-coins mr-2 text-brand-gold"></i> Mức giảm giá</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Theo phần trăm (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="chiet_khau_phan_tram" value="{{ old('chiet_khau_phan_tram') }}" placeholder="0"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold h-10 pr-8">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"><span class="text-gray-500 font-bold">%</span></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Hoặc số tiền (VNĐ)</label>
                                <div class="relative">
                                    <input type="number" step="1000" name="so_tien_giam_gia" value="{{ old('so_tien_giam_gia') }}" placeholder="0"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold h-10 pr-10">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"><span class="text-gray-500 text-xs">VND</span></div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-blue-600 mt-2 italic">* Chỉ nhập 1 trong 2 ô. Nếu nhập cả 2, hệ thống sẽ ưu tiên %.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ngày bắt đầu <span class="text-red-500">*</span></label>
                        <input type="date" name="ngay_bat_dau" value="{{ old('ngay_bat_dau') }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11">
                        @error('ngay_bat_dau') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ngày kết thúc <span class="text-red-500">*</span></label>
                        <input type="date" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11">
                        @error('ngay_ket_thuc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.khuyen-mai') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition-all">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-900 text-brand-gold rounded-lg font-bold hover:bg-gray-800 shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-plus mr-2"></i> Tạo Khuyến Mãi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection