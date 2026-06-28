<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['key', 'group', 'label'];

    // CAMBIO IMPORTANTE: Relación con Usuarios, no con Roles
    public function users()
    {
        return $this->belongsToMany(User::class, 'permission_user');
    }
}