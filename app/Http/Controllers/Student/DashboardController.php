<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // 1. Obtener IDs de lecciones completadas para este estudiante
        $completedLessonIds = LessonProgress::where('student_id', $student->id)
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();

        // 2. Cargar inscripciones con relaciones y conteos optimizados
        $enrollments = $student->enrollments()
            ->with([
                'course.instructor', 
                'course.categories',
                'course.topics.lessons'
            ]) 
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Obtener todas las entregas de proyectos y certificados del estudiante de una vez
        $submissions = \App\Models\ProjectSubmission::where('student_id', $student->id)->get()->keyBy('course_id');
        $certificates = \App\Models\Certificate::where('student_id', $student->id)->get()->keyBy('course_id');

        // 4. Calcular métricas
        $inProgressCount = 0;
        $completedCount = 0;

        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            
            // Calculamos conteos filtrando por los que están en temas (módulos), igual que en Classroom
            $allCourseLessons = $course->topics->pluck('lessons')->flatten();
            $totalLessons = $allCourseLessons->count();
            $completedInCourse = $allCourseLessons->whereIn('id', $completedLessonIds)->count();
            
            $evalConfig = $course->evaluation_config ?? [];
            $hasExam = $evalConfig['final_exam_enabled'] ?? false;
            $hasProject = $evalConfig['final_project_enabled'] ?? false;
            $hasFinalStep = $hasExam || $hasProject;

            $finalStepDone = false;
            if ($hasFinalStep) {
                if ($hasExam && $enrollment->final_score !== null) {
                    $finalStepDone = true;
                } elseif ($hasProject) {
                    $submission = $submissions->get($course->id);
                    if ($submission) $finalStepDone = true;
                }
            }

            $totalItems = $totalLessons + ($hasFinalStep ? 1 : 0);
            $completedItems = $completedInCourse + ($finalStepDone ? 1 : 0);
            $percent = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
            
            $enrollment->calculated_progress = $percent;
            $enrollment->completed_lessons_count = $completedInCourse;
            $enrollment->total_lessons_count = $totalLessons;
            $enrollment->my_certificate = $certificates->get($course->id);

            if ($enrollment->is_completed || $percent >= 100) {
                $completedCount++;
                $enrollment->is_really_completed = true;
            } else {
                $inProgressCount++;
                $enrollment->is_really_completed = false;
            }
        }

        return view('student.dashboard', compact(
            'student', 
            'enrollments', 
            'inProgressCount', 
            'completedCount'
        ));
    }
}