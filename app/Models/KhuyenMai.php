<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    use HasFactory;
    
    // Đảm bảo tên cột khớp với bảng khuyến_mais của bạn
    protected $fillable = [
        'ten_khuyen_mai', 
        'ma_khuyen_mai', 
        'chiet_khau_phan_tram', // Thêm cột này
        'so_tien_giam_gia',    // Thêm cột này
        'ngay_bat_dau', 
        'ngay_ket_thuc',
    ];
    
    // Đảm bảo kiểu dữ liệu là số thập phân
    protected $casts = [
        'chiet_khau_phan_tram' => 'float',
        'so_tien_giam_gia' => 'float',
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
    ];
}