<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'cover_image',
        'instructor_id',
        'level',
        'status',
        'featured',
        'duration', // Carga horaria
        'evaluation_config',   
        'certificate_template', 
        'attendance_config',
        'learning_outcomes',
        'metadata',
        'benefits',
        'resources',
        'user_id'
    ];

    protected $casts = [
        'evaluation_config' => 'array',
        'certificate_template' => 'array',
        'attendance_config' => 'array',
        'learning_outcomes' => 'array',
        'metadata' => 'array',
        'resources' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'featured' => 'boolean'
    ];

    // Relación con Categorías
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Usaremos "topics" como si fueran los módulos
    public function topics()
    {
        return $this->hasMany(CourseTopic::class)->orderBy('order_index');
    }

    // ALIAS: Definimos "modules" para que apunte a "topics" y no falle la vista
    public function modules()
    {
        return $this->hasMany(CourseTopic::class)->orderBy('order_index');
    }

    // Relación directa con todas las lecciones del curso
    public function lessons()
    {
        return $this->hasMany(CourseLesson::class);
    }

    // Atributo calculado para contar lecciones totales
    public function getLessonsCountAttribute()
    {
        return $this->lessons()->count();
    }

    // --- RELACIONES FALTANTES AGREGADAS ---

    public function individualCertificate()
    {
        return $this->belongsTo(IndividualCertificate::class, 'individual_certificate_id');
    }

    public function courseCertificate()
    {
        return $this->belongsTo(CourseCertificate::class, 'course_certificate_id');
    }

    public function getActiveCertificateTemplateAttribute()
    {
        if ($this->individual_certificate_id && $this->individualCertificate) {
            return $this->individualCertificate->certificate_template;
        }
        if ($this->course_certificate_id && $this->courseCertificate) {
            return $this->courseCertificate->certificate_template;
        }
        return $this->certificate_template;
    }

    // 1. Relación con Inscripciones (Enrollments)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // 2. Relación con Entregas de Proyecto (Para que funcione el contador de pendientes)
    public function projectSubmissions()
    {
        return $this->hasMany(ProjectSubmission::class);
    }

    // --- ACCESORES DINÁMICOS DE PROMOCIÓN ---

    public function getActivePromotionAttribute()
    {
        $now = now();
        
        return Promotion::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get()
            ->first(function ($promo) {
                if ($promo->target_type === 'all') {
                    return true;
                }
                
                $targetIds = is_array($promo->target_ids) ? $promo->target_ids : json_decode($promo->target_ids ?? '[]', true);
                $targetIds = array_map('intval', $targetIds);
                
                if ($promo->target_type === 'course') {
                    return in_array($this->id, $targetIds);
                }
                
                if ($promo->target_type === 'category') {
                    $exceptionIds = is_array($promo->exception_ids) ? $promo->exception_ids : json_decode($promo->exception_ids ?? '[]', true);
                    $exceptionIds = array_map('intval', $exceptionIds);
                    
                    if (in_array($this->id, $exceptionIds)) {
                        return false;
                    }
                    
                    $courseCategoryIds = $this->categories->pluck('id')->toArray();
                    return count(array_intersect($courseCategoryIds, $targetIds)) > 0;
                }
                
                return false;
            });
    }

    public function getFinalPriceAttribute()
    {
        $originalPrice = floatval($this->price);
        
        // 1. Verificar si hay descuento estático en BD
        if (!empty($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $originalPrice) {
            return floatval($this->discount_price);
        }

        // 2. Verificar descuento dinámico por promoción activa
        $promo = $this->active_promotion;
        if ($promo) {
            if ($promo->discount_type === 'percent') {
                return max($originalPrice - ($originalPrice * ($promo->value / 100)), 0);
            } elseif ($promo->discount_type === 'fixed') {
                return max($originalPrice - floatval($promo->value), 0);
            }
        }
        
        return $originalPrice;
    }

    public function getHasDiscountAttribute()
    {
        return $this->final_price < floatval($this->price);
    }

    public function getDiscountPercentAttribute()
    {
        $originalPrice = floatval($this->price);
        $finalPrice = $this->final_price;
        
        if ($this->has_discount && $originalPrice > 0) {
            return round((($originalPrice - $finalPrice) / $originalPrice) * 100);
        }
        
        return 0;
    }
}