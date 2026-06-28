<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPromotionController extends Controller
{
    public function index()
    {
        $timezone = \App\Models\Setting::get('app_timezone', 'America/Lima');
        
        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);
        
        foreach ($promotions as $promo) {
            $promo->local_start_date = $promo->start_date ? $promo->start_date->timezone($timezone)->format('Y-m-d\TH:i') : null;
            $promo->local_end_date = $promo->end_date ? $promo->end_date->timezone($timezone)->format('Y-m-d\TH:i') : null;
        }
        
        $courses = Course::where('status', 'published')
            ->with('categories:id')
            ->get(['id', 'title', 'type']);
            
        $categories = Category::where('status', true)->select('id', 'name')->get();

        return view('admin.promotions.index', compact('promotions', 'courses', 'categories', 'timezone'));
    }

    public function store(Request $request)
    {
        $this->validatePromotion($request);
        
        DB::transaction(function () use ($request) {
            $data = $this->prepareData($request);
            $data['user_id'] = auth()->id();
            
            $promotion = Promotion::create($data);
            $this->applyDiscountToCourses($promotion);
        });

        return redirect()->back()->with('success', 'Promoción creada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $this->validatePromotion($request);
        $promotion = Promotion::findOrFail($id);

        DB::transaction(function () use ($request, $promotion) {
            $this->revertDiscountFromCourses($promotion);
            
            $data = $this->prepareData($request);
            $promotion->update($data);
            
            $this->applyDiscountToCourses($promotion);
        });

        return redirect()->back()->with('success', 'Promoción actualizada correctamente.');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        
        DB::transaction(function () use ($promotion) {
            $this->revertDiscountFromCourses($promotion);
            $promotion->delete();
        });

        return redirect()->back()->with('success', 'Promoción eliminada.');
    }

    // --- HELPER PARA PREPARAR DATOS ---
    private function prepareData(Request $request)
    {
        $data = $request->all();

        // 1. ELIMINAMOS json_encode MANUAL. 
        // El Modelo Promotion tiene 'casts' => 'array', así que pasamos el array directo.
        // Si viene null, pasamos null o array vacío.
        $data['target_ids'] = $request->target_ids ?? [];
        $data['exception_ids'] = $request->exception_ids ?? [];

        // 2. Manejo de Fechas (Guardar hora de acuerdo a la zona horaria configurada)
        $timezone = \App\Models\Setting::get('app_timezone', 'America/Lima');
        if ($request->start_date) {
            $data['start_date'] = Carbon::parse($request->start_date, $timezone)->setTimezone('UTC');
        }
        if ($request->end_date) {
            $data['end_date'] = Carbon::parse($request->end_date, $timezone)->setTimezone('UTC');
        }

        return $data;
    }

    // --- LÓGICA DE PRECIOS ---

    private function applyDiscountToCourses(Promotion $promo)
    {
        // Si no está activa (por fecha o flag), no aplicamos nada
        if (!$promo->is_active) return;

        $courses = $this->getTargetCourses($promo);

        foreach ($courses as $course) {
            $originalPrice = $course->price;
            $discountAmount = 0;

            if ($promo->discount_type === 'percent') {
                $discountAmount = $originalPrice * ($promo->value / 100);
            } else {
                $discountAmount = $promo->value;
            }

            $finalPrice = max($originalPrice - $discountAmount, 0); 
            
            $course->discount_price = $finalPrice;
            $course->save();
        }
    }

    private function revertDiscountFromCourses(Promotion $promo)
    {
        $courses = $this->getTargetCourses($promo);
        
        foreach ($courses as $course) {
            $course->discount_price = null; 
            $course->save();
        }
    }

    private function getTargetCourses($promo)
    {
        $query = Course::query();

        // Gracias al cast 'array' del Modelo, esto YA es un array.
        // No necesitamos json_decode. Solo aseguramos que no sea null.
        $targetIds = $promo->target_ids;
        if (!is_array($targetIds)) $targetIds = [];

        $exceptionIds = $promo->exception_ids;
        if (!is_array($exceptionIds)) $exceptionIds = [];

        if ($promo->target_type === 'course') {
            // El error ocurría aquí porque $targetIds era string. Ahora es array.
            if (!empty($targetIds)) {
                $query->whereIn('id', $targetIds);
            } else {
                // Si es tipo 'course' pero no hay IDs, no devolver nada para seguridad
                return collect(); 
            }
        } elseif ($promo->target_type === 'category') {
            if (!empty($targetIds)) {
                $query->whereHas('categories', function($q) use ($targetIds) {
                    $q->whereIn('categories.id', $targetIds);
                });
            }

            // APLICAR EXCLUSIONES
            if (!empty($exceptionIds)) {
                $query->whereNotIn('id', $exceptionIds);
            }
        }

        return $query->get();
    }

    private function validatePromotion($request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'target_type' => 'required|in:all,category,course',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'target_ids' => 'nullable|array',
            'exception_ids' => 'nullable|array',
        ]);
    }
}