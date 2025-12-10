@extends('admin.layouts.dashboard')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    
    <div class="toolbar mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Hạng phòng</h1>
        <a href="{{ route('admin.loai-phong.them') }}" class="btn-primary inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-semibold shadow-sm">
            <i class="fa fa-plus"></i> Thêm mới
        </a>
    </div>

    <div class="card-common bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            @if($loaiPhongs->isEmpty())
                <div class="p-10 text-center flex flex-col items-center justify-center text-gray-500">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
                    <p>Chưa có loại phòng nào. Hãy thêm mới ngay!</p>
                </div>
            @else
            <table class="table-common min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Hình ảnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên loại phòng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chi tiết</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Tiện nghi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($loaiPhongs as $lp)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        
                        <td class="px-6 py-4 text-sm">
                            <div class="h-12 w-20 rounded overflow-hidden border border-gray-200 bg-gray-100">
                                @if($lp->hinh_anh)
                                    <img src="{{ asset($lp->hinh_anh) }}" alt="{{ $lp->ten_loai_phong }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $lp->ten_loai_phong }}</div>
                            <div class="text-sm text-blue-600 font-medium">{{ number_format($lp->gia, 0, ',', '.') }} đ / đêm</div>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 space-y-1">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-user-group text-gray-400 w-4"></i>
                                <span>{{ $lp->so_nguoi }} người</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-ruler-combined text-gray-400 w-4"></i>
                                <span>{{ $lp->dien_tich ?? '--' }} m²</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                {{-- Duyệt qua quan hệ tienNghis --}}
                                @forelse($lp->tienNghis as $tn)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $tn->ten_tien_nghi }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400 italic">Không có tiện nghi</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.loai-phong.sua', ['id' => $lp->id]) }}" 
                                   class="text-gray-500 hover:text-blue-600 transition-colors" 
                                   title="Sửa">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </a>
                                <a href="{{ route('admin.loai-phong.xoa', ['id' => $lp->id]) }}" 
                                   onclick="return confirm('Cảnh báo: Bạn có chắc muốn xóa loại phòng này? Hành động này sẽ xóa cả các phòng vật lý thuộc loại này.')" 
                                   class="text-gray-500 hover:text-red-600 transition-colors" 
                                   title="Xóa">
                                    <i class="fa-solid fa-trash-can text-lg"></i>
                                </a>
                            </div>
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