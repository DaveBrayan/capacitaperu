<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'code', 
        'discount_type', 
        'value', 
        'target_type', 
        'target_ids', 
        'exception_ids', // <--- FALTABA ESTE CAMPO
        'start_date', 
        'end_date', 
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'target_ids' => 'array',
        'exception_ids' => 'array', // <--- IMPORTANTE: Convertir JSON a Array automáticamente
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Accesors para lógica de vista
    public function getStatusLabelAttribute()
    {
        $now = now();
        // Si tienes una columna is_active en BD, úsala. Si no, quita la línea siguiente.
        if (isset($this->attributes['is_active']) && !$this->is_active) return 'Inactiva';
        
        if ($now->lessThan($this->start_date)) return 'Programada';
        if ($now->greaterThan($this->end_date)) return 'Vencida';
        return 'Activa';
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->end_date || now()->greaterThan($this->end_date)) return 0;
        return now()->diffInDays($this->end_date, false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}