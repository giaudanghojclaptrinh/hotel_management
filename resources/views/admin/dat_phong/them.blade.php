@extends('admin.layouts.dashboard')
@section('title', 'Tạo đơn đặt phòng')
@section('header', 'Tạo đơn mới')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">Thông tin đặt phòng</h1>
        <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-brand-gold uppercase tracking-wider">Điền thông tin</h3>
        </div>

        <div class="p-8">
            <form method="POST" action="{{ route('admin.dat-phong.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Khách hàng</label>
                        <select name="user_id" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Chọn Phòng</label>
                        <select name="phong_id" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all">
                            @foreach($phongs as $phong)
                                <option value="{{ $phong->id }}">
                                    Phòng {{ $phong->so_phong }} — {{ $phong->loaiPhong->ten_loai ?? 'Loại thường' }} 
                                    ({{ number_format($phong->loaiPhong->gia ?? 0, 0, ',', '.') }} đ/đêm)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Ngày đến</label>
                            <input type="date" name="ngay_den" required 
                                   class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Ngày đi</label>
                            <input type="date" name="ngay_di" required 
                                   class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Phương thức thanh toán</label>
                        <select name="payment_method" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold h-11 transition-all">
                            <option value="cash">Tiền mặt (Tại quầy)</option>
                            <option value="online">Chuyển khoản / Online</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Ghi chú</label>
                        <textarea name="note" rows="3" 
                                  class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all placeholder-gray-600"
                                  placeholder="Nhập ghi chú (nếu có)..."></textarea>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.dat-phong') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                            <i class="fa-solid fa-check mr-2"></i> Tạo đơn
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection