@extends('admin.layouts.dashboard')
@section('title', 'Danh sách Hạng phòng')
@section('header', 'Quản lý Hạng phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-white">Danh sách Hạng phòng</h1>
            <p class="text-sm text-gray-400 mt-1">Quản lý các loại phòng và giá niêm yết</p>
        </div>
        <a href="{{ route('admin.loai-phong.them') }}" class="flex items-center px-5 py-2.5 bg-brand-gold text-gray-900 rounded-xl font-bold hover:bg-white shadow-md transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus mr-2"></i> Thêm hạng phòng
        </a>
    </div>

    <div class="bg-gray-900 p-4 rounded-xl shadow-sm border border-gray-800 mb-6">
        <form method="GET" action="{{ route('admin.loai-phong') }}" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-500"></i>
                </div>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm tên hạng phòng..." 
                       class="w-full h-10 pl-10 rounded-lg bg-gray-800 border-gray-700 text-white placeholder-gray-500 focus:border-brand-gold focus:ring-brand-gold text-sm transition-all shadow-sm" />
            </div>
            <button type="submit" class="h-10 px-6 bg-gray-800 border border-gray-700 text-gray-300 font-bold rounded-lg hover:text-brand-gold hover:border-brand-gold transition-all">
                Tìm kiếm
            </button>
            @if(request('q'))
                <a href="{{ route('admin.loai-phong') }}" class="h-10 px-4 bg-gray-800 border border-gray-700 text-red-400 rounded-lg hover:bg-gray-700 flex items-center justify-center transition-all" title="Xóa bộ lọc">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-900/30 border border-green-600 text-green-400 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            @if($loaiPhongs->isEmpty())
                <div class="p-16 text-center flex flex-col items-center justify-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-4 border border-gray-700">
                        <i class="fa-solid fa-bed text-3xl text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-300">Chưa có hạng phòng nào</h3>
                    <p class="text-sm mt-1 text-gray-500">Hãy thêm hạng phòng đầu tiên để bắt đầu kinh doanh.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Hình ảnh</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tên hạng phòng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Giá niêm yết</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tiện nghi</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800">
                        @foreach($loaiPhongs as $lp)
                        <tr class="hover:bg-gray-800/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-16 w-24 flex-shrink-0 overflow-hidden rounded-lg border border-gray-700 shadow-sm bg-gray-800">
                                    <img class="h-full w-full object-cover"
                                         src="{{ $lp->hinh_anh ? asset($lp->hinh_anh) : 'https://placehold.co/600x400?text=No+Image' }}"
                                         alt="{{ $lp->ten_loai_phong }}">
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-white">{{ $lp->ten_loai_phong }}</div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($lp->mo_ta ?? '', 50) }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-base font-serif font-bold text-brand-gold">{{ number_format($lp->gia, 0, ',', '.') }} đ</span>
                                <span class="text-xs text-gray-500 block">/ đêm</span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($lp->tienNghis as $tn)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-800 text-gray-300 border border-gray-700">
                                            {{ $tn->ten_tien_nghi }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-600 italic">Chưa cập nhật</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.loai-phong.sua', ['id' => $lp->id]) }}" class="text-gray-500 hover:text-brand-gold transition-colors p-2 rounded-lg hover:bg-gray-800" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </a>
                                    <a href="{{ route('admin.loai-phong.xoa', ['id' => $lp->id]) }}"
                                       onclick="return confirm('Cảnh báo: Xóa loại phòng này sẽ xóa luôn các phòng thuộc loại này. Tiếp tục?')"
                                       class="text-gray-500 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-gray-800" title="Xóa">
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
        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-800">
            {{ $loaiPhongs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection