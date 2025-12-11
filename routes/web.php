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

Auth::routes();

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (KHÁCH HÀNG)
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [PageController::class, 'home'])->name('trang_chu');
Route::get('/home', [PageController::class, 'home'])->name('home');

// Danh sách & Chi tiết phòng
Route::get('/danh-sach-phong', [PageController::class, 'rooms'])->name('phong.danh-sach');
Route::get('/chi-tiet-phong/{id}', [PageController::class, 'roomDetail'])->name('phong.chi-tiet');

// Trang ưu đãi
Route::get('/uu-dai', [PageController::class, 'promotions'])->name('khuyen-mai');

// [API] Kiểm tra mã khuyến mãi
Route::post('/api/check-promo', [BookingController::class, 'checkPromotion'])->name('api.check.promo');

// [VNPAY CALLBACK] Route xử lý kết quả thanh toán
Route::get('/payment/callback', [BookingController::class, 'paymentCallback'])->name('payment.callback');


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
    
    // [MỚI - FIX LỖI] Route xem chi tiết hóa đơn của user
    Route::get('/hoa-don-cua-toi/{id}', [BookingController::class, 'invoice'])->name('bookings.invoice');

    // 3. Quy trình đặt phòng (Yêu cầu có Profile)
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
    });

    // Quản lý Đặt phòng
    Route::prefix('dat-phong')->group(function() {
        Route::get('/', [DatPhongController::class, 'getDanhSach'])->name('admin.dat-phong');
        Route::delete('/xoa-hang-loat', [DatPhongController::class, 'xoaHangLoat'])->name('admin.dat-phong.xoa-hang-loat');
        Route::get('/hoa-don/{id}', [DatPhongController::class, 'getHoaDon'])->name('admin.dat-phong.hoa-don'); 
        Route::post('/thanh-toan/{id}', [DatPhongController::class, 'postThanhToan'])->name('admin.dat-phong.thanh-toan');  
        Route::get('/duyet/{id}', [DatPhongController::class, 'duyetDon'])->name('admin.dat-phong.duyet'); 
        Route::get('/huy/{id}', [DatPhongController::class, 'huyDon'])->name('admin.dat-phong.huy'); 
        Route::get('/sua/{id}', [DatPhongController::class, 'getSua'])->name('admin.dat-phong.sua');
        Route::post('/sua/{id}', [DatPhongController::class, 'postSua'])->name('admin.dat-phong.update');
        Route::get('/xoa/{id}', [DatPhongController::class, 'getXoa'])->name('admin.dat-phong.xoa');
    });

    // Quản lý Khuyến mãi
    Route::prefix('khuyen-mai')->group(function() {
        Route::get('/', [KhuyenMaiController::class, 'getDanhSach'])->name('admin.khuyen-mai');
        Route::get('/them', [KhuyenMaiController::class, 'getThem'])->name('admin.khuyen-mai.them');
        Route::post('/them', [KhuyenMaiController::class, 'postThem'])->name('admin.khuyen-mai.store');
        Route::get('/sua/{id}', [KhuyenMaiController::class, 'getSua'])->name('admin.khuyen-mai.sua');
        Route::post('/sua/{id}', [KhuyenMaiController::class, 'postSua'])->name('admin.khuyen-mai.update');
        Route::get('/xoa/{id}', [KhuyenMaiController::class, 'getXoa'])->name('admin.khuyen-mai.xoa');
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
    });

    // Quản lý Tiện nghi
    Route::prefix('tien-nghi')->group(function() {
        Route::get('/', [TienNghiController::class, 'getDanhSach'])->name('admin.tien-nghi');
        Route::get('/them', [TienNghiController::class, 'getThem'])->name('admin.tien-nghi.them');
        Route::post('/them', [TienNghiController::class, 'postThem'])->name('admin.tien-nghi.store');
        Route::get('/sua/{id}', [TienNghiController::class, 'getSua'])->name('admin.tien-nghi.sua');
        Route::post('/sua/{id}', [TienNghiController::class, 'postSua'])->name('admin.tien-nghi.update');
        Route::get('/xoa/{id}', [TienNghiController::class, 'getXoa'])->name('admin.tien-nghi.xoa');
    });
});