<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'link_url',
        'image_url',
        'aspect',
        'ai_modified',
    ];

    protected $casts = [
        'aspect' => 'float',
        'ai_modified' => 'boolean',
    ];

    protected $appends = ['likes_count', 'comments_count'];

    // ----------- Relasi -----------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'pin_likes')
                    ->withPivot('liked_at');
    }

    public function savedBy()
    {
        return $this->belongsToMany(User::class, 'saved_pins')
                    ->withPivot('saved_at');
    }

    // ----------- Accessor -----------

    public function getLikesCountAttribute(): int
    {
        return $this->likedBy()->count();
    }

    public function getCommentsCountAttribute(): int
    {
        // Belum ada tabel komentar — selalu 0.
        return 0;
    }
}
