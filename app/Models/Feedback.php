<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * Explicit table name because 'feedback' is uninflected by the pluralizer.
     */
    protected $table = 'feedbacks';

    protected $fillable = [
        'name', 'email', 'message', 'handled', 'handled_at'
    ];

    protected $casts = [
        'handled' => 'boolean',
        'handled_at' => 'datetime',
    ];
}
