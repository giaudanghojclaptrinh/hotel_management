<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TienNghi extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong Database (Tùy chọn nếu tên bảng chuẩn là 'tien_nghis')
     */
    protected $table = 'tien_nghis';

    /**
     * Các cột được phép thêm/sửa (Mass Assignment)
     */
    protected $fillable = [
        'ten_tien_nghi',  // Tên hiển thị (Ví dụ: Wifi tốc độ cao)
        'ma_tien_nghi',   // Mã dùng trong code (Ví dụ: wifi)
        'icon'            // Class icon (Ví dụ: fa-solid fa-wifi)
    ];

    /**
     * Định nghĩa mối quan hệ Nhiều - Nhiều với bảng LoaiPhong
     * * Logic: Một Tiện nghi (Wifi) có thể thuộc về nhiều Loại phòng khác nhau (Deluxe, Standard...).
     */
    public function loaiPhongs() 
    {
        // Cú pháp: belongsToMany(ModelĐích, BảngTrungGian, KhóaNgoạiCủaModelNày, KhóaNgoạiCủaModelKia)
        return $this->belongsToMany(
            LoaiPhong::class,           // 1. Model liên kết
            'loai_phong_tien_nghi',     // 2. Tên bảng trung gian (Pivot Table)
            'tien_nghi_id',             // 3. Khóa ngoại trỏ về bảng TienNghi
            'loai_phong_id'             // 4. Khóa ngoại trỏ về bảng LoaiPhong
        );
    }
}