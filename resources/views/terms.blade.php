@extends('layouts.app')
@section('title', 'Điều khoản sử dụng')

@section('content')

<div class="page-banner" style="height: 250px; position: relative; overflow: hidden;">
    <div class="banner-bg" style="position: absolute; inset: 0;">
        <img src="{{ asset('uploads/home/home.png') }}" alt="Terms Background" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.3;">
        <div class="banner-overlay" style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.6), var(--bg-body));"></div>
    </div>
    <div class="banner-content" style="position: relative; z-index: 10; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
        <h1 class="banner-title" style="font-family: var(--font-serif); font-size: 2.5rem; color: var(--white); margin-bottom: 0.5rem;">Điều khoản sử dụng</h1>
        <p class="banner-desc" style="color: var(--text-muted);">Quy định và thỏa thuận khi sử dụng dịch vụ tại Luxury Stay</p>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    <div class="legal-content-wrapper" style="max-width: 900px; margin: 0 auto;">
        
        <div class="legal-card" style="background: var(--bg-card); border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 3rem; color: var(--text-color);">
            
            <div class="legal-section">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">1. Giới thiệu chung</h3>
                <p style="margin-bottom: 1rem; line-height: 1.6;">Chào mừng quý khách đến với <strong>Luxury Stay Hotel</strong>. Khi quý khách sử dụng dịch vụ đặt phòng trực tuyến hoặc lưu trú tại khách sạn của chúng tôi, quý khách đồng ý tuân thủ các điều khoản và điều kiện được nêu dưới đây. Vui lòng đọc kỹ trước khi thực hiện giao dịch.</p>
            </div>

            <div class="legal-section" style="margin-top: 2rem;">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">2. Quy định đặt phòng & Thanh toán</h3>
                <ul style="list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--text-muted);">
                    <li style="margin-bottom: 0.5rem;">Quý khách cần cung cấp đầy đủ thông tin cá nhân (Họ tên, SĐT, CCCD) chính xác khi đặt phòng.</li>
                    <li style="margin-bottom: 0.5rem;">Hệ thống chấp nhận thanh toán qua các cổng: Tiền mặt tại quầy, Chuyển khoản ngân hàng, hoặc VNPay.</li>
                    <li style="margin-bottom: 0.5rem;">Đối với các đơn đặt phòng vào mùa cao điểm, chúng tôi có thể yêu cầu đặt cọc trước để giữ chỗ.</li>
                </ul>
            </div>

            <div class="legal-section" style="margin-top: 2rem;">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">3. Chính sách Nhận & Trả phòng</h3>
                <p style="margin-bottom: 1rem; line-height: 1.6;">
                    <strong style="color: var(--white);">Giờ nhận phòng (Check-in):</strong> 14:00<br>
                    <strong style="color: var(--white);">Giờ trả phòng (Check-out):</strong> 12:00
                </p>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Việc nhận phòng sớm hoặc trả phòng muộn tùy thuộc vào tình trạng phòng trống và có thể bị tính thêm phí phụ thu theo quy định của khách sạn.
                </p>
            </div>

            <div class="legal-section" style="margin-top: 2rem;">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">4. Chính sách Hủy phòng & Hoàn tiền</h3>
                <ul style="list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--text-muted);">
                    <li style="margin-bottom: 0.5rem;">Hủy trước <strong>3 ngày</strong> so với ngày nhận phòng: Miễn phí hủy, hoàn tiền 100% nếu đã thanh toán.</li>
                    <li style="margin-bottom: 0.5rem;">Hủy trong vòng <strong>3 ngày</strong> trước khi nhận phòng: Phí phạt là 50% tổng giá trị đơn đặt.</li>
                    <li style="margin-bottom: 0.5rem;">Không đến nhận phòng (No-show): Phí phạt là 100% tổng giá trị đơn đặt.</li>
                </ul>
            </div>

            <div class="legal-section" style="margin-top: 2rem;">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">5. Trách nhiệm của khách hàng</h3>
                <p style="margin-bottom: 1rem; line-height: 1.6;">
                    Quý khách chịu trách nhiệm bảo quản tài sản cá nhân và tuân thủ các quy định về an ninh, trật tự, phòng cháy chữa cháy của khách sạn. Nghiêm cấm mang vũ khí, chất cháy nổ, chất cấm vào khu vực khách sạn.
                </p>
            </div>

            <div class="legal-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-light); text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                <p>Cập nhật lần cuối: {{ date('d/m/Y') }}</p>
                <p>Mọi thắc mắc vui lòng liên hệ hotline: <strong style="color: var(--white);">0909 123 456</strong></p>
            </div>

        </div>
    </div>
</div>
@endsection