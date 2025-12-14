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

    protected $table = 'dat_phongs';

    protected $fillable = [
        'user_id',
        'ngay_den',      
        'ngay_di',
        'subtotal',       // Tạm tính trước thuế
        'tong_tien',      
        'trang_thai',
        'payment_status',
        'payment_method', // Thêm cột này
        'promotion_code',
        'discount_amount',
        'vat_amount',     // Thuế VAT 8%
        'ghi_chu',
        'cancel_reason',  // Lý do hủy đơn
        'cancelled_at',   // Thời gian hủy đơn
    ];

    // [FIX LỖI] Khai báo các trường ngày tháng để tự động convert sang đối tượng Carbon
    protected $casts = [
        'ngay_den' => 'date',
        'ngay_di' => 'date',
        'cancelled_at' => 'datetime',
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