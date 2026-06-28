<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function show($courseSlug, $moduleId)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $topic = CourseTopic::where('id', $moduleId)->where('course_id', $course->id)->firstOrFail();
        
        $config = $course->attendance_config ?? [];
        $questions = $config['questions'] ?? [];

        return view('front.attendance.form', [
            'course' => $course,
            'topic' => $topic,
            'questions' => $questions,
            'courseTitle' => $course->title,
            'moduleTitle' => $topic->title
        ]);
    }

    public function store(Request $request, $courseSlug, $moduleId)
    {
        $request->validate([
            'dni' => 'required|string',
            'answers' => 'nullable|array'
        ]);

        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $topic = CourseTopic::where('id', $moduleId)->where('course_id', $course->id)->firstOrFail();

        // 1. Buscar Estudiante
        $student = Student::where('ci_nit', $request->dni)->first();

        if (!$student) {
            return back()->withErrors(['dni' => 'No encontramos un estudiante inscrito con este documento (CI/NIT).'])->withInput();
        }

        // 2. Verificar duplicados de asistencia
        $exists = Attendance::where('student_id', $student->id)
                            ->where('topic_id', $topic->id)
                            ->exists();

        if ($exists) {
            return back()->with('info', 'Ya has registrado tu asistencia para este módulo anteriormente.');
        }

        // 3. Guardar registro de Asistencia
        Attendance::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'topic_id' => $topic->id,
            'answers' => $request->answers,
            'attended_at' => now(),
        ]);

        // 4. ACTUALIZAR PROGRESO (CORREGIDO)
        // Marcamos como completadas SOLO las lecciones de contenido (video, texto, etc.)
        // Los QUIZZES/EXÁMENES se deben resolver aparte.
        $lessons = $topic->lessons;

        if ($lessons->count() > 0) {
            foreach ($lessons as $lesson) {
                // CORRECCIÓN: Si es un quiz, NO lo marcamos como completado automáticamente.
                if ($lesson->type !== 'quiz') {
                    LessonProgress::firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'lesson_id' => $lesson->id
                        ],
                        [
                            'completed' => true,
                            'completed_at' => now(),
                            'score' => null
                        ]
                    );
                }
            }
        }

        return back()->with('success', '¡Asistencia registrada! El contenido del módulo se ha marcado como visto (excepto evaluaciones).');
    }
}