@extends('admin.layouts.dashboard')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Báo cáo doanh thu</h1>
        <a href="{{ route('admin.dat-phong') }}" class="text-sm text-blue-600">← Quay lại</a>
    </div>

    <!-- Thẻ tổng quan nhanh -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-900 rounded-2xl p-5 border border-gray-800 shadow-sm">
            <p class="text-sm text-gray-400">Tổng doanh thu (đã thanh toán)</p>
            <div class="flex items-end justify-between mt-2">
                <h2 class="text-3xl font-bold text-white">{{ number_format($totalRevenue ?? 0) }} VND</h2>
                <span class="text-green-400 text-xs font-semibold">PAID</span>
            </div>
        </div>

        <a href="{{ route('admin.hoa-don', ['status' => 'paid']) }}" class="group block h-full bg-gray-900 rounded-2xl p-5 border border-gray-800 shadow-sm hover:border-green-700 hover:bg-gray-800/80 transition">
            <div class="flex items-start justify-between gap-4 h-full">
                <div class="space-y-1">
                    <p class="text-sm text-gray-400">Hóa đơn đã thanh toán</p>
                    <div class="text-2xl font-bold text-white">{{ $paidCount ?? 0 }} đơn</div>
                    <div class="text-sm text-gray-500">{{ number_format($paidSum ?? 0) }} VND</div>
                    <div class="text-xs text-green-400 mt-3">Xem danh sách →</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-900/30 border border-green-700 flex items-center justify-center text-green-400 group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.hoa-don', ['status' => 'unpaid']) }}" class="group block h-full bg-gray-900 rounded-2xl p-5 border border-gray-800 shadow-sm hover:border-yellow-700 hover:bg-gray-800/80 transition">
            <div class="flex items-start justify-between gap-4 h-full">
                <div class="space-y-1">
                    <p class="text-sm text-gray-400">Hóa đơn chờ thanh toán</p>
                    <div class="text-2xl font-bold text-white">{{ $unpaidCount ?? 0 }} đơn</div>
                    <div class="text-sm text-gray-500">{{ number_format($unpaidSum ?? 0) }} VND</div>
                    <div class="text-xs text-amber-300 mt-3">Đi tới quản lý hóa đơn →</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-yellow-900/30 border border-yellow-700 flex items-center justify-center text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="card-common p-4 mb-6">
        <form method="GET" class="flex gap-2 items-center">
            <label class="text-sm">Từ:</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input">
            <label class="text-sm">Đến:</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input">
            <label class="text-sm">Phòng:</label>
            <select name="room_id" class="form-select">
                <option value="">Tất cả</option>
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ request('room_id') == $r->id ? 'selected' : '' }}>{{ $r->so_phong }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary">Lọc</button>
        </form>
    </div>

    <div class="card-common p-4 mb-6">
        <div class="mb-4">
            <h2 class="text-lg font-medium">Tổng doanh thu: <span class="text-xl font-bold text-green-600">{{ number_format($totalRevenue ?? 0) }} VND</span></h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khách</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phòng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày cập nhật</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thanh toán</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hóa đơn</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($bookings as $b)
                    <tr>
                        <td class="px-6 py-4">{{ $loop->iteration + (($bookings->currentPage()-1) * $bookings->perPage()) }}</td>
                        <td class="px-6 py-4">#{{ $b->id }}</td>
                        <td class="px-6 py-4">{{ $b->user->name ?? '—' }}</td>
                        <td class="px-6 py-4">@if($b->chiTietDatPhongs->first()) {{ $b->chiTietDatPhongs->first()->phong->so_phong ?? '—' }} @endif</td>
                        <td class="px-6 py-4">{{ $b->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @php($status = $b->hoaDon->trang_thai ?? $b->payment_status)
                            @if($status === 'paid')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    <i class="fa-solid fa-check mr-1"></i> Đã thanh toán
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                    <i class="fa-solid fa-clock mr-1"></i> Chờ thanh toán
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">{{ number_format($b->tong_tien) }} VND</td>
                        <td class="px-6 py-4"><a href="{{ route('admin.dat-phong.hoa-don', $b->id) }}" class="text-indigo-600">Xem hóa đơn</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4">{{ $bookings->links() }}</div>
    </div>

</div>

@endsection
