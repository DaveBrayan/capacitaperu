<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IndividualCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'slug', 
        'registration_number', 
        'hours', 
        'instructor_id', 
        'category_id', 
        'start_date', 
        'end_date', 
        'certificate_template', 
        'cover_image',
        'status',
        'user_id'
    ];

    protected $casts = [
        'certificate_template' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(IndividualCertificateStudent::class, 'individual_certificate_id');
    }

    public function courseCertificate()
    {
        return $this->hasOne(CourseCertificate::class, 'individual_certificate_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . uniqid();
            }
        });
    }
}
