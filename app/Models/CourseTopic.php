<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'summary',
        'order_index'
    ];

    // Un tema pertenece a un curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Un tema tiene muchas lecciones
    public function lessons()
    {
        return $this->hasMany(CourseLesson::class, 'topic_id')->orderBy('order_index');
    }
}