@extends('admin.layouts.dashboard')
@section('title', 'Thêm Khuyến mãi')
@section('header', 'Tạo khuyến mãi mới')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">Thông tin khuyến mãi</h1>
        <a href="{{ route('admin.khuyen-mai') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-brand-gold uppercase tracking-wider">Thiết lập chương trình</h3>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.khuyen-mai.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Tên chương trình <span class="text-red-500">*</span></label>
                        <input type="text" name="ten_khuyen_mai" value="{{ old('ten_khuyen_mai') }}" required placeholder="Ví dụ: Giảm giá mùa hè..."
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all placeholder-gray-600">
                        @error('ten_khuyen_mai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Mã khuyến mãi (Code) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="ma_khuyen_mai" value="{{ old('ma_khuyen_mai') }}" required placeholder="SUMMER2025" style="text-transform: uppercase;"
                                   class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 font-mono font-bold tracking-wide placeholder-gray-600">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-ticket text-gray-500"></i>
                            </div>
                        </div>
                        @error('ma_khuyen_mai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-gray-800/30 p-6 rounded-xl border border-gray-700 md:col-span-2">
                        <h3 class="text-sm font-bold text-brand-gold mb-4 flex items-center"><i class="fa-solid fa-coins mr-2"></i> Mức giảm giá</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Theo phần trăm (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="chiet_khau_phan_tram" value="{{ old('chiet_khau_phan_tram') }}" placeholder="0"
                                           class="w-full rounded-lg bg-gray-900 border-gray-600 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-10 pr-8 transition-all">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"><span class="text-gray-500 font-bold">%</span></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Hoặc số tiền (VNĐ)</label>
                                <div class="relative">
                                    <input type="number" step="1000" name="so_tien_giam_gia" value="{{ old('so_tien_giam_gia') }}" placeholder="0"
                                           class="w-full rounded-lg bg-gray-900 border-gray-600 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-10 pr-12 transition-all">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"><span class="text-gray-500 text-xs font-bold">VND</span></div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-blue-400 mt-3 italic flex items-center"><i class="fa-solid fa-circle-info mr-1"></i> Hệ thống sẽ ưu tiên tính theo % nếu nhập cả hai.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Ngày bắt đầu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="ngay_bat_dau" value="{{ old('ngay_bat_dau') }}" required
                                   class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar text-gray-500"></i>
                            </div>
                        </div>
                        @error('ngay_bat_dau') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Ngày kết thúc <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}" required
                                   class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar-check text-gray-500"></i>
                            </div>
                        </div>
                        @error('ngay_ket_thuc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-800 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.khuyen-mai') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-plus mr-2"></i> Tạo Khuyến Mãi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection