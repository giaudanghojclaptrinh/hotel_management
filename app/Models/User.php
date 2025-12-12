<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cccd',
        'phone',
        'role',
        'password',
    ];
    
    public const roles = [
        'admin',
        'user'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Ensure passwords are hashed when set. If a value already looks like
     * a hash (bcrypt/argon prefix), leave it as-is to avoid double-hashing.
     */
    public function setPasswordAttribute(?string $value): void
    {
        if (empty($value)) {
            return;
        }

        // Common hash prefixes (bcrypt, argon)
        if (Str::startsWith($value, ['$2y$', '$2a$', '$argon$', '$argon2i$', '$argon2id$'])) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the bookings (dat_phongs) for the user.
     */
    public function datPhongs(): HasMany
    {
        return $this->hasMany(DatPhong::class);
    }

    /**
     * Override default password reset notification to send Vietnamese email.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
