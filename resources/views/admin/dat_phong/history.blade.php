@extends('admin.layouts.dashboard')
@section('title', 'Lịch sử đặt phòng')
@section('header', 'Lịch sử đặt phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-white">Danh sách đã duyệt</h1>
            <p class="text-sm text-gray-400 mt-1">Các đơn đặt phòng đã hoàn thành hoặc đang hoạt động</p>
        </div>
        <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại Sơ đồ
        </a>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <div class="p-4 flex items-center justify-between">
                <form id="bulkDpForm" method="POST" action="{{ route('admin.dat-phong.bulk-delete') }}">
                    @csrf
                    <button type="submit" class="h-10 px-4 bg-gray-800 border border-red-900/50 text-red-500 rounded-lg text-sm font-bold hover:bg-red-900/20 hover:border-red-500 shadow-sm transition-all" onclick="return confirm('Xóa các đơn được chọn?')">
                        <i class="fa-solid fa-trash-can mr-2"></i> Xóa chọn
                    </button>
                </form>
                <div class="text-sm text-gray-400">Đã chọn: <span id="dp-selected-count">0</span></div>
            </div>

            <form id="dpTableForm" method="POST" action="{{ route('admin.dat-phong.bulk-delete') }}">
            @csrf
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left w-10">
                            <input id="selectAllDp" type="checkbox" class="rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Mã Đơn</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Khách hàng</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Phòng</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Thời gian</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Trạng thái</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-400">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-800">
                    @foreach($datPhongs as $dp)
                    <tr class="hover:bg-gray-800/50 transition-colors group">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $dp->id }}" class="dp-checkbox rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-brand-gold group-hover:text-white transition-colors">
                            #{{ $dp->id }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-white">{{ $dp->user->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $dp->user->email ?? '' }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($dp->chiTietDatPhongs->first()) 
                                <span class="px-2 py-1 rounded bg-gray-800 border border-gray-700 font-serif font-bold text-gray-300 text-xs">
                                    {{ $dp->chiTietDatPhongs->first()->phong->so_phong ?? '—' }}
                                </span>
                            @else
                                <span class="text-gray-600">—</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            {{ \Carbon\Carbon::parse($dp->ngay_den)->format('d/m') }} 
                            <i class="fa-solid fa-arrow-right mx-1 text-xs text-gray-600"></i> 
                            {{ \Carbon\Carbon::parse($dp->ngay_di)->format('d/m/Y') }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/30 text-green-400 border border-green-800">
                                {{ ucfirst($dp->trang_thai) }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($dp->hoaDon)
                            <a href="{{ route('admin.dat-phong.hoa-don', $dp->id) }}" class="text-gray-400 hover:text-brand-gold font-bold transition-colors flex items-center justify-end gap-1">
                                Chi tiết <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                            @else
                            <span class="text-gray-600 italic text-xs">Chưa có HĐ</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </form>
        </div>
        
        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-800">
            {{ $datPhongs->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const selectAll = document.getElementById('selectAllDp');
        const checkboxes = document.querySelectorAll('.dp-checkbox');
        const countEl = document.getElementById('dp-selected-count');
        function update(){
            const c = document.querySelectorAll('.dp-checkbox:checked').length;
            if(countEl) countEl.textContent = c;
            if(selectAll) selectAll.checked = (c>0 && c===checkboxes.length);
        }
        if(selectAll){ selectAll.addEventListener('change', ()=>{ checkboxes.forEach(cb=>cb.checked = selectAll.checked); update(); }); }
        checkboxes.forEach(cb=>cb.addEventListener('change', update));
        update();
    })();
</script>
@endpush