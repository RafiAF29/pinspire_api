<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'website',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ----------- Relasi -----------

    public function interests()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function pins()
    {
        return $this->hasMany(Pin::class);
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function savedPins()
    {
        return $this->belongsToMany(Pin::class, 'saved_pins')
                    ->withPivot('saved_at');
    }

    public function likedPins()
    {
        return $this->belongsToMany(Pin::class, 'pin_likes')
                    ->withPivot('liked_at');
    }
}
