<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'files',
        'status',
        'score',
        'feedback'
    ];

    protected $casts = [
        'files' => 'array', // Importante para que Laravel convierta el JSON a Array automáticamente
    ];

    // Relación con el Estudiante (Modelo Student)
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Relación con el Curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}