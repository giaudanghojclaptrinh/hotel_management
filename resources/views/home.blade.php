@extends('layouts.app')

@section('title', 'Trang chủ - Trải nghiệm nghỉ dưỡng đẳng cấp')

@section('content')

<!-- 1. HERO SECTION & BOOKING FORM -->
<div class="relative h-[85vh] min-h-[600px] w-full bg-gray-900 overflow-hidden">
    <!-- Background Image với lớp phủ tối -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop" 
             alt="Luxury Hotel" 
             class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
    </div>

    <!-- Hero Text -->
    <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4 pt-20">
        <span class="text-brand-gold font-bold tracking-[0.2em] uppercase text-sm mb-4 animate-fade-in-up">Chào mừng đến với Luxury Stay</span>
        <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl text-white font-bold leading-tight mb-6 drop-shadow-2xl max-w-4xl mx-auto">
            Nơi Đẳng Cấp Giao Thoa <br> Cùng Sự Bình Yên
        </h1>
        <p class="text-gray-200 text-lg md:text-xl max-w-2xl mx-auto mb-10 font-light">
            Tận hưởng kỳ nghỉ trong mơ với hệ thống phòng suite sang trọng và dịch vụ 5 sao chuẩn quốc tế tại trung tâm thành phố.
        </p>
        
        <!-- Nút cuộn xuống -->
        <a href="#booking-form" class="animate-bounce text-white opacity-80 hover:opacity-100 transition mt-4">
            <i class="fa-solid fa-chevron-down text-3xl"></i>
        </a>
    </div>

</div>

<!-- 3. FEATURED ROOMS (DYNAMIC DATA) -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-brand-gold font-bold uppercase tracking-wider text-sm">Không gian nghỉ dưỡng</span>
            <h2 class="font-serif text-4xl font-bold text-gray-900 mt-2">Hạng phòng nổi bật</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($loaiPhongs) && $loaiPhongs->count() > 0)
                @foreach($loaiPhongs as $phong)
                <!-- Room Card -->
                <div class="group bg-white rounded-lg shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                    <!-- Ảnh phòng (Có thể thay bằng ảnh thật từ DB nếu có cột 'image') -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80" 
                             alt="{{ $phong->ten_loai_phong }}" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur text-brand-900 text-xs font-bold px-3 py-1 rounded uppercase">
                            {{ number_format($phong->gia_dem ?? 0, 0, ',', '.') }}đ / đêm
                        </div>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="font-serif text-2xl font-bold text-gray-900 mb-2 group-hover:text-brand-gold transition">
                            <a href="#">{{ $phong->ten_loai_phong }}</a>
                        </h3>
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4 flex-grow">{{ $phong->mo_ta ?? 'Trải nghiệm không gian sang trọng với đầy đủ tiện nghi hiện đại.' }}</p>
                        
                        <!-- Tiện ích nhỏ -->
                        <div class="flex items-center gap-4 text-gray-400 text-sm mb-6 border-t border-gray-100 pt-4">
                            <span title="Người lớn"><i class="fa-solid fa-user mr-1"></i> 2</span>
                            <span title="Diện tích"><i class="fa-solid fa-ruler-combined mr-1"></i> 35m²</span>
                            <span title="Wifi"><i class="fa-solid fa-wifi mr-1"></i> Free</span>
                        </div>

                        <a href="{{ route('phong') }}" class="block w-full text-center bg-gray-100 text-gray-800 font-bold py-3 rounded hover:bg-brand-900 hover:text-white transition">
                            Chi tiết & Đặt phòng
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <p class="col-span-3 text-center text-gray-500">Đang cập nhật danh sách phòng...</p>
            @endif
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('phong') }}" class="inline-flex items-center justify-center px-8 py-3 border border-brand-900 text-base font-medium rounded-md text-brand-900 bg-transparent hover:bg-brand-900 hover:text-white transition md:text-lg">
                Xem tất cả phòng
            </a>
        </div>
    </div>
</section>

<!-- 4. SERVICES -->
<section class="py-20 bg-brand-900 text-white relative overflow-hidden">
    <!-- Họa tiết nền mờ -->
    <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none">
        <i class="fa-solid fa-crown text-9xl"></i>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="text-brand-gold font-bold uppercase tracking-wider text-sm">Tiện ích & Dịch vụ</span>
            <h2 class="font-serif text-4xl font-bold text-white mt-2">Tận hưởng kỳ nghỉ trọn vẹn</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Service 1 -->
            <div class="text-center group p-6 rounded-lg hover:bg-white/5 transition">
                <div class="w-16 h-16 mx-auto bg-brand-gold/10 rounded-full flex items-center justify-center text-brand-gold mb-4 group-hover:bg-brand-gold group-hover:text-brand-900 transition">
                    <i class="fa-solid fa-utensils text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Nhà hàng 5 sao</h3>
                <p class="text-gray-400 text-sm">Thưởng thức ẩm thực Á - Âu.</p>
            </div>
            <!-- Service 2 -->
            <div class="text-center group p-6 rounded-lg hover:bg-white/5 transition">
                <div class="w-16 h-16 mx-auto bg-brand-gold/10 rounded-full flex items-center justify-center text-brand-gold mb-4 group-hover:bg-brand-gold group-hover:text-brand-900 transition">
                    <i class="fa-solid fa-person-swimming text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Hồ bơi vô cực</h3>
                <p class="text-gray-400 text-sm">Tầm nhìn toàn cảnh thành phố.</p>
            </div>
            <!-- Service 3 -->
            <div class="text-center group p-6 rounded-lg hover:bg-white/5 transition">
                <div class="w-16 h-16 mx-auto bg-brand-gold/10 rounded-full flex items-center justify-center text-brand-gold mb-4 group-hover:bg-brand-gold group-hover:text-brand-900 transition">
                    <i class="fa-solid fa-spa text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Luxury Spa</h3>
                <p class="text-gray-400 text-sm">Liệu trình thư giãn cao cấp.</p>
            </div>
            <!-- Service 4 -->
            <div class="text-center group p-6 rounded-lg hover:bg-white/5 transition">
                <div class="w-16 h-16 mx-auto bg-brand-gold/10 rounded-full flex items-center justify-center text-brand-gold mb-4 group-hover:bg-brand-gold group-hover:text-brand-900 transition">
                    <i class="fa-solid fa-martini-glass-citrus text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Sky Bar</h3>
                <p class="text-gray-400 text-sm">Cocktail và nhạc sống mỗi tối.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. PROMOTIONS (DYNAMIC DATA) -->
