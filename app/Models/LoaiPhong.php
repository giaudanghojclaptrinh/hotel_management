<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoaiPhong extends Model
{

	protected $fillable = [
        'ten_loai_phong', 
        'gia', 
        'suc_chua', 
        'mo_ta', 
        'dien_tich',
		'hinh_anh',
    ];

	public function phongs(): HasMany
	{
		return $this->hasMany(Phong::class);
	}

	/**
	 * Get the booking details for this LoaiPhong.
	 */
	public function chiTietDatPhongs(): HasMany
	{
		return $this->hasMany(ChiTietDatPhong::class, 'loai_phong_id');
	}
	public function tienNghis() {
    	return $this->belongsToMany(TienNghi::class, 'chi_tiet_tien_nghi', 'ma_loai_phong', 'ma_tien_nghi');
	}
}
