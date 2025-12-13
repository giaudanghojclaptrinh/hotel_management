@extends('admin.layouts.dashboard')
@section('title', 'Thùng rác')
@section('header', 'Thùng rác đặt phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Đơn đã hủy / Xóa</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý các đơn đặt phòng bị hủy hoặc xóa tạm thời</p>
        </div>
        <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-brand-gold hover:text-brand-gold transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <form id="bulkDeleteForm" method="POST" action="{{ route('admin.dat-phong.bulk-delete') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 shadow-sm transition-all disabled:opacity-50" onclick="return confirm('Xóa vĩnh viễn các đơn đã chọn?')">
                    <i class="fa-solid fa-trash-can mr-2"></i> Xóa vĩnh viễn đã chọn
                </button>
            </form>
            <span class="text-xs text-gray-500 italic">* Hành động này không thể hoàn tác</span>
        </div>

        <div class="overflow-x-auto">
            <form id="trashTableForm" method="POST" action="{{ route('admin.dat-phong.bulk-delete') }}">
                @csrf
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mã Đơn</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Phòng</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ngày xóa</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($datPhongs as $dp)
                        <tr class="hover:bg-red-50/30 transition-colors">
                            <td class="px-6 py-4"><input type="checkbox" name="ids[]" value="{{ $dp->id }}" class="trash-checkbox rounded border-gray-300 text-brand-gold focus:ring-brand-gold"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">#{{ $dp->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $dp->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($dp->chiTietDatPhongs->first()) 
                                    <span class="font-serif font-bold text-gray-700">{{ $dp->chiTietDatPhongs->first()->phong->so_phong ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dp->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.dat-phong.xoa', $dp->id) }}" class="text-red-600 hover:text-red-900 font-bold" onclick="return confirm('Xóa vĩnh viễn đơn này?')">
                                    Xóa vĩnh viễn
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $datPhongs->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Logic Select All giữ nguyên
document.getElementById('selectAll').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.trash-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>
@endpush