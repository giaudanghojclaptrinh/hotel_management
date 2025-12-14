@extends('layouts.app')
@section('title', $room->ten_loai_phong)

@vite(['resources/css/client/rooms.css', 'resources/js/client/rooms.js'])

@section('content')

<div class="room-detail-container">
    <div class="container">
        
        <nav class="breadcrumb">
            <a href="{{ route('trang_chu') }}">Trang chủ</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('phong.danh-sach') }}">Phòng nghỉ</a>
            <span class="text-gray-300">/</span>
            <span class="active">{{ $room->ten_loai_phong }}</span>
        </nav>

        <div class="detail-grid">
            
            <div class="left-column">
                
                <div class="room-gallery-main">
                    <img src="{{ $room->hinh_anh ? asset($room->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                         alt="{{ $room->ten_loai_phong }}">
                    
                    <div class="room-gallery-overlay"></div>
                    
                    <div class="room-title-overlay">
                        <h1 class="room-detail-title">{{ $room->ten_loai_phong }}</h1>
                        
                        {{-- PHẦN 1: HIỂN THỊ ĐÁNH GIÁ TRUNG BÌNH Ở ĐÂY (TOP) --}}
                        @php
                            if (\Illuminate\Support\Facades\Schema::hasTable('reviews')) {
                                $avgRating = $room->reviews()->avg('rating') ?: 0;
                                $reviewsCount = $room->reviews()->count();
                            } else {
                                $avgRating = 0;
                                $reviewsCount = 0;
                            }
                            $avgRatingFormatted = number_format($avgRating, 1);
                        @endphp

                        <div class="rating-banner" style="display:flex; gap:10px; align-items:center; margin: 10px 0;">
                            <div class="stars" style="color: var(--primary-gold); font-size: 1.1rem;">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= round($avgRating))
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star" style="opacity: 0.5;"></i>
                                    @endif
                                @endfor
                            </div>
                            <div style="color: white; font-weight: 600;">
                                <span style="font-size: 1.2rem;">{{ $avgRatingFormatted }}/5</span>
                                <span style="font-size: 0.9rem; font-weight: normal; opacity: 0.9; margin-left: 5px;">
                                    ({{ $reviewsCount }} lượt đánh giá)
                                </span>
                            </div>
                        </div>
                        {{-- KẾT THÚC PHẦN ĐÁNH GIÁ TOP --}}

                        <div class="room-meta-flex">
                            <span><i class="fa-solid fa-user-group"></i> {{ $room->so_nguoi }} Khách</span>
                            <span><i class="fa-solid fa-ruler-combined"></i> {{ $room->dien_tich ?? '--' }} m²</span>
                            <span><i class="fa-solid fa-bed"></i> 1 Giường King</span>
                        </div>
                    </div>
                </div>
                
                <div class="room-desc-box">
                    <h3 class="room-desc-title">Mô tả phòng</h3>
                    <div class="desc-content">
                        <p class="mb-4">{{ $room->mo_ta ?? 'Chưa có mô tả chi tiết cho hạng phòng này.' }}</p>
                        <p>Được thiết kế với phong cách hiện đại pha lẫn nét cổ điển, phòng {{ $room->ten_loai_phong }} mang đến không gian nghỉ dưỡng lý tưởng. Nội thất cao cấp, ánh sáng tự nhiên và các tiện ích công nghệ cao sẽ làm hài lòng những vị khách khó tính nhất.</p>
                    </div>
                </div>

                <div class="amenities-box">
                    <h3 class="room-desc-title" style="margin-bottom: 2rem;">Tiện nghi cao cấp</h3>
                    @if($room->tienNghis && $room->tienNghis->count() > 0)
                        <div class="amenities-grid">
                            @foreach($room->tienNghis as $tn)
                                <div class="amenity-item">
                                    <div class="amenity-icon-box">
                                        <i class="{{ $tn->icon ?? 'fa-solid fa-check' }}"></i>
                                    </div>
                                    <span>{{ $tn->ten_tien_nghi }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted italic">Đang cập nhật danh sách tiện nghi...</p>
                    @endif
                </div>
            </div>

            <div class="right-column">
                <div class="booking-sidebar-card">
                    <div class="booking-price-header">
                        <div>
                            <span class="price-label">Giá tốt nhất</span>
                            <div class="flex items-baseline gap-1">
                                <span class="price-large">{{ number_format($room->gia, 0, ',', '.') }}đ</span>
                                <span class="price-unit">/đêm</span>
                            </div>
                        </div>
                        <div class="badge-breakfast">
                            <i class="fa-solid fa-mug-hot"></i> Ăn sáng
                        </div>
                    </div>

                    <form action="{{ route('phong.chi-tiet', $room->id) }}" method="GET" id="booking-date-form">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <div class="booking-form-group">
                            <label class="booking-label">Ngày nhận phòng</label>
                            <div class="date-input-wrapper">
                                <i class="fa-regular fa-calendar"></i>
                                <input type="date" 
                                    id="checkin_date" 
                                    name="checkin" 
                                    value="{{ request('checkin') }}" 
                                    onchange="document.getElementById('booking-date-form').submit()"
                                    required 
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="booking-form-group">
                            <label class="booking-label">Ngày trả phòng</label>
                            <div class="date-input-wrapper">
                                <i class="fa-regular fa-calendar-check"></i>
                                <input type="date" 
                                    id="checkout_date" 
                                    name="checkout" 
                                    value="{{ request('checkout') }}" 
                                    onchange="document.getElementById('booking-date-form').submit()"
                                    required 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                        </div>

                        @if(request('checkin') && request('checkout'))
                            @if($phongTrong > 0)
                                <div class="status-box status-available">
                                    <i class="fa-solid fa-circle-check text-lg"></i>
                                    <div>
                                        <span class="font-bold block">Còn {{ $phongTrong }} phòng trống!</span>
                                        <span class="text-xs">Từ {{ \Carbon\Carbon::parse(request('checkin'))->format('d/m') }} đến {{ \Carbon\Carbon::parse(request('checkout'))->format('d/m') }}.</span>
                                    </div>
                                </div>
                                
                                <div class="booking-form-group" style="margin-top: 15px;">
                                    <label class="flex items-center gap-2" style="cursor: pointer; font-size: 14px;">
                                        <input type="checkbox" id="accepted_terms_detail" style="cursor: pointer; width: 18px; height: 18px;">
                                        <span>Tôi đồng ý với <a href="#" style="color: #2563eb; text-decoration: underline;">điều khoản & điều kiện</a></span>
                                    </label>
                                </div>
                                
                                <button type="submit" 
                                    id="btn-submit-booking"
                                    formaction="{{ route('booking.create') }}"
                                    formmethod="GET"
                                    class="btn-book-now">
                                    <span>ĐẶT PHÒNG NGAY</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            @else
                                <div class="status-box status-soldout">
                                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                                    <div>
                                        <span class="font-bold block">Hết phòng!</span>
                                        <span class="text-xs">Không còn phòng trống trong khoảng thời gian này.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-book-now btn-disabled" disabled>
                                    TẠM HẾT PHÒNG
                                </button>
                            @endif
                        @else
                            <div class="status-box status-available">
                                <i class="fa-solid fa-calendar-alt text-lg"></i>
                                <div>
                                    <span class="font-bold block">Chọn ngày để kiểm tra</span>
                                    <span class="text-xs">Tổng số phòng: {{ $room->phongs->where('tinh_trang', '!=', 'maintenance')->count() }}.</span>
                                </div>
                            </div>
                             <button type="submit" 
                                    formaction="{{ route('booking.create') }}"
                                    formmethod="GET"
                                    class="btn-book-now btn-disabled" 
                                    disabled>
                                <span>ĐẶT PHÒNG NGAY</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        @endif
                        
                        <p class="booking-note">
                            * Bạn sẽ được yêu cầu đăng nhập để hoàn tất đặt phòng.
                        </p>
                    </form>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const checkbox = document.getElementById('accepted_terms_detail');
                            const bookingBtn = document.getElementById('btn-submit-booking');
                            
                            if (checkbox && bookingBtn) {
                                // Disable button initially
                                bookingBtn.classList.add('btn-disabled');
                                bookingBtn.style.opacity = '0.5';
                                bookingBtn.style.cursor = 'not-allowed';
                                
                                // Enable/disable button based on checkbox
                                checkbox.addEventListener('change', function() {
                                    if (this.checked) {
                                        bookingBtn.classList.remove('btn-disabled');
                                        bookingBtn.style.opacity = '1';
                                        bookingBtn.style.cursor = 'pointer';
                                    } else {
                                        bookingBtn.classList.add('btn-disabled');
                                        bookingBtn.style.opacity = '0.5';
                                        bookingBtn.style.cursor = 'not-allowed';
                                    }
                                });
                                
                                // Prevent form submission if not checked
                                bookingBtn.addEventListener('click', function(e) {
                                    if (!checkbox.checked) {
                                        e.preventDefault();
                                        alert('Vui lòng đồng ý với điều khoản & điều kiện để tiếp tục đặt phòng.');
                                        return false;
                                    }
                                });
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-6">
    <section class="reviews-section">
        <div class="reviews-inner">
            <h2 class="reviews-title">Bình luận của khách hàng</h2>

            {{-- Đã xóa phần tổng hợp sao (Aggregate rating banner) ở đây vì đã đưa lên top --}}

            @auth
                @error('review')
                    <div class="flash-error" style="margin-bottom:0.75rem;color:#f87171">{{ $message }}</div>
                @enderror
                {{-- Rating form (one-time) --}}
                @php
                    $userHasRated = false;
                    if (\Illuminate\Support\Facades\Schema::hasTable('reviews') && auth()->check()) {
                        $userHasRated = (bool) $room->reviews()->where('user_id', auth()->id())->where('rating', '>', 0)->whereNull('parent_id')->exists();
                    }
                @endphp

                @if(!$userHasRated)
                    <form action="{{ route('reviews.store') }}" method="POST" class="review-form" id="rating-form">
                        @csrf
                        <input type="hidden" name="loai_phong_id" value="{{ $room->id }}">
                        <div class="form-row" style="align-items:center; gap:1rem;">
                            <label>Đánh giá (một lần)</label>
                            <div class="star-rating" id="star-rating" role="radiogroup" aria-label="Đánh giá sao">
                                <span class="star" data-value="1" aria-hidden><i class="fa-regular fa-star"></i></span>
                                <span class="star" data-value="2" aria-hidden><i class="fa-regular fa-star"></i></span>
                                <span class="star" data-value="3" aria-hidden><i class="fa-regular fa-star"></i></span>
                                <span class="star" data-value="4" aria-hidden><i class="fa-regular fa-star"></i></span>
                                <span class="star" data-value="5" aria-hidden><i class="fa-regular fa-star"></i></span>
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="">
                            <div style="margin-left:auto">
                                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="flash-note" style="margin-bottom:0.75rem; color:var(--text-muted);">Bạn đã đánh giá hạng phòng này.</div>
                @endif

                {{-- Comment form (can submit multiple times) --}}
                <form action="{{ route('reviews.store') }}" method="POST" class="review-form" id="comment-form">
                    @csrf
                    <input type="hidden" name="loai_phong_id" value="{{ $room->id }}">
                    <div class="form-row">
                        <label>Viết bình luận</label>
                        <textarea name="comment" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về phòng này..."></textarea>
                        @error('comment') <div class="text-sm" style="color:#f87171">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                    </div>
                </form>
            @else
                <div class="review-login-cta">
                    <p>Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để viết bình luận.</p>
                </div>
            @endauth

            <div class="review-list mt-4">
                @php
                    if (\Illuminate\Support\Facades\Schema::hasTable('reviews')) {
                        $avg = $room->reviews()->where('rating', '>', 0)->avg('rating') ?: 0;
                        $avgRating = number_format($avg, 1);
                        // load only top-level comments that have content; include replies and users
                        $reviews = $room->reviews()
                            ->whereNull('parent_id')
                            ->whereNotNull('comment')
                            ->with(['user','replies.user'])
                            ->latest()
                            ->paginate(6);
                    } else {
                        $avgRating = number_format(0,1);
                        $reviews = null;
                    }
                @endphp

                @if($reviews && $reviews->count() > 0)
                    @foreach($reviews as $rev)
                        <div class="review-card">
                            <div class="review-head" style="display:flex; gap:12px; align-items:flex-start;">
                                <div class="rev-avatar"><i class="fa-solid fa-circle-user" style="font-size:28px; color:var(--text-muted)"></i></div>
                                <div class="rev-main" style="flex:1">
                                    <div style="display:flex; justify-content:space-between;align-items:center">
                                        <div style="font-weight:700; color:var(--primary-dark);">{{ $rev->user->name ?? 'Khách ẩn danh' }}</div>
                                        <div style="font-size:0.85rem; color:var(--text-muted)">{{ $rev->created_at->diffForHumans() }}</div>
                                    </div>

                                    <div class="review-body" style="margin-top:0.5rem; color:var(--text-color); line-height:1.5">{{ $rev->comment }}</div>

                                    <div class="review-actions" style="margin-top:0.6rem; display:flex; gap:8px; align-items:center">
                                        <button type="button" class="reply-toggle" data-target="reply-form-{{ $rev->id }}">Trả lời</button>
                                        @auth
                                            @if(auth()->id() === $rev->user_id)
                                                <span style="color:var(--text-muted)">Bạn</span>
                                            @endif
                                        @endauth
                                    </div>

                                    {{-- reply form (hidden) --}}
                                    <div id="reply-form-{{ $rev->id }}" class="reply-form-wrapper" style="display:none; margin-top:0.6rem;">
                                        @auth
                                            <form action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="loai_phong_id" value="{{ $room->id }}">
                                                <input type="hidden" name="parent_id" value="{{ $rev->id }}">
                                                <textarea name="comment" rows="2" placeholder="Viết trả lời..." required style="width:100%;"></textarea>
                                                <div style="margin-top:6px; display:flex; gap:8px">
                                                    <button type="submit" class="btn btn-sm">Gửi</button>
                                                    <button type="button" class="btn btn-sm btn-cancel-reply" data-target="reply-form-{{ $rev->id }}">Hủy</button>
                                                </div>
                                            </form>
                                        @else
                                            <div><a href="{{ route('login') }}">Đăng nhập</a> để trả lời</div>
                                        @endauth
                                    </div>

                                    {{-- replies list --}}
                                    @if($rev->replies && $rev->replies->count())
                                        @php
                                            $totalReplies = $rev->replies->count();
                                            $visibleReplies = $rev->replies->slice(0,5);
                                            $hiddenReplies = $rev->replies->slice(5);
                                        @endphp

                                        <div class="review-replies" style="margin-top:0.8rem;">
                                            @foreach($visibleReplies as $reply)
                                                <div id="reply-{{ $reply->id }}" class="reply-card" style="display:flex; gap:10px; padding:8px 0; border-top:1px solid rgba(0,0,0,0.03)">
                                                    <div class="reply-avatar"><i class="fa-solid fa-circle-user" style="font-size:20px; color:var(--text-muted)"></i></div>
                                                    <div style="flex:1">
                                                        <div style="font-weight:700">{{ $reply->user->name ?? 'Khách ẩn danh' }} <span style="font-weight:400; color:var(--text-muted); font-size:0.85rem">· {{ $reply->created_at->diffForHumans() }}</span></div>
                                                        <div style="margin-top:4px">{{ $reply->comment }}</div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($hiddenReplies->count() > 0)
                                                <div class="hidden-replies-wrapper" id="hidden-replies-{{ $rev->id }}" style="display:none;">
                                                    @foreach($hiddenReplies as $reply)
                                                        <div class="reply-card" style="display:flex; gap:10px; padding:8px 0; border-top:1px solid rgba(0,0,0,0.03)">
                                                            <div class="reply-avatar"><i class="fa-solid fa-circle-user" style="font-size:20px; color:var(--text-muted)"></i></div>
                                                            <div style="flex:1">
                                                                <div style="font-weight:700">{{ $reply->user->name ?? 'Khách ẩn danh' }} <span style="font-weight:400; color:var(--text-muted); font-size:0.85rem">· {{ $reply->created_at->diffForHumans() }}</span></div>
                                                                <div style="margin-top:4px">{{ $reply->comment }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div style="margin-top:6px;">
                                                    <button type="button" class="btn btn-sm btn-show-more-replies" data-target="hidden-replies-{{ $rev->id }}">Xem thêm {{ $hiddenReplies->count() }} trả lời</button>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-4">
                        {{ $reviews->withQueryString()->links() }}
                    </div>

                @else
                    <p class="text-muted" style="text-align:center; padding:2rem;">Chưa có bình luận nào cho hạng phòng này.</p>
                @endif
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Star rating interaction
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingInput = document.getElementById('rating-input');

    function setRating(value){
        ratingInput.value = value;
        stars.forEach(s => {
            const v = parseInt(s.getAttribute('data-value'));
            const icon = s.querySelector('i');
            if (v <= value) {
                icon.classList.remove('fa-regular'); icon.classList.add('fa-solid');
                s.classList.add('filled');
            } else {
                icon.classList.remove('fa-solid'); icon.classList.add('fa-regular');
                s.classList.remove('filled');
            }
        });
    }

    stars.forEach(s => {
        s.addEventListener('click', function(){
            const v = parseInt(this.getAttribute('data-value'));
            setRating(v);
        });
        s.addEventListener('mouseenter', function(){
            const v = parseInt(this.getAttribute('data-value'));
            stars.forEach(ss => {
                const iv = parseInt(ss.getAttribute('data-value'));
                const icon = ss.querySelector('i');
                if (iv <= v) { icon.classList.remove('fa-regular'); icon.classList.add('fa-solid'); }
                else { icon.classList.remove('fa-solid'); icon.classList.add('fa-regular'); }
            });
        });
    });

    const starWrapper = document.getElementById('star-rating');
    if (starWrapper) {
        starWrapper.addEventListener('mouseleave', function(){
            const current = parseInt(ratingInput.value) || 0; setRating(current);
        });
    }

    // Reply toggles
    document.querySelectorAll('.reply-toggle').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.getAttribute('data-target');
            const el = document.getElementById(id);
            if (!el) return;
            el.style.display = (el.style.display === 'block') ? 'none' : 'block';
            if (el.style.display === 'block') { const ta = el.querySelector('textarea'); if (ta) ta.focus(); }
        });
    });

    document.querySelectorAll('.btn-cancel-reply').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.getAttribute('data-target');
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
    });
    // show more replies
    document.querySelectorAll('.btn-show-more-replies').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.getAttribute('data-target');
            const el = document.getElementById(id);
            if (!el) return;
            el.style.display = 'block';
            this.style.display = 'none';
        });
    });
});
</script>
@endpush