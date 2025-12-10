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

// --- 2. IMPORT CLIENT CONTROLLERS ---
use App\Http\Controllers\Client\PageController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\ProfileController;

Auth::routes();

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (KHÁCH HÀNG - AI CŨNG XEM ĐƯỢC)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('trang_chu');
Route::get('/home', [PageController::class, 'home'])->name('home'); // Alias tránh lỗi cũ

// Danh sách & Chi tiết (Client View)
Route::get('/danh-sach-phong', [PageController::class, 'rooms'])->name('phong');
Route::get('/chi-tiet-phong/{id}', [PageController::class, 'roomDetail'])->name('phong.chi-tiet');
Route::get('/uu-dai', [PageController::class, 'promotions'])->name('khuyen-mai');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (KHÁCH HÀNG ĐÃ ĐĂNG NHẬP)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // 1. Hồ sơ cá nhân
    Route::get('/ho-so', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/ho-so', [ProfileController::class, 'update'])->name('profile.update');

    // 2. Lịch sử đặt phòng (User xem của chính mình)
    // Lưu ý: Cần đảm bảo hàm getDanhSach trong DatPhongController có logic lọc theo Auth::id() nếu user gọi
    Route::get('/lich-su-dat-phong', [DatPhongController::class, 'getDanhSach'])->name('bookings.history');

    // 3. Quy trình đặt phòng (Yêu cầu đủ hồ sơ)
    Route::middleware(['check.profile'])->group(function () {
        Route::get('/dat-phong/xac-nhan', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/dat-phong/luu', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/dat-phong/thanh-cong', [BookingController::class, 'success'])->name('booking.success');
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (QUẢN TRỊ VIÊN)
|--------------------------------------------------------------------------
| Tất cả route trong này đều có tiền tố '/admin'
*/
Route::prefix('admin')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]) // Chặn User thường
    ->group(function () {
    
    // Dashboard: domain.com/admin/dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // --- NHÓM 1: QUẢN LÝ LOẠI PHÒNG ---
    // URL bắt đầu bằng: /admin/loai-phong
    Route::prefix('loai-phong')->group(function() {
        Route::get('/', [LoaiPhongController::class, 'getDanhSach'])->name('admin.loai-phong');       // Danh sách
        Route::get('/them', [LoaiPhongController::class, 'getThem'])->name('admin.loai-phong.them');   // Form thêm
        Route::post('/them', [LoaiPhongController::class, 'postThem'])->name('admin.loai-phong.store'); // Xử lý thêm
        Route::get('/sua/{id}', [LoaiPhongController::class, 'getSua'])->name('admin.loai-phong.sua'); // Form sửa
        Route::post('/sua/{id}', [LoaiPhongController::class, 'postSua'])->name('admin.loai-phong.update'); // Xử lý sửa
        Route::get('/xoa/{id}', [LoaiPhongController::class, 'getXoa'])->name('admin.loai-phong.xoa'); // Xóa
    });

    // --- NHÓM 2: QUẢN LÝ PHÒNG ---
    // URL bắt đầu bằng: /admin/phong
    Route::prefix('phong')->group(function() {
        Route::get('/', [PhongController::class, 'getDanhSach'])->name('admin.phong');
        Route::get('/them', [PhongController::class, 'getThem'])->name('admin.phong.them');
        Route::post('/them', [PhongController::class, 'postThem'])->name('admin.phong.store');
        Route::get('/sua/{id}', [PhongController::class, 'getSua'])->name('admin.phong.sua');
        Route::post('/sua/{id}', [PhongController::class, 'postSua'])->name('admin.phong.update');
        Route::get('/xoa/{id}', [PhongController::class, 'getXoa'])->name('admin.phong.xoa');
    });

    // --- NHÓM 3: QUẢN LÝ ĐẶT PHÒNG ---
    // URL bắt đầu bằng: /admin/dat-phong
    Route::prefix('dat-phong')->group(function() {
        Route::get('/', [DatPhongController::class, 'getDanhSach'])->name('admin.dat-phong');
        Route::get('/sua/{id}', [DatPhongController::class, 'getSua'])->name('admin.dat-phong.sua');
        Route::post('/sua/{id}', [DatPhongController::class, 'postSua'])->name('admin.dat-phong.update');
        Route::get('/xoa/{id}', [DatPhongController::class, 'getXoa'])->name('admin.dat-phong.xoa');
    });

    // --- NHÓM 4: QUẢN LÝ KHUYẾN MÃI ---
    Route::prefix('khuyen-mai')->group(function() {
        Route::get('/', [KhuyenMaiController::class, 'getDanhSach'])->name('admin.khuyen-mai');
        Route::get('/them', [KhuyenMaiController::class, 'getThem'])->name('admin.khuyen-mai.them');
        Route::post('/them', [KhuyenMaiController::class, 'postThem'])->name('admin.khuyen-mai.store');
        Route::get('/sua/{id}', [KhuyenMaiController::class, 'getSua'])->name('admin.khuyen-mai.sua');
        Route::post('/sua/{id}', [KhuyenMaiController::class, 'postSua'])->name('admin.khuyen-mai.update');
        Route::get('/xoa/{id}', [KhuyenMaiController::class, 'getXoa'])->name('admin.khuyen-mai.xoa');
    });

    // --- NHÓM 5: QUẢN LÝ HÓA ĐƠN ---
    Route::prefix('hoa-don')->group(function() {
        Route::get('/', [HoaDonController::class, 'getDanhSach'])->name('admin.hoa-don');
        Route::get('/sua/{id}', [HoaDonController::class, 'getSua'])->name('admin.hoa-don.sua');
        Route::post('/sua/{id}', [HoaDonController::class, 'postSua'])->name('admin.hoa-don.update');
        Route::get('/xoa/{id}', [HoaDonController::class, 'getXoa'])->name('admin.hoa-don.xoa');
    });
    
    // --- NHÓM 6: QUẢN LÝ NGƯỜI DÙNG (USERS) ---
    // URL bắt đầu bằng: /admin/users (số nhiều cho chuẩn)
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'getDanhSach'])->name('admin.users'); // Danh sách
        Route::get('/them', [UserController::class, 'getThem'])->name('admin.user.them'); // Form thêm
        Route::post('/them', [UserController::class, 'postThem'])->name('admin.user.store'); // Xử lý thêm
        Route::get('/sua/{id}', [UserController::class, 'getSua'])->name('admin.user.sua'); // Form sửa
        Route::post('/sua/{id}', [UserController::class, 'postSua'])->name('admin.user.update'); // Xử lý sửa
        Route::get('/xoa/{id}', [UserController::class, 'getXoa'])->name('admin.user.xoa'); // Xóa
    });

});