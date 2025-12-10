@extends('admin.layouts.dashboard')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    <div class="toolbar mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Đặt phòng</h1>
    </div>

    <div class="card-common">
        <div class="overflow-x-auto">
            @if($datPhongs->isEmpty())
                <div class="p-6 text-center text-gray-500">Chưa có đặt phòng nào. Hãy thêm mới.</div>
            @else
            <table class="table-common min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phòng đã đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đến</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($datPhongs as $dp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            @if($dp->user)
                                {{ $dp->user->name }} <span class="text-xs text-gray-500">({{ $dp->user->email }})</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($dp->chiTietDatPhongs && $dp->chiTietDatPhongs->isNotEmpty())
                                @foreach($dp->chiTietDatPhongs as $ct)
                                    @if($ct->phong)
                                        <span class="inline-block mr-2 px-2 py-1 bg-gray-100 rounded text-sm">Phòng {{ $ct->phong->so_phong ?? $ct->phong->id }}</span>
                                    @else
                                        <span class="inline-block mr-2 px-2 py-1 bg-gray-100 rounded text-sm">Phòng #{{ $ct->phong_id }}</span>
                                    @endif
                                @endforeach
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dp->ngay_den }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dp->ngay_di }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($dp->tong_tien ?? 0, 0, ',', '.') }} VND</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dp->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dp->trang_thai ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            <a href="{{ route('admin.dat-phong.sua', ['id' => $dp->id]) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="Sửa"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('admin.dat-phong.xoa', ['id' => $dp->id]) }}" onclick="return confirm('Bạn có muốn xóa đặt phòng #{{ $dp->id }} không?')" class="text-red-600 hover:text-red-800" title="Xóa"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

@endsection

