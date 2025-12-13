@extends('admin.layouts.dashboard')
@section('title', 'Danh sách Hạng phòng')
@section('header', 'Quản lý Hạng phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Danh sách Hạng phòng</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý các loại phòng và giá niêm yết</p>
        </div>
        <a href="{{ route('admin.loai-phong.them') }}" class="flex items-center px-5 py-2.5 bg-brand-900 text-brand-gold rounded-xl font-bold hover:bg-gray-800 shadow-md transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus mr-2"></i> Thêm hạng phòng
        </a>
    </div>

    <div class="bg-white p-4 rounded-xl mb-6">
        <form method="GET" action="{{ route('admin.loai-phong') }}" class="flex items-center gap-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm tên hạng phòng..." class="w-64 h-10 pl-10 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold" />
            </div>
            <button type="submit" class="h-10 px-4 bg-gray-100 text-gray-700 font-bold rounded-lg">Tìm</button>
            @if(request('q'))
                <a href="{{ route('admin.loai-phong') }}" class="h-10 px-4 bg-white border border-gray-300 text-gray-500 rounded-lg">Đặt lại</a>
            @endif
        </form>
    </div>
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            @if($loaiPhongs->isEmpty())
                <div class="p-12 text-center flex flex-col items-center justify-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-bed text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Chưa có hạng phòng nào</h3>
                    <p class="text-sm mt-1">Hãy thêm hạng phòng đầu tiên để bắt đầu kinh doanh.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-brand-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Hình ảnh</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Tên hạng phòng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Giá niêm yết</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Tiện nghi</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-brand-gold uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($loaiPhongs as $lp)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-16 w-24 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                                    <img class="h-full w-full object-cover"
                                         src="{{ $lp->hinh_anh ? asset($lp->hinh_anh) : 'https://placehold.co/600x400?text=No+Image' }}"
                                         alt="{{ $lp->ten_loai_phong }}">
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $lp->ten_loai_phong }}</div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($lp->mo_ta ?? '', 50) }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-base font-serif font-bold text-brand-900">{{ number_format($lp->gia, 0, ',', '.') }} đ</span>
                                <span class="text-xs text-gray-500 block">/ đêm</span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($lp->tienNghis as $tn)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            {{ $tn->ten_tien_nghi }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Chưa cập nhật</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.loai-phong.sua', ['id' => $lp->id]) }}" class="text-gray-400 hover:text-brand-gold transition-colors p-2 rounded-full hover:bg-yellow-50" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </a>
                                    <a href="{{ route('admin.loai-phong.xoa', ['id' => $lp->id]) }}"
                                       onclick="return confirm('Cảnh báo: Xóa loại phòng này sẽ xóa luôn các phòng thuộc loại này. Tiếp tục?')"
                                       class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50" title="Xóa">
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
        <div class="p-4">{{ $loaiPhongs->withQueryString()->links() }}</div>
    </div>
</div>
@endsection