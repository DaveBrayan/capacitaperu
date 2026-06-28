<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'price_paid',
        'enrolled_at',
        'progress_percent',
        'is_completed',
        'receipt_url', // Agregamos este campo para la boleta
        'status',      // Agregamos estado (pending, approved)
        'exam_answers',
        'user_id'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'is_completed' => 'boolean',
        'price_paid' => 'decimal:2',
        'exam_answers' => 'array',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}