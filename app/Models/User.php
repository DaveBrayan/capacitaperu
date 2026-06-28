<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     * AQUÍ ES DONDE OCURRÍA EL ERROR: Faltaban los campos nuevos.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',   // <--- Agregado
        'email',
        'password',
        'role',       // <--- Agregado
        'pin',        // <--- Agregado
        'avatar_url', // <--- Agregado
        'status',     // <--- Agregado
        'force_pass_reset', // <--- Agregado
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin', // Es buena práctica ocultar el PIN también
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================
    // LÓGICA DE PERMISOS (LO QUE FALTABA)
    // =========================================================

    /**
     * Relación: Un usuario tiene muchos permisos directos.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    /**
     * Helper para verificar si el usuario tiene un permiso específico.
     * Útil para usar en Blade: @if($user->hasPermission('courses.create'))
     */
    public function hasPermission($key)
    {
        // El superadmin y el admin siempre tienen acceso a todo
        if ($this->role === 'superadmin' || $this->role === 'admin') {
            return true;
        }
        
        // Verifica si la colección de permisos cargados contiene la llave
        return $this->permissions->contains('key', $key);
    }
}