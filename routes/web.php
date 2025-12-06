<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChiTietDatPhongController;
use App\Http\Controllers\DatPhongController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\LoaiPhongController;
use App\Http\Controllers\PhongController;
use App\Http\Controllers\UserController;

Auth::routes();

// Trang chủ
route::get('/', [HomeController::class, 'index'])->name('home');
route::get('/trang-chu', [HomeController::class, 'index'])->name('home');   

// quản lý loại phòng
route::get('/loai-phong', [LoaiPhongController::class, 'getDanhSach'])->name('loai-phong');
route::get('/loai-phong/them', [LoaiPhongController::class, 'getThem'])->name('loai-phong.them');
route::post('/loai-phong/them', [LoaiPhongController::class, 'postThem'])->name('loai-phong.them');
route::get('/loai-phong/sua/{id}', [LoaiPhongController::class, 'getSua'])->name('loai-phong.sua');
route::post('/loai-phong/sua/{id}', [LoaiPhongController::class, 'postSua'])->name('loai-phong.sua');
route::get('/loai-phong/xoa/{id}', [LoaiPhongController::class, 'getXoa'])->name('loai-phong.xoa'); 

// quản lý phòng
route::get('/phong', [PhongController::class, 'getDanhSach'])->name('phong');
route::get('/phong/them', [PhongController::class, 'getThem'])->name('phong.them');
route::post('/phong/them', [PhongController::class, 'postThem'])->name('phong.them');
route::get('/phong/sua/{id}', [PhongController::class, 'getSua'])->name('phong.sua');
route::post('/phong/sua/{id}', [PhongController::class, 'postSua'])->name('phong.sua');
route::get('/phong/xoa/{id}', [PhongController::class, 'getXoa'])->name('phong.xoa');       

// quản lý đặt phòng
route::get('/dat-phong', [DatPhongController::class, 'getDanhSach'])->name('dat-phong');
route::get('/dat-phong/them', [DatPhongController::class, 'getThem'])->name('dat-phong.them');
route::post('/dat-phong/them', [DatPhongController::class, 'postThem'])->name('dat-phong.them');
route::get('/dat-phong/sua/{id}', [DatPhongController::class, 'getSua'])->name('dat-phong.sua');
route::post('/dat-phong/sua/{id}', [DatPhongController::class, 'postSua'])->name('dat-phong.sua');
route::get('/dat-phong/xoa/{id}', [DatPhongController::class, 'getXoa'])->name('dat-phong.xoa');

// quản lý chi tiết đặt phòng
route::get('/chi-tiet-dat-phong', [ChiTietDatPhongController::class, 'getDanhSach'])->name('chi-tiet-dat-phong');
route::get('/chi-tiet-dat-phong/them', [ChiTietDatPhongController::class, 'getThem'])->name('chi-tiet-dat-phong.them');
route::post('/chi-tiet-dat-phong/them', [ChiTietDatPhongController::class, 'postThem'])->name('chi-tiet-dat-phong.them');
route::get('/chi-tiet-dat-phong/sua/{id}', [ChiTietDatPhongController::class, 'getSua'])->name('chi-tiet-dat-phong.sua');
route::post('/chi-tiet-dat-phong/sua/{id}', [ChiTietDatPhongController::class, 'postSua'])->name('chi-tiet-dat-phong.sua');
route::get('/chi-tiet-dat-phong/xoa/{id}', [ChiTietDatPhongController::class, 'getXoa'])->name('chi-tiet-dat-phong.xoa');

// quản lý hóa đơn
route::get('/hoa-don', [HoaDonController::class, 'getDanhSach'])->name('hoa-don');
route::get('/hoa-don/them', [HoaDonController::class, 'getThem'])->name('hoa-don.them');
route::post('/hoa-don/them', [HoaDonController::class, 'postThem'])->name('hoa-don.them');
route::get('/hoa-don/sua/{id}', [HoaDonController::class, 'getSua'])->name('hoa-don.sua');
route::post('/hoa-don/sua/{id}', [HoaDonController::class, 'postSua'])->name('hoa-don.sua');
route::get('/hoa-don/xoa/{id}', [HoaDonController::class, 'getXoa'])->name('hoa-don.xoa');

// quản lý người dùng
route::get('/user', [UserController::class, 'getDanhSach'])->name('user');
route::get('/user/them', [UserController::class, 'getThem'])->name('user.them');
route::post('/user/them', [UserController::class, 'postThem'])->name('user.them');
route::get('/user/sua/{id}', [UserController::class, 'getSua'])->name('user.sua');
route::post('/user/sua/{id}', [UserController::class, 'postSua'])->name('user.sua');
route::get('/user/xoa/{id}', [UserController::class, 'getXoa'])->name('user.xoa');  

