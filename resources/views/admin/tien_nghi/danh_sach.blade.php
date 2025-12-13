@extends('admin.layouts.dashboard')
@section('title', 'Danh sách Tiện nghi')
@section('header', 'Quản lý Tiện nghi')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Danh sách Tiện nghi</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý các tiện ích (Wifi, AC, TV...) của phòng</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-center">
            <form method="GET" action="{{ route('admin.tien-nghi') }}" class="relative flex items-center gap-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc mã" class="w-56 h-10 pl-10 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold" />
                <button type="submit" class="px-3 py-2 bg-brand-900 text-brand-gold rounded text-sm">Tìm</button>
                @if(request('q'))
                    <a href="{{ route('admin.tien-nghi') }}" class="px-3 py-2 bg-gray-100 text-gray-700 rounded text-sm">Đặt lại</a>
                @endif
            </form>

            <form id="bulkTnForm" method="POST" action="{{ route('admin.tien-nghi.bulk-delete') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white border border-gray-300 text-red-600 rounded-lg font-bold hover:bg-red-50 hover:border-red-300 transition-all shadow-sm" onclick="return confirm('Xóa các tiện nghi được chọn?')">
                    <i class="fa-solid fa-trash-can mr-2"></i> Xóa chọn
                </button>
            </form>
            
            <a href="{{ route('admin.tien-nghi.them') }}" class="flex items-center px-5 py-2 bg-brand-900 text-brand-gold rounded-lg font-bold hover:bg-gray-800 shadow-md transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Thêm mới
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            @if($tienNghis->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                    <p>Chưa có tiện nghi nào.</p>
                </div>
            @else
            <form id="tnTableForm" method="POST" action="{{ route('admin.tien-nghi.bulk-delete') }}">
            @csrf
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-brand-900">
                    <tr>
                        <th class="px-6 py-4 text-left w-10">
                            <input id="selectAllTn" type="checkbox" class="rounded border-gray-500 text-brand-gold focus:ring-brand-gold bg-gray-700">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Tên tiện nghi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Mã</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-brand-gold uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-brand-gold uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @foreach ($tienNghis as $tn)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $tn->id }}" class="tn-checkbox rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $tn->ten_tien_nghi }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-600 bg-gray-50 px-2 py-1 rounded w-fit">{{ $tn->ma_tien_nghi }}</td>
                        <td class="px-6 py-4 text-center text-xl text-brand-900">
                            @if($tn->icon)<i class="{{ $tn->icon }}"></i>@endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.tien-nghi.sua', ['id' => $tn->id]) }}" class="text-gray-400 hover:text-brand-gold transition-colors" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </a>
                                <a href="{{ route('admin.tien-nghi.xoa', ['id' => $tn->id]) }}" onclick="return confirm('Bạn có muốn xóa tiện nghi {{ $tn->ten_tien_nghi }} không?')" class="text-gray-400 hover:text-red-600 transition-colors" title="Xóa">
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
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">{{ $tienNghis->withQueryString()->links() }}</div>
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