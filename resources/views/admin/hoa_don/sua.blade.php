@extends('admin.layouts.dashboard')
@section('title', 'Sửa Hóa đơn')
@section('header', 'Cập nhật hóa đơn')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">
            Sửa Hóa đơn <span class="text-brand-gold">#{{ $hoaDon->ma_hoa_don }}</span>
        </h1>
        <a href="{{ route('admin.hoa-don') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800 flex items-center justify-between">
            <span class="text-xs font-bold text-brand-gold uppercase tracking-wider">Thông tin chi tiết</span>
            <span class="text-xs font-medium text-gray-500"><i class="fa-regular fa-clock mr-1"></i> Cập nhật lần cuối: {{ $hoaDon->updated_at->format('d/m/Y H:i') }}</span>
        </div>

        <div class="p-8">
            <form method="POST" action="{{ route('admin.hoa-don.update', $hoaDon->id) }}">
                @csrf

                <div class="space-y-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Đơn đặt phòng liên quan</label>
                        <div class="relative">
                            <select name="dat_phong_id" class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all cursor-pointer">
                                @foreach($datPhongs as $dp)
                                    <option value="{{ $dp->id }}" {{ old('dat_phong_id', $hoaDon->dat_phong_id) == $dp->id ? 'selected' : '' }}>
                                        #{{ $dp->id }} - {{ $dp->user->name ?? 'Guest' }} ({{ optional($dp->ngay_den)->format('d/m/Y') ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-link text-gray-500"></i>
                            </div>
                        </div>
                        @error('dat_phong_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Mã hóa đơn</label>
                            <div class="relative">
                                <input type="text" name="ma_hoa_don" value="{{ old('ma_hoa_don', $hoaDon->ma_hoa_don) }}" 
                                       class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 font-mono font-bold tracking-wide">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-barcode text-gray-500"></i>
                                </div>
                            </div>
                            @error('ma_hoa_don')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Ngày lập phiếu</label>
                            <div class="relative">
                                <input type="date" name="ngay_lap" value="{{ old('ngay_lap', $hoaDon->ngay_lap ? $hoaDon->ngay_lap->format('Y-m-d') : '') }}" 
                                       class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-regular fa-calendar text-gray-500"></i>
                                </div>
                            </div>
                            @error('ngay_lap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Tổng tiền thanh toán</label>
                            <div class="relative">
                                <input type="number" name="tong_tien" step="0.01" value="{{ old('tong_tien', $hoaDon->tong_tien) }}" 
                                       class="w-full rounded-lg bg-gray-800 border-gray-700 shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 pr-12 font-serif font-bold text-xl text-brand-gold">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold text-xs">VNĐ</span>
                                </div>
                            </div>
                            @error('tong_tien')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Phương thức thanh toán</label>
                            <div class="relative">
                                <input type="text" name="phuong_thuc_thanh_toan" value="{{ old('phuong_thuc_thanh_toan', $hoaDon->phuong_thuc_thanh_toan) }}" 
                                       class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11"
                                       placeholder="Ví dụ: Tiền mặt, Chuyển khoản...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-credit-card text-gray-500"></i>
                                </div>
                            </div>
                            @error('phuong_thuc_thanh_toan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="bg-gray-800/30 p-6 rounded-xl border border-gray-800">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-400 mb-2">Trạng thái thanh toán</label>
                                <select name="trang_thai" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 cursor-pointer">
                                    <option value="unpaid" {{ old('trang_thai', $hoaDon->trang_thai) == 'unpaid' ? 'selected' : '' }}>⏳ Chưa thanh toán</option>
                                    <option value="paid" {{ old('trang_thai', $hoaDon->trang_thai) == 'paid' ? 'selected' : '' }}>✅ Đã thanh toán</option>
                                </select>
                                @error('trang_thai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-400 mb-2">Ghi chú thêm</label>
                                <textarea name="ghi_chu" rows="3" 
                                          class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm placeholder-gray-600"
                                          placeholder="Nhập ghi chú nội bộ...">{{ old('ghi_chu', $hoaDon->ghi_chu) }}</textarea>
                                @error('ghi_chu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.hoa-don') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Lưu Cập Nhật
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection