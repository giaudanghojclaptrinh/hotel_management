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
        'dien_tich',
		'hinh_anh',
    ];

	public function tienNghis() {
    	return $this->belongsToMany(
            TienNghi::class, 
            'loai_phong_tien_nghi', // 1. Tên bảng trung gian ĐÚNG (theo database)
            'loai_phong_id',        // 2. Khóa ngoại của bảng hiện tại (loai_phongs)
            'tien_nghi_id'          // 3. Khóa ngoại của bảng kia (tien_nghis)
		);
	}

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
}
