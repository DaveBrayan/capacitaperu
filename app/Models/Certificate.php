<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'student_id',
        'course_id',
        'issued_at',
        'download_url',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    // Relación con el Estudiante
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relación con el Curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}