@if(isset($khuyenMais) && $khuyenMais->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-brand-gold font-bold uppercase tracking-wider text-sm">Ưu đãi đặc biệt</span>
                <h2 class="font-serif text-4xl font-bold text-gray-900 mt-2">Khuyến mãi dành cho bạn</h2>
            </div>
            <a href="{{ route('khuyen-mai') }}" class="hidden md:inline-block text-brand-900 font-bold hover:text-brand-gold transition">
                Xem tất cả <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($khuyenMais as $km)
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition border border-gray-100 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">HOT</div>
                
                <div class="w-24 h-24 flex-shrink-0 bg-brand-50 rounded-full flex items-center justify-center border-2 border-brand-gold text-brand-gold">
                    <i class="fa-solid fa-gift text-3xl"></i>
                </div>
                
                <div class="flex-grow text-center md:text-left">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $km->ten_khuyen_mai }}</h3>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $km->mo_ta }}</p>
                    <div class="flex items-center justify-center md:justify-start gap-3">
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded border border-gray-200 font-mono text-sm">
                            Mã: <strong class="text-brand-900">{{ $km->ma_giam_gia }}</strong>
                        </span>
                        <span class="text-red-500 font-bold">
                            @if($km->loai_giam_gia == 'percentage')
                                Giảm {{ $km->gia_tri_giam }}%
                            @else
                                Giảm {{ number_format($km->gia_tri_giam) }}đ
                            @endif
                        </span>
                    </div>
                </div>
                
                <div>
                    <button onclick="navigator.clipboard.writeText('{{ $km->ma_giam_gia }}'); alert('Đã sao chép mã!')" class="text-brand-gold hover:text-brand-900 text-sm font-semibold underline">
                        Sao chép
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 6. TESTIMONIALS (STATIC) -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-serif text-4xl font-bold text-gray-900">Khách hàng nói gì về chúng tôi?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Review 1 -->
            <div class="bg-gray-50 p-8 rounded-xl relative">
                <i class="fa-solid fa-quote-left text-4xl text-brand-gold/20 absolute top-4 left-4"></i>
                <p class="text-gray-600 mb-6 italic relative z-10">"Một trải nghiệm tuyệt vời! Phòng ốc sạch sẽ, nhân viên cực kỳ thân thiện và chuyên nghiệp. Chắc chắn tôi sẽ quay lại."</p>
                <div class="flex items-center gap-4">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-12 h-12 rounded-full">
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Nguyễn Thu Hà</h4>
                        <div class="text-yellow-400 text-xs">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
             <!-- Review 2 -->
             <div class="bg-gray-50 p-8 rounded-xl relative">
                <i class="fa-solid fa-quote-left text-4xl text-brand-gold/20 absolute top-4 left-4"></i>
                <p class="text-gray-600 mb-6 italic relative z-10">"Vị trí khách sạn rất thuận tiện. Đồ ăn sáng ngon và đa dạng. Hồ bơi view đẹp nhất khu vực."</p>
                <div class="flex items-center gap-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-12 h-12 rounded-full">
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Trần Minh Tuấn</h4>
                        <div class="text-yellow-400 text-xs">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
             <!-- Review 3 -->
             <div class="bg-gray-50 p-8 rounded-xl relative">
                <i class="fa-solid fa-quote-left text-4xl text-brand-gold/20 absolute top-4 left-4"></i>
                <p class="text-gray-600 mb-6 italic relative z-10">"Dịch vụ đẳng cấp 5 sao thực sự. Tôi rất ấn tượng với cách bài trí nội thất trong phòng Suite."</p>
                <div class="flex items-center gap-4">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User" class="w-12 h-12 rounded-full">
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Sarah Johnson</h4>
                        <div class="text-yellow-400 text-xs">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. CTA / CALL TO ACTION -->
<section class="py-16 bg-brand-gold">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-brand-900 mb-4">Sẵn sàng cho kỳ nghỉ trong mơ?</h2>
        <p class="text-brand-900/80 mb-8 text-lg">Đặt phòng trực tiếp trên website để nhận ưu đãi tốt nhất và tích điểm thành viên.</p>
        <a href="{{ route('phong') }}" class="inline-block bg-brand-900 text-white font-bold py-4 px-10 rounded shadow-xl hover:bg-white hover:text-brand-900 transition transform hover:-translate-y-1">
            ĐẶT PHÒNG NGAY
        </a>
    </div>
</section>

@endsection