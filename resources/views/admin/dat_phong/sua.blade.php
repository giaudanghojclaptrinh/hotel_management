@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Sửa đặt phòng</h2>
            <a href="{{ route('admin.dat-phong') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('admin.dat-phong.sua', ['id' => $datPhong->id]) }}" method="POST">
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
                    <input type="date" id="ngay_den" name="ngay_den" value="{{ old('ngay_den', $datPhong->ngay_den) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('ngay_den') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ngay_di">Ngày đi</label>
                    <input type="date" id="ngay_di" name="ngay_di" value="{{ old('ngay_di', $datPhong->ngay_di) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('ngay_di') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="tong_tien">Tổng tiền (VND)</label>
                    <input type="number" step="0.01" id="tong_tien" name="tong_tien" value="{{ old('tong_tien', $datPhong->tong_tien) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('tong_tien') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="payment_status">Thanh toán</label>
                    <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="unpaid" {{ old('payment_status', $datPhong->payment_status) == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="paid" {{ old('payment_status', $datPhong->payment_status) == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    </select>
                    @error('payment_status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

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

                <div>
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
@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Sửa đặt phòng</h2>
            <a href="{{ route('admin.dat-phong') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('admin.dat-phong.sua', ['id' => $datPhong->id]) }}" method="POST">
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
                    <input type="date" id="ngay_den" name="ngay_den" value="{{ old('ngay_den', $datPhong->ngay_den) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('ngay_den') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ngay_di">Ngày đi</label>
                    <input type="date" id="ngay_di" name="ngay_di" value="{{ old('ngay_di', $datPhong->ngay_di) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('ngay_di') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="trang_thai">Trạng thái</label>
                    <input type="text" id="trang_thai" name="trang_thai" value="{{ old('trang_thai', $datPhong->trang_thai) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('trang_thai') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
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
@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Sửa khuyến mãi</h2>
            <a href="{{ route('khuyen-mai') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('khuyen-mai.sua', ['id' => $khuyenMai->id]) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ten_khuyen_mai">Tên khuyến mãi</label>
                    <input type="text" id="ten_khuyen_mai" name="ten_khuyen_mai" value="{{ old('ten_khuyen_mai', $khuyenMai->ten_khuyen_mai) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('ten_khuyen_mai') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ma_khuyen_mai">Mã khuyến mãi</label>
                    <input type="text" id="ma_khuyen_mai" name="ma_khuyen_mai" value="{{ old('ma_khuyen_mai', $khuyenMai->ma_khuyen_mai) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('ma_khuyen_mai') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="chiet_khau_phan_tram">Chiết khấu (%)</label>
                    <input type="number" step="0.01" id="chiet_khau_phan_tram" name="chiet_khau_phan_tram" value="{{ old('chiet_khau_phan_tram', $khuyenMai->chiet_khau_phan_tram) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('chiet_khau_phan_tram') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="so_tien_giam_gia">Số tiền giảm (VND)</label>
                    <input type="number" step="0.01" id="so_tien_giam_gia" name="so_tien_giam_gia" value="{{ old('so_tien_giam_gia', $khuyenMai->so_tien_giam_gia) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('so_tien_giam_gia') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ngay_bat_dau">Ngày bắt đầu</label>
                    <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" value="{{ old('ngay_bat_dau', $khuyenMai->ngay_bat_dau) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('ngay_bat_dau') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ngay_ket_thuc">Ngày kết thúc</label>
                    <input type="date" id="ngay_ket_thuc" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc', $khuyenMai->ngay_ket_thuc) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('ngay_ket_thuc') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('khuyen-mai') }}" class="text-sm text-gray-600 hover:underline">Hủy</a>
                <button type="submit" class="btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection
