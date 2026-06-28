<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', true)
            ->whereIn('target_type', ['course', 'both'])
            ->withCount(['courses' => function ($q) {
                $q->where('status', 'published')->where('type', 'course');
            }])
            ->get();

        $coursesQuery = Course::with(['categories', 'instructor'])
            ->where('status', 'published')
            ->where('type', 'course');

        if ($request->filled('categories')) {
            $categoryIds = array_filter($request->categories);
            if (!empty($categoryIds)) {
                $coursesQuery->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        } elseif ($request->filled('category')) {
            $categorySlug = $request->category;
            // Backwards compatibility redirect just in case
            if ($categorySlug === 'diplomados') {
                return redirect()->route('diplomados');
            } else {
                $coursesQuery->whereHas('categories', function ($q) use ($categorySlug) {
                    $q->where('categories.slug', $categorySlug);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $coursesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $coursesQuery
            ->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        if ($request->ajax()) {
            return view('front.partials.catalog-grid', compact('courses'))->render();
        }

        return view('front.catalog', compact('courses', 'categories'));
    }

    public function diplomados(Request $request)
    {
        $categories = Category::where('status', true)
            ->whereIn('target_type', ['diploma', 'both'])
            ->withCount(['courses' => function ($q) {
                $q->where('status', 'published')->where('type', 'diploma');
            }])
            ->get();

        $coursesQuery = Course::with(['categories', 'instructor'])
            ->where('status', 'published')
            ->where('type', 'diploma');

        if ($request->filled('categories')) {
            $categoryIds = array_filter($request->categories);
            if (!empty($categoryIds)) {
                $coursesQuery->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $coursesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $coursesQuery
            ->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        if ($request->ajax()) {
            return view('front.partials.catalog-grid', compact('courses'))->render();
        }

        $isDiplomados = true;

        return view('front.catalog', compact('courses', 'categories', 'isDiplomados'));
    }

    // --- NUEVO MÉTODO: Muestra el detalle del curso ---
    public function show($slug)
    {
        // Buscamos el curso por su slug y cargamos sus relaciones
        $course = Course::where('slug', $slug)
            ->with(['categories', 'instructor', 'modules.lessons'])
            ->where('status', 'published')
            ->firstOrFail(); // Si no existe, lanza error 404

        // Retornamos la vista pasando la variable $course
        return view('front.course', compact('course'));
    }
}