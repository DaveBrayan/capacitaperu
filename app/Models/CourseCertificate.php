<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'course_id',
        'individual_certificate_id',
        'instructor_id',
        'title',
        'registration_number',
        'hours',
        'start_date',
        'end_date',
        'certificate_template',
        'status',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $prefix = "CP-DOC";
                $year = date('Y');
                $suffix = strtoupper(\Illuminate\Support\Str::random(4));
                $newUuid = "{$prefix}-{$year}-{$suffix}";
                
                while (self::where('uuid', $newUuid)->exists()) {
                    $suffix = strtoupper(\Illuminate\Support\Str::random(4));
                    $newUuid = "{$prefix}-{$year}-{$suffix}";
                }
                
                $model->uuid = $newUuid;
            }
        });
    }

    protected $casts = [
        'certificate_template' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function individualCertificate()
    {
        return $this->belongsTo(IndividualCertificate::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
