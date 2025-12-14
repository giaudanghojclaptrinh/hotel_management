@extends('admin.layouts.dashboard')
@section('title', 'Quản lý Phản hồi Khách hàng')
@section('header', 'Phản hồi Khách hàng')

@section('content')
<div class="max-w-full mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">
            <i class="fa-solid fa-comment-dots mr-2 text-brand-gold"></i> Danh sách Phản hồi
        </h1>
        {{-- Nút có thể dùng để xem các phản hồi chưa xử lý --}}
        {{-- <a href="{{ route('admin.feedbacks', ['status' => 'pending']) }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm">
            Chờ xử lý ({{ $feedbacks->where('handled', false)->count() }})
        </a> --}}
    </div>

    {{-- Thêm hiển thị thông báo (nếu có) --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-900/20 border border-green-800 text-green-400 text-sm shadow-sm flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        <form id="bulk-delete-form" method="POST" action="{{ route('admin.feedbacks.bulk-delete') }}">
            @csrf
            @method('DELETE') {{-- Thêm method DELETE cho hành động xóa --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                
                <thead class="bg-gray-800/50">
                    <tr>
                        {{-- 1. Checkbox --}}
                        <th class="px-3 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-12">
                            <input type="checkbox" id="select-all" class="accent-brand-gold border-gray-600 rounded" title="Chọn tất cả">
                        </th>
                        {{-- 2. ID --}}
                        <th class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-16">ID</th>
                        {{-- 3. Người gửi --}}
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-40">Người gửi</th>
                        {{-- 4. Email --}}
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                        {{-- 5. Nội dung (Đã rút ngắn) --}}
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider max-w-xs">Tóm tắt nội dung</th>
                        {{-- 6. Trạng thái --}}
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-32">Trạng thái</th>
                        {{-- 7. Ngày gửi --}}
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-40">Ngày gửi</th>
                        {{-- 8. Hành động --}}
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-24">Hành động</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-800 bg-gray-900">
                    @foreach($feedbacks as $f)
                        <tr class="hover:bg-gray-800/50 transition-colors">
                            {{-- 1. Checkbox --}}
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <input type="checkbox" name="selected[]" value="{{ $f->id }}" class="row-checkbox accent-brand-gold border-gray-600 rounded" />
                            </td>
                            {{-- 2. ID --}}
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $f->id }}</td>
                            {{-- 3. Người gửi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-white">{{ $f->name }}</td>
                            {{-- 4. Email --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-gold font-medium">
                                {{ $f->email }}
                            </td>
                            {{-- 5. Nội dung (Đã rút ngắn xuống 40 ký tự) --}}
                            <td class="px-6 py-4 text-sm text-gray-400 truncate max-w-xs">
                                {{ Str::limit($f->message, 10) }}
                            </td>
                            {{-- 6. Trạng thái --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($f->handled)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-900/30 text-green-400 border border-green-800">
                                        <i class="fa-solid fa-check-circle mr-1"></i> Đã xử lý
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-900/30 text-red-400 border border-red-800">
                                        <i class="fa-solid fa-circle-xmark mr-1"></i> Chưa xử lý
                                    </span>
                                @endif
                            </td>
                            {{-- 7. Ngày gửi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $f->created_at->format('d/m/Y H:i') }}
                            </td>
                            {{-- 8. Hành động --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.feedbacks.show', $f) }}" class="px-3 py-1 bg-gray-700 text-white rounded-lg text-xs font-medium hover:bg-brand-gold hover:text-gray-900 transition-all">
                                    <i class="fa-solid fa-eye mr-1"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    
                    @if($feedbacks->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500 text-lg">
                                <i class="fa-solid fa-inbox mr-2"></i> Hiện chưa có phản hồi nào.
                            </td>
                        </tr>
                    @endif
                </tbody>
                </table>
            </div>
            
            {{-- Footer Bảng (Bulk Actions và Pagination) --}}
            <div class="p-4 bg-gray-900 border-t border-gray-800 flex items-center justify-between flex-wrap">
                
                {{-- Nút Xóa Hàng Loạt (Ban đầu ẩn) --}}
                <div id="bulk-actions" style="display: none;">
                    <button type="button" onclick="confirmBulkDelete()" class="px-4 py-2 bg-red-700 text-white rounded-lg text-sm font-bold hover:bg-red-800 transition-colors flex items-center shadow-md">
                        <i class="fa-solid fa-trash-alt mr-2"></i> Xóa các mục đã chọn (<span id="selected-count">0</span>)
                    </button>
                </div>

                {{-- Phân trang --}}
                <div class="ml-auto">
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const rows = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCountSpan = document.getElementById('selected-count');

        function updateBulkActions() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            selectedCountSpan.textContent = checkedCount;
            if (checkedCount > 0) {
                bulkActions.style.display = 'block';
            } else {
                bulkActions.style.display = 'none';
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                rows.forEach(r => r.checked = selectAll.checked);
                updateBulkActions();
            });
        }

        rows.forEach(r => {
            r.addEventListener('change', function() {
                // Nếu một hàng bị bỏ chọn, bỏ chọn "Select All"
                if (!this.checked) {
                    selectAll.checked = false;
                }
                // Nếu tất cả các hàng được chọn, chọn "Select All"
                const allChecked = Array.from(rows).every(r => r.checked);
                if (allChecked) {
                    selectAll.checked = true;
                }

                updateBulkActions();
            });
        });

        // Cập nhật trạng thái lần đầu khi tải trang
        updateBulkActions();
    });

    function confirmBulkDelete() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if (checkedCount > 0) {
            if (confirm(`Bạn có chắc chắn muốn xóa ${checkedCount} phản hồi đã chọn không? Hành động này không thể hoàn tác.`)) {
                document.getElementById('bulk-delete-form').submit();
            }
        } else {
            alert('Vui lòng chọn ít nhất một phản hồi để xóa.');
        }
    }
</script>
@endpush
@endsection