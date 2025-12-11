@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Sửa đặt phòng #{{ $datPhong->id }}</h2>
            <a href="{{ route('admin.dat-phong') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('admin.dat-phong.update', ['id' => $datPhong->id]) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="user_id">Chọn người dùng (người đặt)</label>
                    <select id="user_id" name="user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Chọn người dùng --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $datPhong->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ngay_den">Ngày đến</label>
                    {{-- Chuyển định dạng Carbon object sang Y-m-d nếu cần --}}
                    <input type="date" id="ngay_den" name="ngay_den" value="{{ old('ngay_den', optional($datPhong->ngay_den)->format('Y-m-d') ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('ngay_den') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ngay_di">Ngày đi</label>
                    <input type="date" id="ngay_di" name="ngay_di" value="{{ old('ngay_di', optional($datPhong->ngay_di)->format('Y-m-d') ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('ngay_di') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                
                <hr class="col-span-full border-t border-gray-100 my-2" />

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="tong_tien">Tổng tiền (VND)</label>
                        <input type="number" step="0.01" id="tong_tien" name="tong_tien" value="{{ old('tong_tien', $datPhong->tong_tien) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        @error('tong_tien') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="trang_thai">Trạng thái Đơn</label>
                        <select id="trang_thai" name="trang_thai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="pending" {{ old('trang_thai', $datPhong->trang_thai) == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="confirmed" {{ old('trang_thai', $datPhong->trang_thai) == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ old('trang_thai', $datPhong->trang_thai) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('trang_thai') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="payment_status">Trạng thái TT</label>
                        <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="unpaid" {{ old('payment_status', $datPhong->payment_status) == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ old('payment_status', $datPhong->payment_status) == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="awaiting_payment" {{ old('payment_status', $datPhong->payment_status) == 'awaiting_payment' ? 'selected' : '' }}>Chờ TT Online</option>
                        </select>
                        @error('payment_status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="payment_method">Phương thức TT</label>
                        <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="pay_at_hotel" {{ old('payment_method', $datPhong->payment_method) == 'pay_at_hotel' ? 'selected' : '' }}>Tại khách sạn</option>
                            <option value="online" {{ old('payment_method', $datPhong->payment_method) == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                        @error('payment_method') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="promotion_code">Mã khuyến mãi</label>
                        <input type="text" id="promotion_code" name="promotion_code" value="{{ old('promotion_code', $datPhong->promotion_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        @error('promotion_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="discount_amount">Số tiền giảm (VND)</label>
                        <input type="number" step="0.01" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', $datPhong->discount_amount) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        @error('discount_amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label class="block text-sm font-medium text-gray-700" for="ghi_chu">Ghi chú</label>
                    <textarea id="ghi_chu" name="ghi_chu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('ghi_chu', $datPhong->ghi_chu) }}</textarea>
                    @error('ghi_chu') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.dat-phong') }}" class="text-sm text-gray-600 hover:underline">Hủy</a>
                <button type="submit" class="btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection