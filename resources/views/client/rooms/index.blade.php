@extends('layouts.app')
@section('title', 'Danh sách phòng nghỉ')

@section('content')

<div class="page-banner">
    <div class="banner-bg">
        <img src="{{ asset('uploads/home/home.png') }}" 
             alt="Luxury Hotel Background">
        <div class="banner-overlay"></div>
    </div>
    
    <div class="banner-content">
        <span class="banner-subtitle animate-fade-in-up">Luxury Collection</span>
        <h1 class="banner-title animate-fade-in-up">Danh sách phòng nghỉ</h1>
        <p class="banner-desc animate-fade-in-up">
            Khám phá không gian nghỉ dưỡng đẳng cấp, nơi từng chi tiết được chăm chút tỉ mỉ để mang lại sự thoải mái tuyệt đối cho bạn.
        </p>
    </div>
</div>

<div class="container pb-section">

    <button type="button" class="btn btn-primary mobile-filter-btn" onclick="document.querySelector('.sidebar-filter').classList.toggle('active')">
        <i class="fa-solid fa-filter mr-2"></i> Bộ lọc tìm kiếm
    </button>
    
    <div class="listing-layout">
        
        <aside class="sidebar-filter">
            <form action="{{ route('phong.danh-sach') }}" method="GET" id="filter-form">
                
                <div class="filter-header">
                    <span class="filter-title">Bộ lọc</span>
                    <a href="{{ route('phong.danh-sach') }}" class="btn-reset">Đặt lại</a>
                </div>

                <div class="filter-group">
                    <label class="filter-group-title">Khoảng giá (đêm)</label>
                    <div class="price-inputs">
                        <input type="number" name="min_price" class="form-input" 
                            placeholder="Từ" value="{{ request('min_price') }}"
                            min="0" step="100000"> 
                        <span class="price-separator">-</span>
                        <input type="number" name="max_price" class="form-input" 
                            placeholder="Đến" value="{{ request('max_price') }}"
                            min="0" step="100000">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-group-title">Hạng phòng</label>
                    <div class="checkbox-list" style="max-height: 200px; overflow-y: auto;">
                        @foreach($allLoaiPhongs as $type)
                            <label class="checkbox-item">
                                <input type="checkbox" name="room_types[]" value="{{ $type->id }}" 
                                    {{ in_array($type->id, request('room_types', [])) ? 'checked' : '' }}>
                                <span>{{ $type->ten_loai_phong }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-group-title">Sức chứa</label>
                    <div class="checkbox-list">
                        <label class="checkbox-item">
                            <input type="checkbox" name="capacity[]" value="1" 
                                {{ in_array('1', request('capacity', [])) ? 'checked' : '' }}>
                            <span>1 Khách</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="capacity[]" value="2" 
                                {{ in_array('2', request('capacity', [])) ? 'checked' : '' }}>
                            <span>2 Khách</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="capacity[]" value="3" 
                                {{ in_array('3', request('capacity', [])) ? 'checked' : '' }}>
                            <span>3 Khách</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="capacity[]" value="4" 
                                {{ in_array('4', request('capacity', [])) ? 'checked' : '' }}>
                            <span>Gia đình (4+ Khách)</span>
                        </label>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-group-title">Tiện nghi</label>
                    <div class="checkbox-list">
                        @foreach($tienNghis as $item)
                            <label class="checkbox-item">
                                <input type="checkbox" name="amenities[]" value="{{ $item->id }}" 
                                    {{ in_array($item->id, request('amenities', [])) ? 'checked' : '' }}>
                                <span>{{ $item->ten_tien_nghi }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-submit-wrap">
                    <button type="submit" class="btn btn-primary w-full">
                        Áp dụng lọc
                    </button>
                </div>
            </form>
        </aside>

        <main>
            <div class="filter-bar">
                <p class="filter-count">
                    Tìm thấy <strong>{{ $rooms->total() }}</strong> hạng phòng phù hợp
                </p>
                <div class="filter-actions">
                    <span class="text-sm text-gray-500 mr-2">Sắp xếp:</span>
                    <select name="sort" class="filter-select" form="filter-form" onchange="this.form.submit()">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Mặc định</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                    </select>
                </div>
            </div>

            <div class="room-grid">
                @forelse($rooms as $room)
                
                {{-- LOGIC: Hết phòng khi phongs_count = 0 --}}
                @php
                    $isSoldOut = $room->phongs_count == 0;
                @endphp

                <div class="room-card group {{ $isSoldOut ? 'sold-out' : '' }}">
                    
                    <div class="room-img-wrap">
                        <img src="{{ $room->hinh_anh ? asset($room->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                            alt="{{ $room->ten_loai_phong }}"
                            class="w-full h-full object-cover">
                        
                        @if($isSoldOut)
                            <div class="sold-out-badge">Hết phòng</div>
                        @else
                            <div class="room-price-tag">
                                <span class="room-price-amount">{{ number_format($room->gia, 0, ',', '.') }}đ</span>
                                <span class="room-price-unit">/đêm</span>
                            </div>
                        @endif
                        
                        <div class="room-overlay-hover"></div>
                    </div>
                    
                    <div class="room-body">
                        <h3>
                            <a href="{{ route('phong.chi-tiet', $room->id) }}" class="room-title-link">
                                {{ $room->ten_loai_phong }}
                            </a>
                        </h3>
                        
                        <div class="room-specs">
                            <div class="spec-item" title="Sức chứa tối đa">
                                <i class="fa-solid fa-user-group"></i> 
                                <span>{{ $room->suc_chua }} Khách</span>
                            </div>

                            <div class="spec-item" title="Diện tích phòng">
                                <i class="fa-solid fa-ruler-combined"></i> 
                                <span>{{ $room->dien_tich ? $room->dien_tich . 'm²' : '-- m²' }}</span>
                            </div>

                            {{-- Hiển thị trạng thái phòng --}}
                            <div class="spec-item" style="color: {{ $isSoldOut ? '#ef4444' : '#22c55e' }}">
                                <i class="fa-solid fa-door-open"></i> 
                                <span>{{ $isSoldOut ? 'Hết phòng' : 'Còn ' . $room->phongs_count . ' phòng' }}</span>
                            </div>
                        </div>

                        <p class="room-short-desc">
                            {{ $room->mo_ta ?? 'Trải nghiệm không gian sang trọng với nội thất cao cấp...' }}
                        </p>
                        
                        @if($isSoldOut)
                            <button class="btn-card-action" disabled>
                                <span>Tạm hết</span>
                            </button>
                        @else
                            <a href="{{ route('phong.chi-tiet', $room->id) }}" class="btn-card-action group/btn">
                                <span>Xem chi tiết</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state-container">
                    <div class="empty-state-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="empty-state-title">Không tìm thấy phòng phù hợp</h3>
                    <p class="empty-state-text">
                        Vui lòng thử điều chỉnh lại bộ lọc của bạn.
                    </p>
                    <a href="{{ route('phong.danh-sach') }}" class="btn btn-outline">Xóa bộ lọc</a>
                </div>
                @endforelse
            </div>

            @if($rooms->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-box">
                    {{ $rooms->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </main>
    </div>
</div>

<div class="section-guarantee">
    <div class="container">
        <div class="guarantee-grid">
            <div>
                <div class="guarantee-icon-box"><i class="fa-solid fa-medal"></i></div>
                <h3 class="guarantee-title">Đảm bảo giá tốt nhất</h3>
                <p class="guarantee-desc">Cam kết giá thấp nhất khi đặt trực tiếp.</p>
            </div>
            <div>
                <div class="guarantee-icon-box"><i class="fa-solid fa-headset"></i></div>
                <h3 class="guarantee-title">Hỗ trợ 24/7</h3>
                <p class="guarantee-desc">Đội ngũ nhân viên luôn sẵn sàng phục vụ.</p>
            </div>
            <div>
                <div class="guarantee-icon-box"><i class="fa-solid fa-shield-halved"></i></div>
                <h3 class="guarantee-title">Thanh toán an toàn</h3>
                <p class="guarantee-desc">Bảo mật thông tin tuyệt đối.</p>
            </div>
        </div>
    </div>
</div>

@endsection