<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
    protected $fillable = ['name', 'position', 'phone', 'avatar_url', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
