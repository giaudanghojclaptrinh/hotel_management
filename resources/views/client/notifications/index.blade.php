@extends('layouts.app')
@section('title', 'Thông báo của tôi')


@section('content')
<div class="notify-page-wrapper">
    <div class="container">
        
        <!-- Header -->
        <div class="notify-header">
            <h1 class="notify-title">
                <i class="fa-solid fa-bell"></i> Thông báo của bạn
            </h1>
            {{-- Hiển thị số lượng chưa đọc --}}
            @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                <span class="badge-unread">
                    {{ Auth::user()->unreadNotifications->count() }} tin mới
                </span>
            @endif
        </div>

        @if($notifications->isEmpty())
            <!-- Trạng thái trống -->
            <div class="empty-state animate-fade-in-up">
                <div class="empty-icon">
                    <i class="fa-regular fa-bell-slash"></i>
                </div>
                <h3 class="empty-title">Hộp thư trống</h3>
                <p class="empty-desc">Bạn hiện không có thông báo mới nào.</p>
                <a href="{{ route('trang_chu') }}" class="btn btn-outline" style="margin-top: 1.5rem;">Về trang chủ</a>
            </div>
        @else
            <!-- Toolbar -->
            <div class="notify-toolbar">
                <div class="toolbar-left">
                    <label class="checkbox-label">
                        <input type="checkbox" id="select-all-notifications" class="custom-checkbox" />
                        <span>Chọn tất cả</span>
                    </label>
                    <button id="bulk-delete-btn" 
                            data-route="{{ route('notifications.deleteMultiple') }}" 
                            class="btn-bulk-delete" 
                            disabled> 
                        <i class="fa-solid fa-trash-can"></i> Xóa đã chọn 
                    </button>
                </div>
                <div class="notify-count">
                    Tổng: <strong>{{ $notifications->total() }}</strong> thông báo
                </div>
            </div>

            <!-- Danh sách thông báo -->
            <div class="notify-list" id="notification-list">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                        $statusClass = $isUnread ? 'unread' : 'read';
                        $time = $notification->created_at->diffForHumans();
                    @endphp

                    @php $linkTo = null; @endphp
                    @if(isset($data['loai_phong_id']) && isset($data['reply_id']))
                        @php $linkTo = route('phong.chi-tiet', $data['loai_phong_id']) . '#reply-' . $data['reply_id']; @endphp
                    @endif

                    <div class="notify-item {{ $statusClass }}" data-id="{{ $notification->id }}">
                        <!-- Checkbox -->
                        <div class="pt-1">
                            <input type="checkbox" class="notification-checkbox custom-checkbox" data-id="{{ $notification->id }}" />
                        </div>

                        <!-- Icon -->
                        <div class="notify-icon-box">
                            <i class="fa-solid {{ $data['icon'] ?? 'fa-bell' }}"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="notify-content">
                            <p class="notify-text">
                                {{ $data['message'] }}
                            </p>
                            
                            <div class="notify-meta">
                                @if(isset($data['booking_id']))
                                    <span title="Mã đơn hàng"><i class="fa-solid fa-hashtag"></i> BK-{{ $data['booking_id'] }}</span>
                                @endif
                                <span title="Thời gian"><i class="fa-regular fa-clock"></i> {{ $time }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="notify-actions">
                            @if($linkTo)
                                <a href="{{ $linkTo }}" class="btn-view-detail">
                                    <span>Xem</span> <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            @elseif(isset($data['booking_id']))
                                <a href="{{ route('bookings.invoice', $data['booking_id']) }}" class="btn-view-detail">
                                    <span>Xem chi tiết</span> <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Phân trang: dùng partial để tái sử dụng style/logic --}}
            @include('partials.pagination', ['paginator' => $notifications])
        @endif
    </div>
</div>
@endsection