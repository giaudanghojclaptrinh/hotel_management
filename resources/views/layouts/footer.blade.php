<footer class="footer-wrapper">
    <div class="footer-container">
        <div class="footer-grid">
            
            <!-- 1. Brand Info -->
            <div class="space-y-4">
                <div class="footer-brand">
                     <span class="footer-logo-icon">
                        <i class="fa-solid fa-crown text-sm"></i>
                    </span>
                    <span class="footer-logo-text">Luxury<span class="text-brand-gold">Stay</span></span>
                </div>
                <p class="footer-desc">
                    Nơi giao thoa giữa vẻ đẹp kiến trúc cổ điển và tiện nghi hiện đại. Trải nghiệm kỳ nghỉ khó quên với dịch vụ 5 sao chuẩn quốc tế.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="footer-social-link"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="footer-social-link"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="footer-social-link"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <!-- 2. Links (Khám phá) -->
            <div>
                <h3 class="footer-heading">Khám phá</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('ve-chung-toi') }}" class="footer-link"><i class="fa-solid fa-angle-right"></i> Về chúng tôi</a></li>
                    <li><a href="{{ route('khuyen-mai') }}" class="footer-link"><i class="fa-solid fa-angle-right"></i> Ưu đãi đặc biệt</a></li>
                </ul>
            </div>

            <!-- 3. Contact (Liên hệ) -->
            <div>
                <h3 class="footer-heading">Liên hệ</h3>
                <!-- Sử dụng class footer-list để căn chỉnh khoảng cách -->
                <ul class="footer-list">
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-location-dot footer-contact-icon"></i>
                        <span>Long Xuyên, An Giang</span>
                    </li>
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-phone footer-contact-icon"></i>
                        <span class="font-semibold text-white">+84 792008096</span>
                    </li>
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-envelope footer-contact-icon"></i>
                        <span>booking@luxurystay.com</span>
                    </li>
                </ul>
            </div>

            <!-- 4. Newsletter (Bản tin) -->
            <div>
                <h3 class="footer-heading">Bản tin</h3>
                <p class="footer-desc mb-4">Đăng ký để nhận mã khuyến mãi giảm giá 10% cho lần đặt đầu tiên.</p>
                <form class="flex flex-col gap-2">
                    <input type="email" placeholder="Email của bạn..." class="footer-input">
                    <button type="submit" class="footer-btn-submit">Đăng ký</button>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="footer-bottom">
            <p>&copy; 2025 Luxury Stay Hotel. All rights reserved.</p>
            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition">Chính sách bảo mật</a>
                <a href="#" class="hover:text-white transition">Điều khoản sử dụng</a>
            </div>
        </div>
    </div>
</footer>   