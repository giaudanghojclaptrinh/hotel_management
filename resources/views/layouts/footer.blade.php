<footer class="footer-wrapper">
    <div class="footer-container">
        
        <div class="footer-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
            
            <div class="space-y-4">
                <div class="footer-brand" style="display: flex; align-items: center; gap: 0.5rem;">
                     <span class="footer-logo-icon" style="width: 32px; height: 32px; background: rgba(205, 164, 94, 0.1); border: 1px solid #cda45e; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #cda45e;">
                        <i class="fa-solid fa-crown text-sm"></i>
                    </span>
                    <span class="footer-logo-text" style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: #fff; font-weight: 700;">
                        Luxury<span class="text-brand-gold" style="color: #cda45e;">Stay</span>
                    </span>
                </div>
                <p class="footer-desc" style="color: #a3a3a3; line-height: 1.6; font-size: 0.95rem;">
                    Nơi giao thoa giữa vẻ đẹp kiến trúc cổ điển và tiện nghi hiện đại. Trải nghiệm kỳ nghỉ khó quên với dịch vụ 5 sao chuẩn quốc tế.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="https://www.facebook.com/giau.truongphuoc/?locale=vi_VN" class="footer-social-link"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/giau204/" class="footer-social-link"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@tpggiau0802" class="footer-social-link"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <div>
                <h3 class="footer-heading">Khám phá</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('trang_chu') }}" class="footer-link"><i class="fa-solid fa-angle-right" style="font-size: 0.8em; margin-right: 5px;"></i> Trang chủ</a></li>
                    <li><a href="{{ route('phong.danh-sach') }}" class="footer-link"><i class="fa-solid fa-angle-right" style="font-size: 0.8em; margin-right: 5px;"></i> Phòng nghỉ</a></li>
                    <li><a href="{{ route('ve-chung-toi') }}" class="footer-link"><i class="fa-solid fa-angle-right" style="font-size: 0.8em; margin-right: 5px;"></i> Về chúng tôi</a></li>
                    <li><a href="{{ route('khuyen-mai') }}" class="footer-link"><i class="fa-solid fa-angle-right" style="font-size: 0.8em; margin-right: 5px;"></i> Ưu đãi đặc biệt</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-heading">Liên hệ</h3>
                <ul class="footer-list">
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-location-dot footer-contact-icon"></i>
                        <span>Long Xuyên, An Giang</span>
                    </li>
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-phone footer-contact-icon"></i>
                        <span class="font-semibold text-white">+84 792 008 096</span>
                    </li>
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-envelope footer-contact-icon"></i>
                        <span>giaudeptrainhat@gmail.com</span>
                    </li>
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-clock footer-contact-icon"></i>
                        <span>Hỗ trợ 24/7</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p style="color: #6b7280;">&copy; 2025 Luxury Stay Hotel. All rights reserved.</p>
            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="{{ route('privacy') }}" class="footer-link" style="font-size: 0.85rem;">Chính sách bảo mật</a>
                <a href="{{ route('terms') }}" class="footer-link" style="font-size: 0.85rem;">Điều khoản sử dụng</a>
            </div>
        </div>
    </div>
</footer>