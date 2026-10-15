<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'template_data',
        'preview_image',
        'user_id',
    ];

    protected $casts = [
        'template_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
