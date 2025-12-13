@extends('admin.layouts.dashboard')
@section('title', 'Thùng rác')
@section('header', 'Thùng rác đặt phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-white">Đơn đã hủy / Xóa</h1>
            <p class="text-sm text-gray-400 mt-1">Quản lý các đơn đặt phòng bị hủy hoặc xóa tạm thời</p>
        </div>
        <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="p-4 bg-gray-800/50 border-b border-gray-800 flex items-center justify-between">
            <form id="bulkDeleteForm" method="POST" action="{{ route('admin.dat-phong.bulk-delete') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 shadow-lg shadow-red-900/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed" onclick="return confirm('CẢNH BÁO: Hành động này không thể hoàn tác. Xóa vĩnh viễn các đơn đã chọn?')">
                    <i class="fa-solid fa-dumpster-fire mr-2"></i> Xóa vĩnh viễn đã chọn
                </button>
            </form>
            <span class="text-xs text-gray-500 italic flex items-center">
                <i class="fa-solid fa-circle-info mr-1"></i> Dữ liệu xóa tại đây sẽ mất vĩnh viễn.
            </span>
        </div>

        <div class="overflow-x-auto">
            <form id="trashTableForm" method="POST" action="{{ route('admin.dat-phong.bulk-delete') }}">
                @csrf
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left w-10">
                                <input type="checkbox" id="selectAll" class="rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Mã Đơn</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Phòng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Ngày xóa</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800">
                        @foreach($datPhongs as $dp)
                        <tr class="hover:bg-gray-800/50 transition-colors group">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $dp->id }}" class="trash-checkbox rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-brand-gold group-hover:text-white transition-colors">
                                #{{ $dp->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-white">
                                {{ $dp->user->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($dp->chiTietDatPhongs->first()) 
                                    <span class="font-serif font-bold text-gray-300 bg-gray-800 px-2 py-1 rounded border border-gray-700">
                                        {{ $dp->chiTietDatPhongs->first()->phong->so_phong ?? '—' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $dp->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.dat-phong.xoa', $dp->id) }}" 
                                   class="text-red-500 hover:text-red-400 font-bold hover:underline transition-colors" 
                                   onclick="return confirm('Xóa vĩnh viễn đơn này?')">
                                    Xóa vĩnh viễn
                                </a>
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
    document.getElementById('selectAll').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.trash-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush