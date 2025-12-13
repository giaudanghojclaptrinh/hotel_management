@extends('admin.layouts.dashboard')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    <div class="toolbar mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Lịch sử Đơn đặt phòng (Đã duyệt)</h1>
        <a href="{{ route('admin.dat-phong') }}" class="text-sm text-blue-600">← Quay lại Sơ đồ phòng</a>
    </div>

    <div class="card-common bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
             <!-- (Bảng hiển thị danh sách đơn confirmed/paid giống như file danh_sach cũ) -->
             <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mã</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khách</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phòng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($datPhongs as $dp)
                    <tr>
                        <td class="px-6 py-4">#{{ $dp->id }}</td>
                        <td class="px-6 py-4">{{ $dp->user->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if($dp->chiTietDatPhongs->first()) 
                                {{ $dp->chiTietDatPhongs->first()->phong->so_phong ?? '—' }}
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $dp->ngay_den }} → {{ $dp->ngay_di }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($dp->trang_thai) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('admin.dat-phong.hoa-don', $dp->id) }}" class="text-indigo-600 hover:text-indigo-900">Chi tiết</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $datPhongs->links() }}</div>
        </div>
    </div>
</div>
@endsection