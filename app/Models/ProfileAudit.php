<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileAudit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'changes', 'ip', 'user_agent'];

    protected $casts = [
        'changes' => 'array',
    ];
}
