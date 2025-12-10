@extends('layouts.app')
@section('title', 'Danh sách phòng nghỉ')

@section('content')
<!-- 1. Header Banner -->
<div class="relative bg-brand-900 py-24 text-center text-white mb-12">
    <!-- Hình nền mờ -->
    <div class="absolute inset-0 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             alt="Background" 
             class="w-full h-full object-cover opacity-30 transform scale-105">
        <div class="absolute inset-0 bg-gradient-to-b from-brand-900/50 to-brand-900/90"></div>
    </div>
    
    <!-- Nội dung Banner -->
    <div class="relative z-10 max-w-7xl mx-auto px-4">
        <span class="text-brand-gold font-bold tracking-[0.2em] uppercase text-sm mb-4 block animate-fade-in-up">Luxury Collection</span>
        <h1 class="font-serif text-4xl md:text-6xl font-bold mb-6 leading-tight">Bộ Sưu Tập Phòng Nghỉ</h1>
        <p class="text-gray-300 text-lg font-light max-w-2xl mx-auto">
            Khám phá không gian nghỉ dưỡng đẳng cấp, nơi từng chi tiết được chăm chút tỉ mỉ để mang lại sự thoải mái tuyệt đối cho bạn.
        </p>
    </div>
</div>

<!-- 2. Danh sách phòng -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    
    <!-- Bộ lọc nhanh (Optional) -->
    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-100">
        <p class="text-gray-500 text-sm">Hiển thị <span class="font-bold text-gray-900">{{ $rooms->count() }}</span> hạng phòng</p>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 mr-2">Sắp xếp:</span>
            <select class="text-sm border-gray-300 rounded-md focus:ring-brand-gold focus:border-brand-gold text-gray-700">
                <option>Mặc định</option>
                <option>Giá thấp đến cao</option>
                <option>Giá cao đến thấp</option>
            </select>
        </div>
    </div>

    <!-- Grid Phòng -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($rooms as $room)
        <div class="group bg-white rounded-xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden flex flex-col h-full">
            
            <!-- Hình ảnh -->
            <div class="relative h-72 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80" 
                     alt="{{ $room->ten_loai_phong }}" 
                     class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                
                <!-- Tag giá tiền -->
                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-4 py-2 rounded-lg shadow-lg border border-gray-100">
                    <span class="text-brand-900 font-bold text-lg font-serif">{{ number_format($room->gia_dem, 0, ',', '.') }}đ</span>
                    <span class="text-xs text-gray-500 font-medium">/đêm</span>
                </div>

                <!-- Overlay khi hover -->
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>
            
            <!-- Nội dung Card -->
            <div class="p-6 flex flex-col flex-grow">
                <!-- Tên phòng -->
                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-3 group-hover:text-brand-gold transition duration-300">
                    <a href="{{ route('phong.chi-tiet', $room->id) }}">{{ $room->ten_loai_phong }}</a>
                </h3>
                
                <!-- Thông số kỹ thuật -->
                <div class="flex items-center gap-6 text-sm text-gray-500 mb-5 pb-5 border-b border-gray-100">
                    <div class="flex items-center" title="Sức chứa tối đa">
                        <i class="fa-solid fa-user-group text-brand-gold mr-2 text-lg"></i> 
                        <span>{{ $room->suc_chua }} Khách</span>
                    </div>
                    <div class="flex items-center" title="Diện tích phòng">
                        <i class="fa-solid fa-ruler-combined text-brand-gold mr-2 text-lg"></i> 
                        <span>35m²</span>
                    </div>
                    <div class="flex items-center" title="Giường ngủ">
                        <i class="fa-solid fa-bed text-brand-gold mr-2 text-lg"></i> 
                        <span>King Size</span>
                    </div>
                </div>

                <!-- Mô tả ngắn -->
                <p class="text-gray-600 text-sm line-clamp-3 mb-6 flex-grow leading-relaxed">
                    {{ $room->mo_ta ?? 'Trải nghiệm không gian sang trọng với nội thất cao cấp, view thành phố tuyệt đẹp và đầy đủ tiện nghi hiện đại cho kỳ nghỉ của bạn.' }}
                </p>
                
                <!-- Nút hành động -->
                <div class="mt-auto">
                    <a href="{{ route('phong.chi-tiet', $room->id) }}" class="group/btn flex items-center justify-center w-full py-3.5 border border-brand-900 text-brand-900 font-bold rounded-lg hover:bg-brand-900 hover:text-white transition duration-300 text-sm uppercase tracking-wide">
                        <span>Xem chi tiết & Đặt phòng</span>
                        <i class="fa-solid fa-arrow-right ml-2 transform group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    @if($rooms->hasPages())
    <div class="mt-16 flex justify-center">
        <div class="bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-100">
            {{ $rooms->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Section phụ: Cam kết dịch vụ (Optional) -->
<div class="bg-gray-50 py-16 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-brand-gold">
                    <i class="fa-solid fa-medal text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Đảm bảo giá tốt nhất</h3>
                <p class="text-gray-500 text-sm">Cam kết giá thấp nhất khi đặt trực tiếp.</p>
            </div>
            <div>
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-brand-gold">
                    <i class="fa-solid fa-headset text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Hỗ trợ 24/7</h3>
                <p class="text-gray-500 text-sm">Đội ngũ nhân viên luôn sẵn sàng phục vụ.</p>
            </div>
            <div>
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-brand-gold">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Thanh toán an toàn</h3>
                <p class="text-gray-500 text-sm">Bảo mật thông tin tuyệt đối.</p>
            </div>
        </div>
    </div>
</div>
@endsection