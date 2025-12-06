<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KhuyenMai extends Model
{
    protected $fillable = [
        'ten_khuyen_mai',
        'ma_khuyen_mai',
        'chiet_khau_phan_tram',
        'so_tien_giam_gia',
        'ngay_bat_dau',
        'ngay_ket_thuc',
    ];

    // Currently no direct foreign keys in migrations; keep simple model.
}
