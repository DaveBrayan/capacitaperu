<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Category;
use App\Models\CourseLesson;
use App\Models\ProjectSubmission; // Importante
use App\Models\Enrollment; // Importante
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; 

class AdminCourseController extends Controller
{
    public function index(Request $request)
    {
        $type = request()->routeIs('admin.diplomas.*') ? 'diploma' : 'course';

        // Cargamos la relación 'enrollments' para contar estudiantes
        $courses = Course::with(['instructor', 'categories'])
                        ->where('type', $type)
                        ->withCount('enrollments') // Cuenta automática de inscritos
                        ->withCount(['projectSubmissions as pending_projects_count' => function ($query) {
                            $query->where('status', 'pending');
                        }])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
                        
        $viewType = $type == 'diploma' ? 'Diplomado' : 'Curso';
        $routeNamePrefix = $type == 'diploma' ? 'admin.diplomas.' : 'admin.courses.';

        return view('admin.courses.index', compact('courses', 'type', 'viewType', 'routeNamePrefix'));
    }

    // --- NUEVO: MÉTODO PARA VER DETALLES (AJAX/MODAL) ---
    public function show($id)
    {
        $course = Course::with(['instructor', 'categories', 'topics.lessons'])->findOrFail($id);
        
        // Retornamos JSON para el modal
        return response()->json([
            'title' => $course->title,
            'instructor' => $course->instructor ? $course->instructor->name : 'Sin instructor',
            'level' => $course->level,
            'price' => $course->price,
            'cover' => $course->cover_image ? asset($course->cover_image) : null,
            'description' => Str::limit(strip_tags($course->description), 200),
            'modules_count' => $course->topics->count(),
            'lessons_count' => $course->lessons->count(),
            'students_count' => $course->enrollments()->count(),
            'created_at' => $course->created_at->format('d/m/Y'),
            'status' => $course->status
        ]);
    }

