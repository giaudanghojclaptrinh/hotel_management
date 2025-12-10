@extends('admin.layouts.dashboard')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    <div class="toolbar mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Các hạng phòng</h1>
        <a href="{{ route('admin.loai-phong.them') }}" class="btn-primary inline-flex items-center gap-2 text-sm font-semibold">
            <i class="fa fa-plus"></i> Thêm loại phòng
        </a>
    </div>

    <div class="card-common">
        <div class="overflow-x-auto">
            @if($loaiPhongs->isEmpty())
                <div class="p-6 text-center text-gray-500">Chưa có loại phòng nào. Hãy thêm mới.</div>
            @else
            <table class="table-common min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên loại phòng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số người</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiện nghi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($loaiPhongs as $lp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $lp->ten_loai_phong }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($lp->gia ?? 0, 0, ',', '.') }} VND</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $lp->so_nguoi ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $lp->tien_nghi }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            <a href="{{ route('admin.loai-phong.sua', ['id' => $lp->id]) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="Sửa"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('admin.loai-phong.xoa', ['id' => $lp->id]) }}" onclick="return confirm('Bạn có muốn xóa loại phòng {{ $lp->ten_loai_phong }} không?')" class="text-red-600 hover:text-red-800" title="Xóa"><i class="fa fa-trash"></i></a>
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
