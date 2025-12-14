<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- 1. IMPORT ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\AdminController; 
use App\Http\Controllers\Admin\ChiTietDatPhongController;
use App\Http\Controllers\Admin\DatPhongController; 
use App\Http\Controllers\Admin\HoaDonController;
use App\Http\Controllers\Admin\LoaiPhongController;
use App\Http\Controllers\Admin\PhongController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\TienNghiController;

// --- 2. IMPORT CLIENT CONTROLLERS ---
use App\Http\Controllers\Client\PageController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\NotificationController; // Import Notification Controller
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\ContactController;

Auth::routes();

// Handle GET logout (redirect to login page with message)
Route::get('/logout', function () {
    return redirect()->route('login')->with('info', 'Vui lòng sử dụng nút đăng xuất.');
})->name('logout.get');

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (KHÁCH HÀNG)
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [PageController::class, 'home'])->name('trang_chu');
Route::get('/home', [PageController::class, 'home'])->name('home');

//login with google
Route::get('/login/google', [PageController::class, 'getGoogleLogin'])->name('google.login');
Route::get('/login/google/callback', [PageController::class, 'getGoogleCallback'])->name('google.callback');
// Accept legacy/common callback paths to reduce redirect_uri mismatch causes
Route::get('/larashop/login/google/callback', [PageController::class, 'getGoogleCallback']);
Route::get('/hotel_management/public/login/google/callback', [PageController::class, 'getGoogleCallback']);

// Danh sách & Chi tiết phòng
Route::get('/danh-sach-phong', [PageController::class, 'rooms'])->name('phong.danh-sach');
Route::get('/chi-tiet-phong/{id}', [PageController::class, 'roomDetail'])->name('phong.chi-tiet');

// Trang ưu đãi
Route::get('/uu-dai', [PageController::class, 'promotions'])->name('khuyen-mai');

// [API] Kiểm tra mã khuyến mãi
Route::post('/api/check-promo', [BookingController::class, 'checkPromotion'])->name('api.check.promo');

// [VNPAY CALLBACK] Route xử lý kết quả thanh toán
Route::get('/payment/callback', [BookingController::class, 'paymentCallback'])->name('payment.callback');

Route::get('/ve-chung-toi', [PageController::class, 'about'])->name('ve-chung-toi');
// Contact page
Route::get('/lien-he', function () {
    return view('client.contact.index');
})->name('contact');

// Contact form submit
Route::post('/lien-he', [ContactController::class, 'submit'])->name('contact.submit');

// Legal pages: privacy & terms (simple static views)
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

// Hiển thị view lỗi tương ứng (response code đúng)
Route::get('/error/{code}', function ($code) {
    $allowed = ['400','401','403','404','419','422','429','500','503'];
    if (!in_array($code, $allowed)) abort(404);
    return response()->view('errors.' . $code, [], (int) $code);
});

