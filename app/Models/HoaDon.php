<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoaDon extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'dat_phong_id',
        'ma_hoa_don',
        'ngay_lap',
        'tong_tien',
        'trang_thai',
        'phuong_thuc_thanh_toan', 
    ];

    // [FIX LỖI] Khai báo cột ngày lập để tự động cast sang Carbon
    protected $casts = [
        'ngay_lap' => 'datetime', 
    ];

    /**
     * The booking (DatPhong) this invoice belongs to.
     */
    public function datPhong(): BelongsTo
    {
        return $this->belongsTo(DatPhong::class);
    }
}