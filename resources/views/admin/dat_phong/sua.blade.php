@extends('admin.layouts.dashboard')
@section('title', 'Sửa Đặt phòng')
@section('header', 'Cập nhật thông tin')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-gray-900">Sửa đơn #{{ $datPhong->id }}</h1>
        <a href="{{ route('admin.dat-phong') }}" class="text-sm text-gray-500 hover:text-brand-gold flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-8 py-4 border-b border-gray-200">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Thông tin chi tiết</h3>
        </div>
        
        <div class="p-8">
            <form action="{{ route('admin.dat-phong.update', ['id' => $datPhong->id]) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Khách hàng</label>
                        <select id="user_id" name="user_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold">
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
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ngày đến</label>
                        <input type="date" name="ngay_den" value="{{ old('ngay_den', $datPhong->ngay_den) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ngày đi</label>
                        <input type="date" name="ngay_di" value="{{ old('ngay_di', $datPhong->ngay_di) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tổng tiền (VND)</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" name="tong_tien" value="{{ old('tong_tien', $datPhong->tong_tien) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold pr-12" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">đ</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Trạng thái</label>
                        <select name="trang_thai" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold">
                            <option value="pending" {{ $datPhong->trang_thai == 'pending' ? 'selected' : '' }}>Pending (Chờ duyệt)</option>
                            <option value="confirmed" {{ $datPhong->trang_thai == 'confirmed' ? 'selected' : '' }}>Confirmed (Đã duyệt)</option>
                            <option value="cancelled" {{ $datPhong->trang_thai == 'cancelled' ? 'selected' : '' }}>Cancelled (Hủy)</option>
                            <option value="completed" {{ $datPhong->trang_thai == 'completed' ? 'selected' : '' }}>Completed (Hoàn thành)</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ghi chú</label>
                        <textarea name="ghi_chu" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold">{{ old('ghi_chu', $datPhong->ghi_chu) }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">Hủy</a>
                    <button type="submit" class="px-6 py-2 bg-brand-900 text-brand-gold rounded-lg font-bold hover:bg-gray-800 shadow-md transition-all">
                        <i class="fa-solid fa-save mr-2"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection