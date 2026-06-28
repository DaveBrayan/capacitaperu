<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationToken extends Model
{
    protected $fillable = ['token', 'course_id', 'type', 'is_used', 'expires_at'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}