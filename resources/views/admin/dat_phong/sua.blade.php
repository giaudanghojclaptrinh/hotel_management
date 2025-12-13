@extends('admin.layouts.dashboard')
@section('title', 'Sửa Đặt phòng')
@section('header', 'Cập nhật thông tin')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">Sửa đơn #{{ $datPhong->id }}</h1>
        <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all flex items-center shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-brand-gold uppercase tracking-wider">Thông tin chi tiết</h3>
        </div>
        
        <div class="p-8">
            <form action="{{ route('admin.dat-phong.update', ['id' => $datPhong->id]) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Khách hàng</label>
                        <select id="user_id" name="user_id" required class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11">
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $datPhong->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Ngày đến</label>
                        <input type="date" name="ngay_den" value="{{ old('ngay_den', isset($datPhong->ngay_den) ? \Carbon\Carbon::parse($datPhong->ngay_den)->format('Y-m-d') : '') }}" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Ngày đi</label>
                        <input type="date" name="ngay_di" value="{{ old('ngay_di', isset($datPhong->ngay_di) ? \Carbon\Carbon::parse($datPhong->ngay_di)->format('Y-m-d') : '') }}" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Tổng tiền (VND)</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" name="tong_tien" value="{{ old('tong_tien', $datPhong->tong_tien) }}" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 pr-12 font-mono font-bold" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm font-bold">đ</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Trạng thái</label>
                        <select name="trang_thai" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11">
                            <option value="pending" {{ $datPhong->trang_thai == 'pending' ? 'selected' : '' }}>Pending (Chờ duyệt)</option>
                            <option value="confirmed" {{ $datPhong->trang_thai == 'confirmed' ? 'selected' : '' }}>Confirmed (Đã duyệt)</option>
                            <option value="cancelled" {{ $datPhong->trang_thai == 'cancelled' ? 'selected' : '' }}>Cancelled (Hủy)</option>
                            <option value="completed" {{ $datPhong->trang_thai == 'completed' ? 'selected' : '' }}>Completed (Hoàn thành)</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Ghi chú</label>
                        <textarea name="ghi_chu" rows="3" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold">{{ old('ghi_chu', $datPhong->ghi_chu) }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-800">
                    <a href="{{ route('admin.dat-phong') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 font-bold hover:bg-gray-700 hover:text-white transition-all">Hủy</a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection