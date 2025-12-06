<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietDatPhong extends Model
{

    protected $table = 'chi_tiet_dat_phongs';
    protected $fillable = [
        'dat_phong_id',
        'loai_phong_id',
        'phong_id',
        'so_luong',
        'gia_tien',
    ];

    /**
     * The booking this detail belongs to.
     */
    public function datPhong(): BelongsTo
    {
        return $this->belongsTo(DatPhong::class);
    }

    /**
     * The LoaiPhong referenced by this detail.
     */
    public function loaiPhong(): BelongsTo
    {
        return $this->belongsTo(LoaiPhong::class);
    }

    /**
     * The Phong referenced by this detail.
     */
    public function phong(): BelongsTo
    {
        return $this->belongsTo(Phong::class);
    }
}
