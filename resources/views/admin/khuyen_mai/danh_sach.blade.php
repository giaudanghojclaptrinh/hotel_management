@extends('admin.layouts.dashboard')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    <div class="toolbar mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Khuyến mãi</h1>
        <a href="{{ route('admin.khuyen-mai.them') }}" class="btn-primary inline-flex items-center gap-2 text-sm font-semibold">
            <i class="fa fa-plus"></i> Thêm khuyến mãi
        </a>
    </div>

    <div class="card-common">
        <div class="overflow-x-auto">
            @if($khuyenMais->isEmpty())
                <div class="p-6 text-center text-gray-500">Chưa có khuyến mãi nào. Hãy thêm mới.</div>
            @else
            <table class="table-common min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên khuyến mãi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã khuyến mãi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chiết khấu phần trăm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền giảm giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày bắt đầu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày kết thúc</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($khuyenMais as $km)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $km->ten_khuyen_mai }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $km->ma_khuyen_mai }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $km->chiet_khau_phan_tram }}%</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($km->so_tien_giam_gia ?? 0, 0, ',', '.') }} VND</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $km->ngay_bat_dau }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $km->ngay_ket_thuc }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            <a href="{{ route('admin.khuyen-mai.sua', ['id' => $km->id]) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="Sửa"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('admin.khuyen-mai.xoa', ['id' => $km->id]) }}" onclick="return confirm('Bạn có muốn xóa phòng {{ $km->ten_khuyen_mai }} không?')" class="text-red-600 hover:text-red-800" title="Xóa"><i class="fa fa-trash"></i></a>
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
