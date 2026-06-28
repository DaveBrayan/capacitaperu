<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'topic_id',
        'title',
        'slug',
        'type',
        'content',
        'video_source',
        'video_url',
        'duration_minutes',
        'attachments',
        'order_index',
        'is_preview'
    ];

    protected $casts = [
        'attachments' => 'array', // Para guardar JSON automáticamente
        'is_preview' => 'boolean',
    ];

    // Pertenece a un tema
    public function topic()
    {
        return $this->belongsTo(CourseTopic::class);
    }

    // Pertenece a un curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}