<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_name',
        'student_dni',
        'format',
        'certificates_count',
        'certificates_details'
    ];

    protected $casts = [
        'certificates_details' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
