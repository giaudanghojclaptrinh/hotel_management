@extends('admin.layouts.dashboard')
@section('title', 'Tạo đơn đặt phòng')
@section('header', 'Tạo đơn mới')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.dat-phong.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500">Khách hàng</label>
                    <select name="user_id" class="w-full mt-1 p-2 border rounded">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500">Phòng</label>
                    <select name="phong_id" class="w-full mt-1 p-2 border rounded">
                        @foreach($phongs as $phong)
                            <option value="{{ $phong->id }}">{{ $phong->so_phong }} — {{ $phong->loaiPhong->ten_loai_phong ?? 'Loại' }} ({{ number_format($phong->loaiPhong->gia ?? 0,0,',','.') }} đ/đêm)</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-gray-500">Ngày đến</label>
                        <input type="date" name="ngay_den" class="w-full mt-1 p-2 border rounded" required />
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500">Ngày đi</label>
                        <input type="date" name="ngay_di" class="w-full mt-1 p-2 border rounded" required />
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500">Phương thức thanh toán</label>
                    <select name="payment_method" class="w-full mt-1 p-2 border rounded">
                        <option value="cash">Tiền mặt (Thanh toán tại khách sạn)</option>
                        <option value="online">Thanh toán online</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500">Ghi chú</label>
                    <textarea name="note" class="w-full mt-1 p-2 border rounded" rows="3"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-white border rounded text-sm">Hủy</a>
                    <button type="submit" class="px-4 py-2 bg-brand-900 text-brand-gold rounded text-sm font-bold">Tạo đơn</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
