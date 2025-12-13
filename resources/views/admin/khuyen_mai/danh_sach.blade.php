@extends('admin.layouts.dashboard')
@section('title', 'Danh sách Khuyến mãi')
@section('header', 'Quản lý Khuyến mãi')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-serif font-bold text-white">Chương trình Khuyến mãi</h1>
            <p class="text-sm text-gray-400 mt-1">Quản lý mã giảm giá và các chiến dịch ưu đãi</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
            <form method="GET" action="{{ route('admin.khuyen-mai') }}" class="flex flex-col md:flex-row gap-3 flex-1">
                
                <div class="relative min-w-[200px] flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-500"></i>
                    </div>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc mã..." 
                           class="w-full h-10 pl-10 rounded-lg bg-gray-800 border-gray-700 text-white placeholder-gray-500 focus:border-brand-gold focus:ring-brand-gold text-sm shadow-sm transition-all">
                </div>

                <div class="flex gap-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="h-10 rounded-lg bg-gray-800 border-gray-700 text-white text-sm focus:border-brand-gold focus:ring-brand-gold shadow-sm" title="Từ ngày">
                    <span class="self-center text-gray-500">-</span>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="h-10 rounded-lg bg-gray-800 border-gray-700 text-white text-sm focus:border-brand-gold focus:ring-brand-gold shadow-sm" title="Đến ngày">
                </div>

                <button type="submit" class="h-10 px-4 bg-gray-800 border border-gray-700 text-gray-300 font-bold rounded-lg hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Lọc
                </button>
                
                @if(request('q') || request('from_date') || request('to_date'))
                    <a href="{{ route('admin.khuyen-mai') }}" class="h-10 px-4 bg-gray-800 border border-gray-700 text-red-400 font-bold rounded-lg hover:bg-gray-700 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>

            <div class="flex gap-2">
                <form id="bulkKmForm" method="POST" action="{{ route('admin.khuyen-mai.bulk-delete') }}">
                    @csrf
                    <button type="submit" class="h-10 px-4 bg-gray-800 border border-red-900/50 text-red-500 rounded-lg text-sm font-bold hover:bg-red-900/20 hover:border-red-500 shadow-sm transition-all whitespace-nowrap" onclick="return confirm('Xóa các khuyến mãi được chọn?')">
                        <i class="fa-solid fa-trash-can mr-2"></i> Xóa chọn
                    </button>
                </form>

                <a href="{{ route('admin.khuyen-mai.them') }}" class="h-10 px-5 bg-brand-gold text-gray-900 font-bold rounded-lg hover:bg-white transition-all flex items-center justify-center whitespace-nowrap shadow-md">
                    <i class="fa-solid fa-plus mr-2"></i> Thêm mới
                </a>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            @if($khuyenMais->isEmpty())
                <div class="p-16 text-center flex flex-col items-center justify-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-4 border border-gray-700">
                        <i class="fa-solid fa-ticket text-3xl text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-300">Chưa có khuyến mãi nào</h3>
                    <p class="text-sm mt-1 text-gray-500">Bấm nút "Thêm mới" để tạo chương trình đầu tiên.</p>
                </div>
            @else
            <form id="kmTableForm" method="POST" action="{{ route('admin.khuyen-mai.bulk-delete') }}">
            @csrf
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left w-10">
                            <input id="selectAllKm" type="checkbox" class="rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tên chương trình</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Mã Code</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Giảm giá</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-800">
                    @foreach ($khuyenMais as $km)
                    <tr class="hover:bg-gray-800/50 transition-colors group">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $km->id }}" class="km-checkbox rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-white">{{ $km->ten_khuyen_mai }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-gray-800 text-brand-gold text-xs px-2 py-1 rounded border border-gray-700 font-mono font-bold tracking-wide">
                                {{ $km->ma_khuyen_mai }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($km->chiet_khau_phan_tram > 0)
                                <span class="text-sm font-bold text-green-400">-{{ $km->chiet_khau_phan_tram }}%</span>
                            @elseif($km->so_tien_giam_gia > 0)
                                <span class="text-sm font-bold text-blue-400">-{{ number_format($km->so_tien_giam_gia) }} đ</span>
                            @else
                                <span class="text-xs text-gray-600">Không rõ</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">
                            <div class="flex flex-col text-xs">
                                <span><span class="font-bold w-8 inline-block text-gray-600">Từ:</span> {{ \Carbon\Carbon::parse($km->ngay_bat_dau)->format('d/m/Y') }}</span>
                                <span><span class="font-bold w-8 inline-block text-gray-600">Đến:</span> {{ \Carbon\Carbon::parse($km->ngay_ket_thuc)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $now = \Carbon\Carbon::now();
                                $start = \Carbon\Carbon::parse($km->ngay_bat_dau);
                                $end = \Carbon\Carbon::parse($km->ngay_ket_thuc);
                            @endphp
                            @if($now->between($start, $end))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-900/30 text-green-400 border border-green-800">
                                    Đang chạy
                                </span>
                            @elseif($now->lt($start))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-900/30 text-yellow-500 border border-yellow-800">
                                    Sắp diễn ra
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-gray-800 text-gray-500 border border-gray-700">
                                    Hết hạn
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.khuyen-mai.sua', ['id' => $km->id]) }}" class="text-gray-500 hover:text-brand-gold transition-colors p-2 rounded-lg hover:bg-gray-800" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </a>
                                <a href="{{ route('admin.khuyen-mai.xoa', ['id' => $km->id]) }}" onclick="return confirm('Bạn có muốn xóa {{ $km->ten_khuyen_mai }} không?')" class="text-gray-500 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-gray-800" title="Xóa">
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
        @if(method_exists($khuyenMais, 'links'))
        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-800">
            {{ $khuyenMais->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('selectAllKm')?.addEventListener('change', function(e){
    document.querySelectorAll('.km-checkbox').forEach(cb => cb.checked = e.target.checked);
});
</script>
@endpush
@endsection