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

    <form action="{{ route('admin.dat-phong.bulk-delete') }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa các đơn đã chọn? Hành động này không thể hoàn tác.');">
        @csrf
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-bold">Danh sách lịch sử</h3>
            <button type="submit" class="h-10 px-4 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-all">
                <i class="fa-solid fa-trash-can mr-2"></i> Xóa hàng loạt
            </button>
        </div>

        <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                @if($datPhongs->isEmpty())
                    <div class="p-16 text-center">
                        <div class="inline-flex w-16 h-16 rounded-full bg-gray-800 items-center justify-center text-gray-600 mb-4">
                            <i class="fa-solid fa-clipboard-list text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-300">Không có lịch sử đặt phòng</h3>
                        <p class="text-gray-500 mt-1">Thử thay đổi bộ lọc hoặc kiểm tra lại dữ liệu.</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded bg-gray-700 border-gray-600" onclick="document.querySelectorAll('.row-check').forEach(cb=>cb.checked=this.checked)" title="Chọn tất cả">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Mã đơn</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Phòng</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Ngày đặt</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng tiền</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800">
                            @foreach($datPhongs as $dp)
                            <tr class="hover:bg-gray-800/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="ids[]" value="{{ $dp->id }}" class="row-check rounded bg-gray-700 border-gray-600">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm font-bold text-brand-gold group-hover:text-white transition-colors">
                                    #{{ $dp->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-white">{{ $dp->user->name ?? 'Guest' }}</div>
                                    <div class="text-xs text-gray-500">{{ $dp->user->phone ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300">{{ $dp->chiTietDatPhongs->pluck('loaiPhong.ten_loai_phong')->join(', ') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $dp->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($dp->payment_status == 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-900/30 text-green-400 border border-green-800">
                                            <i class="fa-solid fa-check mr-1"></i> Đã thanh toán
                                        </span>
                                    @elseif($dp->payment_status == 'awaiting_payment')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-900/30 text-amber-300 border border-amber-800">
                                            <i class="fa-solid fa-clock mr-1"></i> Chờ thanh toán
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-900/30 text-brand-gold border border-yellow-800">
                                            <i class="fa-solid fa-clock mr-1"></i> Chờ xử lý
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="font-serif font-bold text-lg {{ $dp->payment_status == 'paid' ? 'text-white' : 'text-gray-500' }}">
                                        {{ number_format($dp->tong_tien, 0, ',', '.') }} đ
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.dat-phong.hoa-don', $dp->id) }}" class="text-gray-500 hover:text-brand-gold font-bold transition-colors inline-flex items-center gap-1 group/btn">
                                        Hóa đơn <i class="fa-solid fa-chevron-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-800">
                {{ $datPhongs->withQueryString()->links() }}
            </div>
        </div>
    </form>
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