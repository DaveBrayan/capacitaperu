<?php

namespace App\Models;

// Importante: Usar Authenticatable en lugar de Model para usuarios que se loguean
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'pin',
        'phone',
        'ci_nit',
        'city',
        'country',
        'avatar_url',
        'status',
        'force_pass_reset',
        'user_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // --- RELACIONES ---

    /**
     * Relación con Inscripciones (Enrollments)
     * Soluciona el error: Call to undefined method ...::enrollments()
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relación directa con Cursos (a través de inscripciones)
     * Útil si quieres acceder directo: $student->courses
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('progress_percent', 'is_completed', 'status', 'enrolled_at')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}