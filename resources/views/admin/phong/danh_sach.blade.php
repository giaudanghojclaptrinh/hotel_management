@extends('admin.layouts.dashboard')
@section('title', 'Danh sách Phòng')
@section('header', 'Quản lý Phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-serif font-bold text-white">Danh sách Phòng</h1>
            <p class="text-sm text-gray-400 mt-1">Quản lý số phòng và trạng thái vật lý</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
            <a href="{{ route('admin.phong.them') }}" class="h-10 px-5 bg-brand-gold text-gray-900 rounded-lg text-sm font-bold hover:bg-white shadow-md transition-all flex items-center justify-center whitespace-nowrap">
                <i class="fa-solid fa-plus mr-2"></i> Thêm phòng
            </a>
        </div>
    </div>

    {{-- Filter buttons với badges --}}
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('admin.phong') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ !request('tinh_trang') ? 'bg-brand-gold text-gray-900 shadow-md' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white' }}">
            <i class="fa-solid fa-border-all"></i>
            <span>Tất cả</span>
            @if(isset($statusCounts['all']) && $statusCounts['all'] > 0)
                <span class="ml-1 {{ !request('tinh_trang') ? 'bg-gray-900 text-brand-gold' : 'bg-gray-700 text-white' }} py-0.5 px-2 rounded-md text-[10px] font-bold">
                    {{ $statusCounts['all'] }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.phong', ['tinh_trang' => 'available'] + request()->except('tinh_trang')) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ request('tinh_trang') == 'available' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-green-400' }}">
            <i class="fa-solid fa-door-open"></i>
            <span>Phòng trống</span>
            @if(isset($statusCounts['available']) && $statusCounts['available'] > 0)
                <span class="ml-1 {{ request('tinh_trang') == 'available' ? 'bg-green-800 text-white' : 'bg-gray-700 text-green-400' }} py-0.5 px-2 rounded-md text-[10px] font-bold animate-pulse">
                    {{ $statusCounts['available'] }}
                </span>
            @endif
        </a>



        <a href="{{ route('admin.phong', ['tinh_trang' => 'cleaning'] + request()->except('tinh_trang')) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ request('tinh_trang') == 'cleaning' ? 'bg-amber-600 text-white shadow-md' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-amber-400' }}">
            <i class="fa-solid fa-broom"></i>
            <span>Đang dọn dẹp</span>
            @if(isset($statusCounts['cleaning']) && $statusCounts['cleaning'] > 0)
                <span class="ml-1 {{ request('tinh_trang') == 'cleaning' ? 'bg-amber-800 text-white' : 'bg-gray-700 text-amber-400' }} py-0.5 px-2 rounded-md text-[10px] font-bold animate-pulse">
                    {{ $statusCounts['cleaning'] }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.phong', ['tinh_trang' => 'maintenance'] + request()->except('tinh_trang')) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ request('tinh_trang') == 'maintenance' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-red-400' }}">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            <span>Bảo trì / Sửa chữa</span>
            @if(isset($statusCounts['maintenance']) && $statusCounts['maintenance'] > 0)
                <span class="ml-1 {{ request('tinh_trang') == 'maintenance' ? 'bg-red-800 text-white' : 'bg-gray-700 text-red-400' }} py-0.5 px-2 rounded-md text-[10px] font-bold animate-pulse">
                    {{ $statusCounts['maintenance'] }}
                </span>
            @endif
        </a>
    </div>

    {{-- Search và filter form --}}
    <form method="GET" action="{{ route('admin.phong') }}" class="mb-6">
        <input type="hidden" name="tinh_trang" value="{{ request('tinh_trang') }}">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative min-w-[200px]">
                <select name="loai_phong_id" class="w-full h-10 pl-3 pr-8 rounded-lg bg-gray-800 border-gray-700 text-white text-sm focus:border-brand-gold focus:ring-brand-gold cursor-pointer transition-all">
                    <option value="">-- Tất cả hạng phòng --</option>
                    @foreach($loaiPhongs as $lp)
                        <option value="{{ $lp->id }}" {{ request('loai_phong_id') == $lp->id ? 'selected' : '' }}>
                            {{ $lp->ten_loai_phong }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-500"></i>
                </div>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm số phòng..." 
                       class="w-full h-10 pl-10 rounded-lg bg-gray-800 border-gray-700 text-white text-sm focus:border-brand-gold focus:ring-brand-gold placeholder-gray-500 transition-all">
            </div>

            <button type="submit" class="h-10 px-4 bg-gray-800 border border-gray-700 text-gray-300 font-bold rounded-lg hover:text-brand-gold hover:border-brand-gold transition-all flex items-center justify-center">
                <i class="fa-solid fa-filter mr-2"></i> Lọc
            </button>

            <a href="{{ route('admin.phong') }}" class="h-10 px-4 bg-gray-800 border border-gray-700 text-red-400 font-bold rounded-lg hover:bg-gray-700 transition-all flex items-center justify-center" title="Đặt lại bộ lọc">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-900/30 border border-green-600 text-green-400 flex items-center shadow-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            @if($phongs->isEmpty())
                <div class="p-16 text-center flex flex-col items-center justify-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-4 border border-gray-700">
                        <i class="fa-solid fa-door-open text-3xl text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-300">Không tìm thấy phòng nào</h3>
                    <p class="text-sm mt-1">Thử thay đổi bộ lọc hoặc thêm phòng mới.</p>
                </div>
            @else
            <div class="p-4 flex items-center justify-between">
                <form id="bulkPhongForm" method="POST" action="{{ route('admin.phong.bulk-delete') }}">
                    @csrf
                    <button type="submit" class="h-10 px-4 bg-gray-800 border border-red-900/50 text-red-500 rounded-lg text-sm font-bold hover:bg-red-900/20 hover:border-red-500 shadow-sm transition-all" onclick="return confirm('Xóa các phòng được chọn?')">
                        <i class="fa-solid fa-trash-can mr-2"></i> Xóa chọn
                    </button>
                </form>
                <div class="text-sm text-gray-400">Đã chọn: <span id="phong-selected-count">0</span></div>
            </div>
                <form id="phongTableForm" method="POST" action="{{ route('admin.phong.bulk-delete') }}">
                @csrf
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left w-10">
                                <input id="selectAllPhong" type="checkbox" class="rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Số phòng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Hạng phòng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Giá niêm yết</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Tình trạng</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800">
                        @foreach($phongs as $phong)
                        <tr class="hover:bg-gray-800/50 transition-colors group">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $phong->id }}" class="phong-checkbox rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center text-brand-gold font-serif font-bold group-hover:bg-brand-gold group-hover:text-gray-900 transition-colors">
                                        {{ $phong->so_phong }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">{{ $phong->loaiPhong->ten_loai_phong ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-serif text-brand-gold">{{ number_format($phong->loaiPhong->gia ?? 0, 0, ',', '.') }} đ</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusClass = 'bg-gray-800 text-gray-400 border-gray-700';
                                    $statusLabel = 'Không xác định';
                                    
                                    switch($phong->tinh_trang) {
                                        case 'available':
                                            $statusClass = 'bg-green-900/30 text-green-400 border-green-800';
                                            $statusLabel = 'Trống';
                                            break;
                                        case 'occupied':
                                            $statusClass = 'bg-red-900/30 text-red-400 border-red-800';
                                            $statusLabel = 'Đang ở';
                                            break;
                                        case 'maintenance':
                                            $statusClass = 'bg-yellow-900/30 text-yellow-400 border-yellow-800';
                                            $statusLabel = 'Bảo trì';
                                            break;
                                        case 'cleaning':
                                            $statusClass = 'bg-blue-900/30 text-blue-400 border-blue-800';
                                            $statusLabel = 'Dọn dẹp';
                                            break;
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.phong.sua', ['id' => $phong->id]) }}" class="text-gray-500 hover:text-brand-gold transition-colors p-2 rounded-lg hover:bg-gray-800" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </a>
                                    <a href="{{ route('admin.phong.xoa', ['id' => $phong->id]) }}" 
                                       onclick="return confirm('Bạn có chắc muốn xóa phòng này?')" 
                                       class="text-gray-500 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-gray-800" title="Xóa">
                                        <i class="fa-solid fa-trash-can text-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </form>
            @endif
        </div>
        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-800">
            {{ $phongs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const selectAll = document.getElementById('selectAllPhong');
        const checkboxes = document.querySelectorAll('.phong-checkbox');
        const countEl = document.getElementById('phong-selected-count');
        function update(){
            const c = document.querySelectorAll('.phong-checkbox:checked').length;
            if(countEl) countEl.textContent = c;
            if(selectAll) selectAll.checked = (c>0 && c===checkboxes.length);
        }
        if(selectAll){ selectAll.addEventListener('change', ()=>{ checkboxes.forEach(cb=>cb.checked = selectAll.checked); update(); }); }
        checkboxes.forEach(cb=>cb.addEventListener('change', update));
        update();
    })();
</script>
@endpush