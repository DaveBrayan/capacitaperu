<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndividualCertificateStudent extends Model
{
    protected $fillable = [
        'individual_certificate_id',
        'fullname',
        'dni',
        'uuid',
        'is_custom',
        'start_date',
        'end_date',
        'hours',
        'issue_date',
        'registration_number',
        'user_id'
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'issue_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $prefix = strtoupper(\Illuminate\Support\Str::random(4));
                $year = date('Y');
                $suffix = strtoupper(\Illuminate\Support\Str::random(4));
                
                $newUuid = "{$prefix}-{$year}-{$suffix}";
                
                // Asegurar que sea único
                while (self::where('uuid', $newUuid)->exists()) {
                    $prefix = strtoupper(\Illuminate\Support\Str::random(4));
                    $suffix = strtoupper(\Illuminate\Support\Str::random(4));
                    $newUuid = "{$prefix}-{$year}-{$suffix}";
                }
                
                $model->uuid = $newUuid;
            }
        });
    }

    public function certificate()
    {
        return $this->belongsTo(IndividualCertificate::class, 'individual_certificate_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
