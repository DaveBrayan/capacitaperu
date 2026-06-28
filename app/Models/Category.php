<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'status',
        'user_id',
        'target_type'
    ];

    /**
     * Relación con Cursos.
     * Nota: Como en la tabla 'courses' guardamos el nombre de la categoría (string)
     * y no un ID, definimos las claves personalizadas aquí.
     * * Foreign Key en Course: 'category'
     * Local Key en Category: 'name'
     */
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'category_course',   // tabla pivot
            'category_id',       // FK de Category en pivot
            'course_id'          // FK de Course en pivot
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}