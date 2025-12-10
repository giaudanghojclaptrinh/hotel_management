<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietDatPhong extends Model
{
    use HasFactory;

    // Định nghĩa tên bảng cho chắc chắn
    protected $table = 'chi_tiet_dat_phongs';

    // QUAN TRỌNG: Khai báo các cột được phép lưu (Mass Assignment)
    // Phải khớp với các trường trong BookingController@store
    // Trong App\Models\ChiTietDatPhong.php
    protected $fillable = [
        'dat_phong_id',
        'loai_phong_id',
        'phong_id',
        'so_luong',
        'don_gia',    // <--- Phải có
        'thanh_tien', // <--- Phải có
    ];

    /**
     * Quan hệ ngược lại với bảng Đặt phòng
     */
    public function datPhong()
    {
        return $this->belongsTo(DatPhong::class, 'dat_phong_id');
    }

    /**
     * Quan hệ với bảng Loại phòng
     */
    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'loai_phong_id');
    }

    /**
     * Quan hệ với bảng Phòng cụ thể
     */
    public function phong()
    {
        return $this->belongsTo(Phong::class, 'phong_id');
    }
}