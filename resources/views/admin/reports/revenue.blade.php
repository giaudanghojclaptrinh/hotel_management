@extends('admin.layouts.dashboard')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Báo cáo doanh thu</h1>
        <a href="{{ route('admin.dat-phong') }}" class="text-sm text-blue-600">← Quay lại</a>
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
