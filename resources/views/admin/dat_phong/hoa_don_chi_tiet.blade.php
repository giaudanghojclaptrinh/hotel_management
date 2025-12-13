@extends('admin.layouts.dashboard')
@section('title', 'Chi tiết Hóa đơn')

@section('header', 'Quản lý Hóa đơn')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.dat-phong') }}" class="text-sm font-medium text-gray-500 hover:text-brand-gold transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Quay lại sơ đồ
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:border-brand-gold hover:text-brand-gold shadow-sm">
                <i class="fa-solid fa-print mr-2"></i> In hóa đơn
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center"><i class="fa-solid fa-check-circle mr-3"></i> {{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        
        <div class="bg-brand-900 p-8 text-white flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-serif font-bold text-brand-gold">HÓA ĐƠN</h1>
                <p class="text-gray-400 mt-1 uppercase tracking-widest text-xs">Mã HĐ: #{{ $hoaDon->ma_hoa_don }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-lg">Luxury Stay Hotel</p>
                <p class="text-sm text-gray-400">contact@luxurystay.com</p>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-2 gap-8 mb-8 border-b border-gray-100 pb-8">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Khách hàng</p>
                    <p class="text-lg font-bold text-brand-900">{{ $hoaDon->datPhong->user->name ?? $datPhong->user->name ?? 'Guest' }}</p>
                    <p class="text-gray-500 text-sm">{{ $hoaDon->datPhong->user->email ?? $datPhong->user->email ?? '' }}</p>
                    <p class="text-gray-500 text-sm">{{ $hoaDon->datPhong->user->phone ?? $datPhong->user->phone ?? '' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Chi tiết đặt phòng</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Ngày đặt:</span> {{ optional($hoaDon->ngay_lap ?? $hoaDon->created_at)->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Check-in:</span> {{ $hoaDon->datPhong->ngay_den }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Check-out:</span> {{ $hoaDon->datPhong->ngay_di }}</p>
                </div>
            </div>

            <form action="{{ route('admin.dat-phong.thanh-toan', $datPhong->id) }}" method="POST" class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Trạng thái thanh toán</label>
                        <select name="trang_thai" class="w-full rounded-lg border-gray-300 focus:border-brand-gold focus:ring-brand-gold">
                            <option value="unpaid" {{ $hoaDon->trang_thai == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $hoaDon->trang_thai == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Phương thức</label>
                        <select name="phuong_thuc_thanh_toan" class="w-full rounded-lg border-gray-300 focus:border-brand-gold focus:ring-brand-gold">
                            <option value="cash" {{ $hoaDon->phuong_thuc_thanh_toan == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                            <option value="banking" {{ $hoaDon->phuong_thuc_thanh_toan == 'banking' ? 'selected' : '' }}>Chuyển khoản</option>
                            <option value="online" {{ $hoaDon->phuong_thuc_thanh_toan == 'online' ? 'selected' : '' }}>Online (VNPay)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button type="submit" class="px-6 py-2 bg-brand-900 text-brand-gold font-bold rounded-lg hover:bg-gray-800 transition-all shadow-md">
                        <i class="fa-solid fa-save mr-2"></i> Cập nhật
                    </button>
                </div>
            </form>

            <div class="flex justify-end">
                <div class="w-full md:w-1/2">
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Tổng tiền phòng</span>
                        <span class="font-medium">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between py-4 items-center">
                        <span class="text-lg font-bold text-brand-900">THÀNH TIỀN</span>
                        <span class="text-2xl font-serif font-bold text-brand-gold">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection