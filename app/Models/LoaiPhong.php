<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoaiPhong extends Model
{

	/**
	 * Get the rooms for this LoaiPhong.
	 */
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
