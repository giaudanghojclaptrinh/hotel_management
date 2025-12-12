@extends('layouts.app')
@section('title', 'Thông báo của tôi')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-serif font-bold text-gray-900 flex items-center">
                <i class="fa-solid fa-bell mr-3 text-brand-gold"></i> Thông báo của bạn
            </h1>
            {{-- Hiển thị tổng số lượng thông báo chưa đọc --}}
            @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                <span class="inline-block bg-red-600 text-white text-sm font-bold px-3 py-1 rounded-full shadow-md">
                    {{ Auth::user()->unreadNotifications->count() }} chưa đọc
                </span>
            @endif
        </div>

        @if($notifications->isEmpty())
            <!-- Trạng thái trống -->
            <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100 animate-fade-in-up">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-regular fa-bell-slash text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hộp thư trống</h3>
                <p class="text-gray-500">Bạn chưa có thông báo mới nào.</p>
            </div>
        @else
            <!-- Toolbar & Bulk Actions -->
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="select-all-notifications" class="form-checkbox rounded text-brand-900" />
                        <span class="text-sm text-gray-600">Chọn tất cả</span>
                    </label>
                    <button id="bulk-delete-btn" 
                            class="text-sm text-white bg-red-600 px-3 py-1 rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed" 
                            disabled> 
                        <i class="fa-solid fa-trash-can mr-1"></i> Xóa đã chọn 
                    </button>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tổng: {{ $notifications->total() }}</span>
                </div>
            </div>

            <!-- Danh sách thông báo -->
            <div class="space-y-4" id="notification-list">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                        
                        // Cấu hình màu sắc dựa trên trạng thái ĐÃ ĐỌC / CHƯA ĐỌC
                        $bgClass = $isUnread 
                            ? 'bg-white hover:bg-brand-50 border-brand-gold shadow-md' 
                            : 'bg-gray-100 hover:bg-gray-200 border-gray-200 opacity-80';
                            
                        $textClass = $isUnread ? 'text-brand-900 font-bold' : 'text-gray-600 font-medium';
                        $borderClass = $isUnread ? 'border-l-4 border-brand-gold' : 'border-l-4 border-gray-300';
                        $iconBg = $isUnread ? 'bg-brand-gold/10' : 'bg-white';
                        
                        $time = $notification->created_at->diffForHumans();
                    @endphp

                    <div class="p-4 rounded-xl {{ $borderClass }} {{ $bgClass }} transition duration-300 flex items-start gap-4 notification-item" data-id="{{ $notification->id }}">
                        
                        {{-- Checkbox --}}
                        <div class="flex items-start mr-2 mt-1">
                            <input type="checkbox" class="notification-checkbox rounded text-brand-900" data-id="{{ $notification->id }}" />
                        </div>

                        <div class="w-10 h-10 flex items-center justify-center rounded-full {{ $iconBg }} shadow-sm flex-shrink-0">
                            <i class="fa-solid {{ $data['icon'] ?? 'fa-bell' }} text-lg"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm {{ $textClass }} mb-1 line-clamp-2">
                                {{ $data['message'] }}
                            </p>
                            
                            <div class="text-xs text-gray-500 flex items-center gap-3">
                                <span title="Mã đơn hàng"><i class="fa-solid fa-hashtag mr-1"></i> BK-{{ $data['booking_id'] ?? 'N/A' }}</span>
                                <span title="Thời gian"><i class="fa-regular fa-clock mr-1"></i> {{ $time }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            {{-- Nút xem chi tiết --}}
                            @if(isset($data['booking_id']))
                            <a href="{{ route('bookings.invoice', $data['booking_id']) }}" 
                               class="text-xs text-brand-900 font-bold p-2 rounded-lg border border-gray-200 hover:bg-white transition-colors flex items-center space-x-1"
                               title="Xem chi tiết hóa đơn">
                                <span>Xem đơn</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                            @endif
                            
                            {{-- Xóa đơn lẻ đã được loại bỏ --}}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Phân trang -->
            @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Setup Axios ---
        if (typeof axios === 'undefined') {
            console.error('Axios chưa được tải. Không thể xóa thông báo.');
            return;
        }
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

        // --- DOM Elements for Bulk Actions ---
        const selectAll = document.getElementById('select-all-notifications');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const checkboxes = document.querySelectorAll('.notification-checkbox');

        // --- Helper Function ---
        const updateBulkState = () => {
            const selected = Array.from(document.querySelectorAll('.notification-checkbox:checked')).map(cb => cb.dataset.id);
            bulkDeleteBtn.disabled = selected.length === 0;
            return selected;
        };

        // ===============================================
        // 1. Logic Xóa hàng loạt (Bulk Delete)
        // ===============================================
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.notification-checkbox').forEach(cb => { cb.checked = checked; });
                updateBulkState();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                if (!cb.checked && selectAll) selectAll.checked = false;
                const all = Array.from(document.querySelectorAll('.notification-checkbox')).every(c => c.checked);
                if (all && selectAll) selectAll.checked = true;
                updateBulkState();
            });
        });
        
        // Sự kiện xóa hàng loạt
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                const selected = updateBulkState();
                if (selected.length === 0) return;
                if (!confirm('Xác nhận xóa ' + selected.length + ' thông báo đã chọn?')) return;

                bulkDeleteBtn.disabled = true;
                
                // [FIXED URL]: Sử dụng route('notifications.deleteMultiple')
                axios.post('{{ route('notifications.deleteMultiple') }}', { ids: selected }, { headers: { 'X-Force-Reload': '1' } })
                    .then(resp => {
                        if (resp.data.success) {
                            selected.forEach(id => {
                                const el = document.querySelector('.notification-item[data-id="' + id + '"]');
                                if (el) el.remove();
                            });
                            if (selectAll) selectAll.checked = false;
                            updateBulkState();
                            alert(resp.data.message);
                            // Cập nhật lại số đếm
                            if (window.fetchUnreadCount) window.fetchUnreadCount();
                        } else {
                            alert(resp.data.message || 'Lỗi khi xóa thông báo.');
                            bulkDeleteBtn.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Lỗi server khi xóa hàng loạt.');
                        bulkDeleteBtn.disabled = false;
                    });
            });
        }

        updateBulkState(); // Khởi tạo trạng thái nút
        
        // Gọi lại hàm đếm thông báo chưa đọc khi trang tải xong
        if (window.fetchUnreadCount) {
             window.fetchUnreadCount(); 
        }
    });
</script>
@endpush

@endsection