    // --- NUEVO: OBTENER TRABAJOS DEL CURSO ---
    public function getProjectSubmissions($courseId)
    {
        $submissions = ProjectSubmission::with('student')
            ->where('course_id', $courseId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'student_name' => $sub->student->name,
                    'student_email' => $sub->student->email,
                    'status' => $sub->status,
                    'score' => $sub->score,
                    'feedback' => $sub->feedback,
                    'files' => $sub->files, // Array de rutas
                    'date' => $sub->created_at->format('d/m/Y H:i'),
                    // Rutas para descarga (limpiamos 'storage/' para usar media.show o asset directo)
                    'file_urls' => array_map(function($f) {
                        return asset($f); 
                    }, $sub->files ?? [])
                ];
            });

        return response()->json($submissions);
    }

    // --- NUEVO: CALIFICAR TRABAJO ---
    public function gradeProject(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);

        $submission = ProjectSubmission::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $submission->score = $request->score;
            $submission->feedback = $request->feedback;
            $submission->status = 'approved'; // O podrías añadir lógica para 'rejected' si la nota es baja
            $submission->save();

            // ACTUALIZAR INSCRIPCIÓN (Nota Final)
            // Buscar la configuración del curso para saber la nota mínima
            $course = $submission->course;
            $minScore = $course->evaluation_config['min_score'] ?? 70;
            
            // Calculamos nota final ponderada (simplificado: si es proyecto único, la nota del proyecto es la final o parte de ella)
            // Asumimos que si hay proyecto, es la nota final principal por ahora, o podrías mezclar con asistencia.
            // Para este ejemplo simple, actualizamos final_score en enrollment con la nota del proyecto.
            
            $enrollment = Enrollment::where('course_id', $submission->course_id)
                                    ->where('student_id', $submission->student_id)
                                    ->first();

            if ($enrollment) {
                $enrollment->final_score = $request->score;
                
                // CÁLCULO DE PROGRESO (Asistencia) para calcular la nota ponderada final
                $allLessonsCollection = $course->topics->pluck('lessons')->flatten();
                $totalLessonsCount = $allLessonsCollection->count();
                $completedLessonsCount = \App\Models\LessonProgress::where('student_id', $submission->student_id)
                    ->whereIn('lesson_id', $allLessonsCollection->pluck('id'))
                    ->where('completed', true)
                    ->count();
                $attendancePercent = $totalLessonsCount > 0 ? round(($completedLessonsCount / $totalLessonsCount) * 100) : 0;

                $evalConfig = $course->evaluation_config ?? [];
                $minScore = $evalConfig['min_score'] ?? 70;
                $attWeight = $evalConfig['attendance_weight'] ?? 20;
                $examWeight = $evalConfig['final_assessment_weight'] ?? 80;

                $attPoints = ($attendancePercent * $attWeight) / 100;
                
                // Calcular total de puntos posibles del proyecto final
                $maxPossible = $evalConfig['final_project_data']['score'] ?? 100;
                if ($maxPossible <= 0) $maxPossible = 100;

                $projectPoints = ($request->score / $maxPossible) * $examWeight;
                $finalTotalScore = round($attPoints + $projectPoints, 2);

                if ($finalTotalScore >= $minScore) {
                    $enrollment->is_completed = true;
                } else {
                    $enrollment->is_completed = false;
                }
                
                $enrollment->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Trabajo calificado correctamente.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al calificar: ' . $e->getMessage()], 500);
        }
    }

    // ... (Mantener create, store, edit, update, destroy tal como estaban antes)
    public function create(Request $request)
    {
        $type = request()->routeIs('admin.diplomas.*') ? 'diploma' : 'course';
        $viewType = $type == 'diploma' ? 'Diplomado' : 'Curso';
        $routeNamePrefix = $type == 'diploma' ? 'admin.diplomas.' : 'admin.courses.';

        $instructors = Instructor::where('status', 'active')->get();
        $categories = Category::where('status', true)
            ->whereIn('target_type', [$type, 'both'])
            ->get(); 
        $course = new Course();
        $course->type = $type;
        $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature  = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');
        $executive_seal      = \App\Models\Setting::where('key', 'individual_cert_executive_seal')->value('value');
        $academic_seal       = \App\Models\Setting::where('key', 'individual_cert_academic_seal')->value('value');
        return view('admin.courses.create', compact('instructors', 'course', 'categories', 'executive_signature', 'academic_signature', 'executive_seal', 'academic_seal', 'type', 'viewType', 'routeNamePrefix'));
    }

    public function store(Request $request)
    {
        Log::info('--- INICIO STORE CURSO ---');
        
        $request->validate([
            'title' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $course = new Course();
            $course->type = $request->input('type', 'course');
            $course->title = $request->title;
            $course->slug = Str::slug($request->title) . '-' . uniqid();
            $course->price = $request->price;
            $course->description = $request->description;
            $course->short_description = $request->short_description;
            $course->instructor_id = $request->instructor_id;
            $course->level = $request->level ?? 'Básico';
            $course->status = $request->input('status', 'published');
            $course->user_id = auth()->id();

            $course->duration = $request->duration;
            $course->metadata = $request->metadata;

            // 1. Decodificar JSONs Config Base
            $evalConfig = [];
            if ($request->filled('evaluation_config')) {
                $evalConfig = json_decode($request->evaluation_config, true) ?? [];
            }
            
            // 2. Manejar Archivo de Ejemplo del Proyecto (SI SE SUBIÓ)
            if ($request->hasFile('project_example_file')) {
                $path = $request->file('project_example_file')->store('course_files', 'public');
                $evalConfig['project_file'] = 'storage/' . $path; // Agregamos la ruta al array
            }
            
            // 3. Asignar config final al curso
            $course->evaluation_config = $evalConfig;
            
            if ($request->filled('certificate_data')) {
                $decoded = json_decode($request->certificate_data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $course->certificate_template = $decoded;
                }
            }

            if ($request->filled('attendance_data')) {
                $course->attendance_config = json_decode($request->attendance_data, true) ?? [];
            }

            // Beneficios y Recursos
            $course->benefits = $request->benefits;
            $course->resources = $request->input('resources', []);

            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('courses', 'public');
                $course->cover_image = 'storage/' . $path;
            }

            $course->save();

            if ($request->has('categories')) {
                $course->categories()->sync($request->categories);
            }

            // Builder
            if ($request->filled('builder_data')) {
                $this->saveTopicsSmart($course, json_decode($request->builder_data, true));
            }

            DB::commit();
            $routeNamePrefix = $course->type == 'diploma' ? 'admin.diplomas.' : 'admin.courses.';
            return redirect()->route($routeNamePrefix . 'edit', $course->id)
                             ->with('success', 'Programa creado y publicado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store curso: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        Log::info("--- UPDATE CURSO {$id} ---");
        $course = Course::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $course->title = $request->title;
            $course->price = $request->price;
            
            if ($request->has('description')) $course->description = $request->description;
            if ($request->has('short_description')) $course->short_description = $request->short_description;
            if ($request->has('instructor_id')) $course->instructor_id = $request->instructor_id;
            if ($request->has('level')) $course->level = $request->level;
            if ($request->has('status')) $course->status = $request->status;

            $course->duration = $request->duration;
            $course->metadata = array_merge($course->metadata ?? [], $request->input('metadata', []));

            // 1. Manejo de Evaluation Config + Archivo
            $evalConfig = $course->evaluation_config ?? []; // Mantener config existente
            
            if ($request->has('evaluation_config')) {
                $newConfig = json_decode($request->evaluation_config, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Fusionamos para no perder datos previos si el JSON viene parcial
                    $evalConfig = array_merge($evalConfig, $newConfig);
                }
            }

            // SUBIDA DE ARCHIVO DE EJEMPLO
            if ($request->hasFile('project_example_file')) {
                // Borrar anterior si existe
                if (isset($evalConfig['project_file'])) {
                    $oldPath = str_replace('storage/', '', $evalConfig['project_file']);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                $path = $request->file('project_example_file')->store('course_files', 'public');
                $evalConfig['project_file'] = 'storage/' . $path;
            }

            $course->evaluation_config = $evalConfig;

            // Otros JSONs
            if ($request->has('certificate_data')) {
                $decoded = json_decode($request->certificate_data, true);
                if (json_last_error() === JSON_ERROR_NONE) $course->certificate_template = $decoded;
            }

            if ($request->has('attendance_data')) {
                $decoded = json_decode($request->attendance_data, true);
                if (json_last_error() === JSON_ERROR_NONE) $course->attendance_config = $decoded;
            }

            // Beneficios y Recursos
            if ($request->has('benefits')) {
                $course->benefits = $request->benefits;
            }
            if ($request->has('resources')) {
                $course->resources = $request->input('resources', []);
            }

            // Imagen Portada
            if ($request->input('delete_cover') == '1') {
                if ($course->cover_image) {
                    $relativePath = str_replace('storage/', '', $course->cover_image);
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                    $course->cover_image = null;
                }
            }

            if ($request->hasFile('cover_image')) {
                if ($course->cover_image && $request->input('delete_cover') != '1') {
                     $oldPath = str_replace('storage/', '', $course->cover_image);
                     if(Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                     }
                }
                $path = $request->file('cover_image')->store('courses', 'public');
                $course->cover_image = 'storage/' . $path;
            }

            $course->save();

            if ($request->has('categories')) {
                $course->categories()->sync($request->categories);
            }

            if ($request->filled('builder_data')) {
                $this->saveTopicsSmart($course, json_decode($request->builder_data, true));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Curso actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update curso: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function saveTopicsSmart($course, $topicsData)
    {
        if (!is_array($topicsData)) return;

        $keptTopicIds = [];
        $keptLessonIds = [];

        foreach ($topicsData as $topicIndex => $topicData) {
            $topicId = $topicData['id'] ?? null;
            $topic = null;

            if ($topicId && $topicId !== 'new') {
                $topic = $course->topics()->find($topicId);
            }

            if ($topic) {
                $topic->update([
                    'title' => $topicData['title'] ?? 'Módulo sin nombre',
                    'order_index' => $topicIndex
                ]);
            } else {
                $topic = $course->topics()->create([
                    'title' => $topicData['title'] ?? 'Módulo sin nombre',
                    'order_index' => $topicIndex
                ]);
            }
            $keptTopicIds[] = $topic->id;

            if (isset($topicData['lessons']) && is_array($topicData['lessons'])) {
                foreach ($topicData['lessons'] as $lessonIndex => $lessonData) {
                    $lessonId = $lessonData['id'] ?? null;
                    $lesson = null;

                    $videoSource = 'video_first';
                    if (isset($lessonData['hide_video']) && $lessonData['hide_video'] == true) $videoSource = 'hidden';
                    elseif (isset($lessonData['content_order']) && $lessonData['content_order'] === 'text_first') $videoSource = 'text_first';

                    $attachments = null;
                    if (($lessonData['type'] ?? 'video') === 'quiz' && isset($lessonData['quiz_data'])) {
                        $attachments = $lessonData['quiz_data'];
                    }

                    $lessonPayload = [
                        'topic_id' => $topic->id,
                        'title' => $lessonData['title'] ?? 'Lección',
                        'type' => $lessonData['type'] ?? 'video',
                        'content' => $lessonData['content'] ?? null,
                        'video_url' => $lessonData['video_url'] ?? null,
                        'video_source' => $videoSource,
                        'duration_minutes' => $lessonData['duration'] ?? 0,
                        'is_preview' => isset($lessonData['is_preview']) ? (bool)$lessonData['is_preview'] : false,
                        'attachments' => $attachments,
                        'order_index' => $lessonIndex
                    ];

                    if ($lessonId && $lessonId !== 'new') {
                        $lesson = CourseLesson::find($lessonId);
                    }

                    if ($lesson) {
                        if ($lesson->course_id == $course->id) {
                            $lesson->update($lessonPayload);
                        }
                    } else {
                        $lessonPayload['course_id'] = $course->id;
                        $lessonPayload['slug'] = Str::slug($lessonPayload['title']) . '-' . uniqid();
                        $lesson = CourseLesson::create($lessonPayload);
                    }
                    $keptLessonIds[] = $lesson->id;
                }
            }
        }

        $course->lessons()->whereNotIn('id', $keptLessonIds)->delete();
        $course->topics()->whereNotIn('id', $keptTopicIds)->delete();
    }
    
    public function edit(Request $request, $id)
    {
        $type = request()->routeIs('admin.diplomas.*') ? 'diploma' : 'course';
        $viewType = $type == 'diploma' ? 'Diplomado' : 'Curso';
        $routeNamePrefix = $type == 'diploma' ? 'admin.diplomas.' : 'admin.courses.';

        $course = Course::with(['topics.lessons', 'instructor', 'categories'])->findOrFail($id);
        $instructors = Instructor::where('status', 'active')->get();
        $categories = Category::where('status', true)
            ->whereIn('target_type', [$type, 'both'])
            ->get();
        $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature  = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');
        $executive_seal      = \App\Models\Setting::where('key', 'individual_cert_executive_seal')->value('value');
        $academic_seal       = \App\Models\Setting::where('key', 'individual_cert_academic_seal')->value('value');
        return view('admin.courses.create', compact('course', 'instructors', 'categories', 'executive_signature', 'academic_signature', 'executive_seal', 'academic_seal', 'type', 'viewType', 'routeNamePrefix'));
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $routeNamePrefix = $course->type == 'diploma' ? 'admin.diplomas.' : 'admin.courses.';
        $course->delete();
        return redirect()->route($routeNamePrefix . 'index')->with('success', 'Programa eliminado.');
    }
}