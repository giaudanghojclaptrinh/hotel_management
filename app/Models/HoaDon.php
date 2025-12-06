<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoaDon extends Model
{

    protected $fillable = [
        'dat_phong_id',
        'ma_hoa_don',
        'ngay_lap',
        'tong_tien',
        'trang_thai',
    ];

    /**
     * The booking (DatPhong) this invoice belongs to.
     */
    public function datPhong(): BelongsTo
    {
        return $this->belongsTo(DatPhong::class);
    }
}
