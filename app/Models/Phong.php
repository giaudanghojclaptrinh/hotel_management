<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phong extends Model
{

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
