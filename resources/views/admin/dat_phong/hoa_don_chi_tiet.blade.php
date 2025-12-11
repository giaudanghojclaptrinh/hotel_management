@extends('admin.layouts.dashboard')
@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    
    <div class="toolbar mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Hóa đơn #{{ $hoaDon->ma_hoa_don }}</h1>
        <a href="{{ route('admin.dat-phong') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">{{ session('error') }}</div>
    @endif
    
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg border border-gray-100">

        <div class="grid grid-cols-2 border-b pb-4 mb-4 text-sm">
            <div>
                <p class="font-semibold text-gray-800">Khách hàng: {{ $datPhong->user->name ?? 'N/A' }}</p>
                <p class="text-gray-600">Email: {{ $datPhong->user->email ?? 'N/A' }}</p>
                <p class="text-gray-600">SĐT: {{ $datPhong->user->phone ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <p class="font-semibold text-gray-800">Mã đơn đặt: #{{ $datPhong->id }}</p>
                {{-- [FIXED] Sử dụng Carbon object từ Model DatPhong --}}
                <p class="text-gray-600">Ngày lập: {{ $hoaDon->ngay_lap->format('d/m/Y H:i') }}</p>
                <p class="text-xs text-gray-400">Ngày đến: {{ $datPhong->ngay_den->format('d/m/Y') }}</p>
                <p class="text-xs text-gray-400">Ngày đi: {{ $datPhong->ngay_di->format('d/m/Y') }}</p>
            </div>
        </div>

        <h3 class="text-lg font-bold text-gray-800 mb-3">Chi tiết dịch vụ</h3>
        <table class="w-full text-sm mb-6 border-b border-gray-200">
            <thead>
                <tr class="text-xs font-semibold text-gray-500 uppercase bg-gray-50">
                    <th class="p-2 text-left">Phòng</th>
                    <th class="p-2 text-center">Số đêm</th>
                    <th class="p-2 text-right">Đơn giá/đêm</th>
                    <th class="p-2 text-right">Thành tiền (Gốc)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datPhong->chiTietDatPhongs as $ct)
                <tr class="border-b">
                    <td class="p-2">
                        {{ $ct->loaiPhong->ten_loai_phong ?? 'N/A' }}
                        <div class="text-xs text-gray-500">Phòng vật lý: **{{ $ct->phong->so_phong ?? 'Chưa gán' }}**</div>
                    </td>
                    {{-- [FIXED] Sử dụng Carbon object từ Model DatPhong --}}
                    <td class="p-2 text-center">{{ $datPhong->ngay_den->diffInDays($datPhong->ngay_di) }}</td>
                    <td class="p-2 text-right">{{ number_format($ct->don_gia, 0, ',', '.') }} đ</td>
                    <td class="p-2 text-right">{{ number_format($ct->thanh_tien, 0, ',', '.') }} đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end">
            <div class="w-full md:w-1/2 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tổng tiền gốc:</span>
                    <span class="font-medium">{{ number_format($datPhong->chiTietDatPhongs->sum('thanh_tien'), 0, ',', '.') }} đ</span>
                </div>
                
                <div class="flex justify-between text-red-600 font-bold">
                    <span>Giảm giá (Mã: {{ $datPhong->promotion_code ?? '—' }}):</span>
                    <span>- {{ number_format($datPhong->discount_amount, 0, ',', '.') }} đ</span>
                </div>

                <div class="flex justify-between items-center border-t pt-2 font-bold text-lg">
                    <span class="text-gray-800">TỔNG CỘNG PHẢI THANH TOÁN:</span>
                    <span class="text-blue-600 text-2xl">{{ number_format($datPhong->tong_tien, 0, ',', '.') }} đ</span>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-dashed">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Xử lý Thanh toán</h3>
            <form action="{{ route('admin.dat-phong.thanh-toan', $datPhong->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Trạng thái Thanh toán</label>
                        <select name="trang_thai" class="w-full rounded-md border-gray-300 shadow-sm mt-1">
                            <option value="unpaid" {{ $hoaDon->trang_thai == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $hoaDon->trang_thai == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phương thức thanh toán</label>
                        <select name="phuong_thuc_thanh_toan" class="w-full rounded-md border-gray-300 shadow-sm mt-1">
                            <option value="cash" {{ $hoaDon->phuong_thuc_thanh_toan == 'cash' ? 'selected' : '' }}>Tiền mặt/Quầy</option>
                            <option value="banking" {{ $hoaDon->phuong_thuc_thanh_toan == 'banking' ? 'selected' : '' }}>Chuyển khoản</option>
                            <option value="online" {{ $hoaDon->phuong_thuc_thanh_toan == 'online' ? 'selected' : '' }}>Online (VNPay/Momo)</option>
                        </select>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">
                        <i class="fa fa-save"></i> Cập nhật Thanh toán
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection