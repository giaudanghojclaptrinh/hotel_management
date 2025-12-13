@extends('admin.layouts.dashboard')
@section('title', 'Danh sách Tiện nghi')
@section('header', 'Quản lý Tiện nghi')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-white">Danh sách Tiện nghi</h1>
            <p class="text-sm text-gray-400 mt-1">Quản lý các tiện ích (Wifi, AC, TV...) của phòng</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-center">
            <form method="GET" action="{{ route('admin.tien-nghi') }}" class="relative flex items-center gap-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-500"></i>
                </div>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc mã" 
                       class="w-56 h-10 pl-10 rounded-lg bg-gray-800 border-gray-700 text-white placeholder-gray-500 focus:border-brand-gold focus:ring-brand-gold text-sm shadow-sm transition-all" />
                <button type="submit" class="px-4 py-2 bg-gray-800 border border-gray-700 text-gray-300 font-bold rounded-lg hover:text-brand-gold hover:border-brand-gold transition-all text-sm">
                    Tìm
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.tien-nghi') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 text-red-400 font-bold rounded-lg hover:bg-gray-700 transition-all text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>

            <form id="bulkTnForm" method="POST" action="{{ route('admin.tien-nghi.bulk-delete') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-800 border border-red-900/50 text-red-500 rounded-lg font-bold hover:bg-red-900/20 hover:border-red-500 shadow-sm transition-all text-sm" onclick="return confirm('Xóa các tiện nghi được chọn?')">
                    <i class="fa-solid fa-trash-can mr-2"></i> Xóa chọn
                </button>
            </form>
            
            <a href="{{ route('admin.tien-nghi.them') }}" class="flex items-center px-5 py-2 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all text-sm">
                <i class="fa-solid fa-plus mr-2"></i> Thêm mới
            </a>
        </div>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            @if($tienNghis->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-4 border border-gray-700 mx-auto">
                        <i class="fa-solid fa-box-open text-4xl text-gray-600"></i>
                    </div>
                    <p>Chưa có tiện nghi nào.</p>
                </div>
            @else
            <form id="tnTableForm" method="POST" action="{{ route('admin.tien-nghi.bulk-delete') }}">
            @csrf
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left w-10">
                            <input id="selectAllTn" type="checkbox" class="rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tên tiện nghi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Mã</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-800">
                    @foreach ($tienNghis as $tn)
                    <tr class="hover:bg-gray-800/50 transition-colors group">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $tn->id }}" class="tn-checkbox rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-white">{{ $tn->ten_tien_nghi }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-mono text-gray-400 bg-gray-800 border border-gray-700 px-2 py-1 rounded">{{ $tn->ma_tien_nghi }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-xl text-brand-gold">
                            @if($tn->icon)<i class="{{ $tn->icon }}"></i>@endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.tien-nghi.sua', ['id' => $tn->id]) }}" class="text-gray-500 hover:text-brand-gold transition-colors p-2 rounded-lg hover:bg-gray-800" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </a>
                                <a href="{{ route('admin.tien-nghi.xoa', ['id' => $tn->id]) }}" onclick="return confirm('Bạn có muốn xóa tiện nghi {{ $tn->ten_tien_nghi }} không?')" class="text-gray-500 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-gray-800" title="Xóa">
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
            {{ $tienNghis->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('selectAllTn')?.addEventListener('change', function(e){
    document.querySelectorAll('.tn-checkbox').forEach(cb => cb.checked = e.target.checked);
});
</script>
@endpush
@endsection