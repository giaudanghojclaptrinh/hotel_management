<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DatPhong extends Model
{
    use HasFactory;

    protected $table = 'dat_phongs'; // Định nghĩa rõ tên bảng

    // SỬA LẠI FILLABLE CHO KHỚP VỚI CONTROLLER VÀ DATABASE
    protected $fillable = [
        'user_id',
        'ngay_den',      
        'ngay_di',        
        'tong_tien',      
        'trang_thai',
        'payment_status', 
    ];

    /**
     * The user who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Booking details for this DatPhong.
     */
    public function chiTietDatPhongs(): HasMany
    {
        return $this->hasMany(ChiTietDatPhong::class, 'dat_phong_id');
    }

    /**
     * Invoice for this booking (one-to-one).
     */
    public function hoaDon(): HasOne
    {
        return $this->hasOne(HoaDon::class, 'dat_phong_id');
    }
}