<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KhuyenMai extends Model
{
    protected $fillable = [
        'ma_khuyen_mai',
        'muc_giam_gia',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'trang_thai',
    ];

    // Currently no direct foreign keys in migrations; keep simple model.
}
