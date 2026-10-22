<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    protected $table = 'user_interests';

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['user_id', 'minat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
