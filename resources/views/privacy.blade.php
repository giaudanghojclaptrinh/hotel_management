@extends('layouts.app')
@section('title', 'Chính sách bảo mật')

@section('content')

<div class="page-banner" style="height: 250px; position: relative; overflow: hidden;">
    <div class="banner-bg" style="position: absolute; inset: 0;">
        <img src="{{ asset('uploads/home/home.png') }}" alt="Privacy Background" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.3;">
        <div class="banner-overlay" style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.6), var(--bg-body));"></div>
    </div>
    <div class="banner-content" style="position: relative; z-index: 10; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
        <h1 class="banner-title" style="font-family: var(--font-serif); font-size: 2.5rem; color: var(--white); margin-bottom: 0.5rem;">Chính sách bảo mật</h1>
        <p class="banner-desc" style="color: var(--text-muted);">Cam kết bảo vệ thông tin cá nhân của khách hàng</p>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    <div class="legal-content-wrapper" style="max-width: 900px; margin: 0 auto;">
        
        <div class="legal-card" style="background: var(--bg-card); border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 3rem; color: var(--text-color);">
            
            <div class="legal-section">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">1. Thu thập thông tin</h3>
                <p style="margin-bottom: 1rem; line-height: 1.6;">Chúng tôi thu thập thông tin cá nhân cần thiết để xử lý việc đặt phòng và nâng cao trải nghiệm của quý khách, bao gồm:</p>
                <ul style="list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--text-muted);">
                    <li style="margin-bottom: 0.5rem;">Họ và tên, Số điện thoại, Email.</li>
                    <li style="margin-bottom: 0.5rem;">Số Căn cước công dân (CCCD) hoặc Hộ chiếu (để làm thủ tục nhận phòng theo quy định pháp luật).</li>
                    <li style="margin-bottom: 0.5rem;">Thông tin thanh toán (khi giao dịch qua cổng thanh toán).</li>
                </ul>
            </div>

            <div class="legal-section" style="margin-top: 2rem;">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">2. Phạm vi sử dụng thông tin</h3>
                <p style="margin-bottom: 1rem; line-height: 1.6;">Thông tin của quý khách chỉ được sử dụng cho các mục đích sau:</p>
                <ul style="list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--text-muted);">
                    <li style="margin-bottom: 0.5rem;">Xác nhận đặt phòng và liên hệ hỗ trợ khách hàng.</li>
                    <li style="margin-bottom: 0.5rem;">Gửi thông báo về các chương trình khuyến mãi (nếu quý khách đăng ký nhận tin).</li>
                    <li style="margin-bottom: 0.5rem;">Nâng cao chất lượng dịch vụ và giải quyết khiếu nại.</li>
                    <li style="margin-bottom: 0.5rem;">Cung cấp cho cơ quan chức năng khi có yêu cầu theo quy định của pháp luật Việt Nam.</li>
                </ul>
            </div>

            <div class="legal-section" style="margin-top: 2rem;">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">3. Bảo mật thông tin</h3>
                <p style="margin-bottom: 1rem; line-height: 1.6;">
                    Luxury Stay cam kết bảo mật tuyệt đối thông tin cá nhân của quý khách. Chúng tôi sử dụng các biện pháp an ninh kỹ thuật (mã hóa SSL, tường lửa) để ngăn chặn truy cập trái phép. Chúng tôi không bán, chia sẻ hay trao đổi thông tin cá nhân của khách hàng cho bên thứ ba vì mục đích thương mại.
                </p>
            </div>

            <div class="legal-section" style="margin-top: 2rem;">
                <h3 class="legal-heading" style="color: var(--primary-gold); font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-light); padding-bottom: 0.5rem;">4. Quyền lợi của khách hàng</h3>
                <p style="margin-bottom: 1rem; line-height: 1.6;">
                    Quý khách có quyền yêu cầu truy cập, chỉnh sửa hoặc xóa thông tin cá nhân của mình trên hệ thống của chúng tôi bất kỳ lúc nào bằng cách đăng nhập vào tài khoản hoặc liên hệ với bộ phận CSKH.
                </p>
            </div>

            <div class="legal-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-light); text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                <p>Nếu có thắc mắc về chính sách bảo mật, vui lòng liên hệ email: <strong style="color: var(--white);">privacy@luxurystay.com</strong></p>
            </div>

        </div>
    </div>
</div>
@endsection