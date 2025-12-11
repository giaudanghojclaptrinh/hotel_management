<?php
// Tên file: config/vnpay.php

$appUrl = env('APP_URL');
// Đảm bảo APP_URL không kết thúc bằng dấu '/', nếu có thì loại bỏ
$baseUrl = rtrim($appUrl, '/');

return [
    // Mã Merchant (TmnCode)
    'vnp_tmn_code' => env('VNPAY_TMN_CODE'),
    
    // Chuỗi bí mật để tạo Hash
    'vnp_hash_secret' => env('VNPAY_HASH_SECRET'),
    
    // URL chuyển đến cổng thanh toán (Sandbox)
    'vnp_url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    
    // [FIXED RETURN URL] URL xử lý kết quả trả về:
    // Sẽ là: http://127.0.0.1/hotel_management/public/payment/callback
    'vnp_return_url' => $baseUrl . '/payment/callback',
    
    // Command cố định cho lệnh thanh toán
    'vnp_command' => 'pay',
    
    // Phiên bản API
    'vnp_version' => '2.1.0',
    
    // Đơn vị tiền tệ mặc định
    'vnp_curr_code' => 'VND',

    // Loại hình thanh toán
    'vnp_order_type' => 'other',
];