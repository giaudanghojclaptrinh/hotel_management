<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMaiUsage extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai_usage';

    protected $fillable = [
        'user_id',
        'khuyen_mai_id',
        'used_count',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id');
    }
}
