<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phong extends Model
{
    protected $table = 'phongs';
    protected $fillable = [
        'loai_phong_id',
        'so_phong',
        'tinh_trang',
    ];

    public const tinh_trang_options = [
        'available' => [
            'label' => 'Phòng trống',
            'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200 ring-emerald-500', // Màu xanh ngọc (Sẵn sàng đón khách)
            'icon' => 'fa-door-open'
        ],
        'occupied' => [
            'label' => 'Đang có khách',
            'class' => 'bg-blue-100 text-blue-800 border-blue-200 ring-blue-500', // Màu xanh dương (Đang sử dụng)
            'icon' => 'fa-user-check'
        ],
        'cleaning' => [
            'label' => 'Đang dọn dẹp',
            'class' => 'bg-amber-100 text-amber-800 border-amber-200 ring-amber-500', // Màu vàng (Cảnh báo nhẹ)
            'icon' => 'fa-broom'
        ],
        'maintenance' => [
            'label' => 'Bảo trì / Sửa chữa',
            'class' => 'bg-red-100 text-red-800 border-red-200 ring-red-500', // Màu đỏ (Không thể dùng)
            'icon' => 'fa-screwdriver-wrench'
        ],
    ];

    /**
     * The LoaiPhong this Phong belongs to.
     */
    public function loaiPhong(): BelongsTo
    {
        return $this->belongsTo(LoaiPhong::class);
    }

    /**
     * Booking details referencing this Phong.
     */
    public function chiTietDatPhongs(): HasMany
    {
        return $this->hasMany(ChiTietDatPhong::class);
    }
}
