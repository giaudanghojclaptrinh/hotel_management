<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DatPhong extends Model
{

    protected $fillable = [
        'user_id',
        'ngay_dat',
        'ngay_nhan_phong',
        'ngay_tra_phong',
        'trang_thai',
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
        return $this->hasMany(ChiTietDatPhong::class);
    }

    /**
     * Invoice for this booking (one-to-one).
     */
    public function hoaDon(): HasOne
    {
        return $this->hasOne(HoaDon::class);
    }
}