// Dev-only: send test feedback email (only in local environment)
if (config('app.debug')) {
    Route::get('/dev/test-feedback-mail', function () {
        $feedback = App\Models\Feedback::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'Đây là email kiểm tra gửi từ hệ thống.'
        ]);
        \Illuminate\Support\Facades\Mail::to(config('mail.admin_address'))->send(new App\Mail\FeedbackReceived($feedback));
        return 'OK - test mail queued/sent';
    });
}

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (KHÁCH HÀNG ĐÃ ĐĂNG NHẬP)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // 1. Hồ sơ cá nhân
    Route::get('/ho-so', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/ho-so', [ProfileController::class, 'update'])->name('profile.update');

    // 2. Lịch sử đặt phòng
    Route::get('/lich-su-dat-phong', [BookingController::class, 'history'])->name('bookings.history');
    // Chi tiết đặt phòng
    Route::get('/dat-phong/chi-tiet/{id}', [BookingController::class, 'detail'])->name('bookings.detail');
    // Hủy đơn đặt phòng (khách hàng)
    Route::post('/dat-phong/huy/{id}', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Route xem chi tiết hóa đơn của user
    Route::get('/hoa-don-cua-toi/{id}', [BookingController::class, 'invoice'])->name('bookings.invoice');
    // (PDF export removed) server-side PDF route was removed; printing uses browser print
    
    // 3. [QUẢN LÝ THÔNG BÁO]
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.count');
        Route::post('/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
        Route::delete('/delete/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
        // Xóa hàng loạt
        Route::post('/delete-multiple', [NotificationController::class, 'bulkDelete'])->name('notifications.deleteMultiple'); 
    });

    // 4. Reviews (khách hàng đã đăng nhập mới được gửi đánh giá)
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // 4. Quy trình đặt phòng (Yêu cầu có Profile)
    Route::middleware(['check.profile'])->group(function () {
        Route::get('/dat-phong/xac-nhan', [BookingController::class, 'create'])->name('booking.create');
        
        // [VNPAY POST]: Xử lý dữ liệu từ modal QR Demo
        Route::post('/dat-phong/vnpay-store', [BookingController::class, 'postVnPayStore'])->name('booking.vnpay.create');
        
        // [CASH POST]: Chỉ để xử lý Pay at Hotel
        Route::post('/dat-phong/luu', [BookingController::class, 'store'])->name('booking.store');
        
        Route::get('/dat-phong/thanh-cong', [BookingController::class, 'success'])->name('booking.success');
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (QUẢN TRỊ VIÊN)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin profile
    Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('admin.profile.update');

    // Quản lý Loại phòng
    Route::prefix('loai-phong')->group(function() {
        Route::get('/', [LoaiPhongController::class, 'getDanhSach'])->name('admin.loai-phong');
        Route::get('/them', [LoaiPhongController::class, 'getThem'])->name('admin.loai-phong.them');
        Route::post('/them', [LoaiPhongController::class, 'postThem'])->name('admin.loai-phong.store');
        Route::get('/sua/{id}', [LoaiPhongController::class, 'getSua'])->name('admin.loai-phong.sua');
        Route::post('/sua/{id}', [LoaiPhongController::class, 'postSua'])->name('admin.loai-phong.update');
        Route::get('/xoa/{id}', [LoaiPhongController::class, 'getXoa'])->name('admin.loai-phong.xoa');
    });

    // Quản lý Phòng
    Route::prefix('phong')->group(function() {
        Route::get('/', [PhongController::class, 'getDanhSach'])->name('admin.phong');
        Route::get('/them', [PhongController::class, 'getThem'])->name('admin.phong.them');
        Route::post('/them', [PhongController::class, 'postThem'])->name('admin.phong.store');
        Route::get('/sua/{id}', [PhongController::class, 'getSua'])->name('admin.phong.sua');
        Route::post('/sua/{id}', [PhongController::class, 'postSua'])->name('admin.phong.update');
        Route::get('/xoa/{id}', [PhongController::class, 'getXoa'])->name('admin.phong.xoa');
        // Bulk delete rooms
        Route::post('/bulk-delete', [PhongController::class, 'bulkDelete'])->name('admin.phong.bulk-delete');
    });

    // Quản lý Đặt phòng
    Route::prefix('dat-phong')->group(function() {
        Route::get('/', [DatPhongController::class, 'getDanhSach'])->name('admin.dat-phong'); // Sơ đồ phòng
        // Tạo đơn đặt phòng (admin) - route thêm mới
        Route::get('/them', [DatPhongController::class, 'getThem'])->name('admin.dat-phong.them');
        Route::post('/them', [DatPhongController::class, 'postThem'])->name('admin.dat-phong.store');

        Route::get('/thung-rac', [DatPhongController::class, 'getThungRac'])->name('admin.dat-phong.trash');
        Route::get('/lich-su', [DatPhongController::class, 'getLichSu'])->name('admin.dat-phong.history');

        Route::get('/duyet/{id}', [DatPhongController::class, 'duyetDon'])->name('admin.dat-phong.duyet'); 
        Route::get('/huy/{id}', [DatPhongController::class, 'huyDon'])->name('admin.dat-phong.huy');

        Route::delete('/xoa-hang-loat', [DatPhongController::class, 'xoaHangLoat'])->name('admin.dat-phong.xoa-hang-loat');
        Route::get('/hoa-don/{id}', [DatPhongController::class, 'getHoaDon'])->name('admin.dat-phong.hoa-don'); 
        Route::post('/thanh-toan/{id}', [DatPhongController::class, 'postThanhToan'])->name('admin.dat-phong.thanh-toan');

        Route::get('/duyet/{id}', [DatPhongController::class, 'duyetDon'])->name('admin.dat-phong.duyet'); 
        Route::get('/huy/{id}', [DatPhongController::class, 'huyDon'])->name('admin.dat-phong.huy'); 
        // AJAX POST endpoints for approve/reject (preferred for modal actions)
        Route::post('/duyet/{id}', [DatPhongController::class, 'postDuyet'])->name('admin.dat-phong.duyet.post');
        Route::post('/huy/{id}', [DatPhongController::class, 'postHuy'])->name('admin.dat-phong.huy.post');
        // Bulk actions: move history items to trash, and permanently delete from trash
        Route::post('/bulk-trash', [DatPhongController::class, 'bulkMoveToTrash'])->name('admin.dat-phong.bulk-trash');
        // Bulk delete bookings safely (history view)
        Route::post('/bulk-delete', [DatPhongController::class, 'destroyMany'])->name('admin.dat-phong.bulk-delete');
        Route::get('/history', [DatPhongController::class, 'getHistory'])->name('admin.dat-phong.history'); 
        Route::get('/trash', [DatPhongController::class, 'getTrash'])->name('admin.dat-phong.trash'); 
        Route::get('/sua/{id}', [DatPhongController::class, 'getSua'])->name('admin.dat-phong.sua');
        Route::post('/sua/{id}', [DatPhongController::class, 'postSua'])->name('admin.dat-phong.update');
        Route::get('/xoa/{id}', [DatPhongController::class, 'getXoa'])->name('admin.dat-phong.xoa');
        // Chi tiết phòng: hiển thị tất cả đơn liên quan tới phòng này
        Route::get('/phong/{id}', [DatPhongController::class, 'getRoomDetail'])->name('admin.dat-phong.room-detail');
        // Báo cáo doanh thu
        Route::get('/bao-cao/danh-thu', [DatPhongController::class, 'revenueReport'])->name('admin.reports.revenue');
    });

    // Quản lý Khuyến mãi
    Route::prefix('khuyen-mai')->group(function() {
        Route::get('/', [KhuyenMaiController::class, 'getDanhSach'])->name('admin.khuyen-mai');
        Route::get('/them', [KhuyenMaiController::class, 'getThem'])->name('admin.khuyen-mai.them');
        Route::post('/them', [KhuyenMaiController::class, 'postThem'])->name('admin.khuyen-mai.store');
        Route::get('/sua/{id}', [KhuyenMaiController::class, 'getSua'])->name('admin.khuyen-mai.sua');
        Route::post('/sua/{id}', [KhuyenMaiController::class, 'postSua'])->name('admin.khuyen-mai.update');
        Route::get('/xoa/{id}', [KhuyenMaiController::class, 'getXoa'])->name('admin.khuyen-mai.xoa');
        Route::post('/bulk-delete', [KhuyenMaiController::class, 'bulkDelete'])->name('admin.khuyen-mai.bulk-delete');
    });

    // Quản lý Hóa đơn
    Route::prefix('hoa-don')->group(function() {
        Route::get('/', [HoaDonController::class, 'getDanhSach'])->name('admin.hoa-don');
        Route::get('/sua/{id}', [HoaDonController::class, 'getSua'])->name('admin.hoa-don.sua');
        Route::post('/sua/{id}', [HoaDonController::class, 'postSua'])->name('admin.hoa-don.update');
        Route::get('/xoa/{id}', [HoaDonController::class, 'getXoa'])->name('admin.hoa-don.xoa');
    });
    
    // Quản lý Users
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'getDanhSach'])->name('admin.users');
        Route::get('/them', [UserController::class, 'getThem'])->name('admin.user.them');
        Route::post('/them', [UserController::class, 'postThem'])->name('admin.user.store');
        Route::get('/sua/{id}', [UserController::class, 'getSua'])->name('admin.user.sua');
        Route::post('/sua/{id}', [UserController::class, 'postSua'])->name('admin.user.update');
        Route::get('/xoa/{id}', [UserController::class, 'getXoa'])->name('admin.user.xoa');
        Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('admin.users.bulk-delete');
    });

    // Quản lý Tiện nghi
    Route::prefix('tien-nghi')->group(function() {
        Route::get('/', [TienNghiController::class, 'getDanhSach'])->name('admin.tien-nghi');
        Route::get('/them', [TienNghiController::class, 'getThem'])->name('admin.tien-nghi.them');
        Route::post('/them', [TienNghiController::class, 'postThem'])->name('admin.tien-nghi.store');
        Route::get('/sua/{id}', [TienNghiController::class, 'getSua'])->name('admin.tien-nghi.sua');
        Route::post('/sua/{id}', [TienNghiController::class, 'postSua'])->name('admin.tien-nghi.update');
        Route::get('/xoa/{id}', [TienNghiController::class, 'getXoa'])->name('admin.tien-nghi.xoa');
        Route::post('/bulk-delete', [TienNghiController::class, 'bulkDelete'])->name('admin.tien-nghi.bulk-delete');
    });

    // Quản lý phản hồi khách hàng (Feedbacks)
    Route::prefix('feedbacks')->group(function() {
        Route::get('/', [ContactController::class, 'index'])->name('admin.feedbacks.index');
        Route::post('/bulk-delete', [ContactController::class, 'bulkDelete'])->name('admin.feedbacks.bulk-delete');
        Route::get('/{feedback}', [ContactController::class, 'show'])->name('admin.feedbacks.show');
        Route::post('/{feedback}/handled', [ContactController::class, 'markHandled'])->name('admin.feedbacks.handle');
    });
});