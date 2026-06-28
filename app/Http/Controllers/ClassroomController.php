<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Models\Enrollment;
use App\Models\ProjectSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    public function show($courseSlug, $lessonSlug = null)
    {
        $student = Auth::guard('student')->user();
        
        $course = Course::with(['topics' => function($q) {
            $q->orderBy('order_index', 'asc');
        }, 'topics.lessons' => function($q) {
            $q->orderBy('order_index', 'asc');
        }])->where('slug', $courseSlug)->firstOrFail();

        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        $currentLesson = null;
        if ($lessonSlug) {
            $currentLesson = CourseLesson::where('course_id', $course->id)
                ->where(function($q) use ($lessonSlug) {
                    $q->where('slug', $lessonSlug)->orWhere('id', $lessonSlug);
                })->firstOrFail();
        } else {
            $firstTopic = $course->topics->first();
            $currentLesson = $firstTopic ? $firstTopic->lessons->first() : null;
        }

        $prevLesson = null;
        $nextLesson = null;
        
        if ($currentLesson) {
            $allLessons = $course->topics->pluck('lessons')->flatten();
            $currentIndex = $allLessons->search(function($lesson) use ($currentLesson) {
                return $lesson->id === $currentLesson->id;
            });
            $prevLesson = ($currentIndex !== false && $currentIndex > 0) ? $allLessons[$currentIndex - 1] : null;
            $nextLesson = ($currentIndex !== false && $currentIndex < $allLessons->count() - 1) ? $allLessons[$currentIndex + 1] : null;
        }

        $quizAttempt = null;
        if ($currentLesson && $currentLesson->type === 'quiz') {
            $quizAttempt = LessonProgress::where('student_id', $student->id)
                ->where('lesson_id', $currentLesson->id)
                ->first();
        }

        $finalExamScore = $enrollment ? $enrollment->final_score : null;
        
        $projectSubmission = ProjectSubmission::where('course_id', $course->id)
                                        ->where('student_id', $student->id)
                                        ->first();

        // CÁLCULO DE PROGRESO
        $allLessonsCollection = $course->topics->pluck('lessons')->flatten();
        $totalLessonsCount = $allLessonsCollection->count();
        
        $completedLessonIds = LessonProgress::where('student_id', $student->id)
            ->whereIn('lesson_id', $allLessonsCollection->pluck('id'))
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();
            
        $completedLessonsCount = count($completedLessonIds);

        // Porcentaje de ASISTENCIA ACADÉMICA
        $attendancePercent = $totalLessonsCount > 0 ? round(($completedLessonsCount / $totalLessonsCount) * 100) : 0;

        // Porcentaje VISUAL
        $evalConfig = $course->evaluation_config ?? [];
        $hasExam = $evalConfig['final_exam_enabled'] ?? false;
        $hasProject = $evalConfig['final_project_enabled'] ?? false;
        
        $hasFinalStep = $hasExam || $hasProject;
        $finalStepDone = false;

        if ($hasFinalStep) {
            if ($hasExam && $finalExamScore !== null) {
                $finalStepDone = true;
            } elseif ($hasProject && $projectSubmission) {
                $finalStepDone = true; 
            }
        }

        $totalVisualItems = $totalLessonsCount + ($hasFinalStep ? 1 : 0);
        $completedVisualItems = $completedLessonsCount + ($finalStepDone ? 1 : 0);
        $progress = $totalVisualItems > 0 ? round(($completedVisualItems / $totalVisualItems) * 100) : 0;

        if ($enrollment && $enrollment->progress_percent != $progress) {
            $enrollment->progress_percent = $progress;
            $enrollment->save();
        }

        // CALCULAR TOTAL PUNTOS EXAMEN (Para normalización en vista)
        $examTotalPoints = 100;
        if ($hasExam) {
            $questions = $course->evaluation_config['final_exam_data']['questions'] ?? [];
            $calculatedTotal = collect($questions)->sum(function($q) {
                return intval($q['points'] ?? 0);
            });
            if ($calculatedTotal > 0) $examTotalPoints = $calculatedTotal;
        }

        return view('classroom.show', compact(
            'course', 'currentLesson', 'prevLesson', 'nextLesson', 
            'progress', 'attendancePercent', 
            'quizAttempt', 'finalExamScore', 'completedLessonIds',
            'projectSubmission', 'examTotalPoints'
        ));
    }

    public function submitFinalProject(Request $request, $courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $student = Auth::guard('student')->user();

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:51200'
        ]);

        $savedPaths = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs("project_submissions/{$course->id}/{$student->id}", $filename, 'public');
                $savedPaths[] = 'storage/' . $path;
            }
        }

        ProjectSubmission::updateOrCreate(
            ['course_id' => $course->id, 'student_id' => $student->id],
            [
                'files' => $savedPaths,
                'status' => 'pending',
                'score' => null, 
                'feedback' => null
            ]
        );

        return redirect()->back()->with('success', '¡Trabajo enviado correctamente! Está en revisión.');
    }

    public function cancelFinalProject($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $student = Auth::guard('student')->user();

        $submission = ProjectSubmission::where('course_id', $course->id)
                                       ->where('student_id', $student->id)
                                       ->firstOrFail();

        if ($submission->files && is_array($submission->files)) {
            foreach ($submission->files as $filePath) {
                $relativePath = str_replace('storage/', '', $filePath);
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            }
        }

        $submission->delete();
        return redirect()->back()->with('warning', 'Envío cancelado. Los archivos han sido eliminados.');
    }

    public function completeLesson(Request $request, $courseSlug, $lessonId)
    {
        $student = Auth::guard('student')->user();
        $lesson = CourseLesson::findOrFail($lessonId);

        LessonProgress::firstOrCreate(
            ['student_id' => $student->id, 'lesson_id' => $lesson->id],
            ['completed' => true, 'completed_at' => now(), 'score' => null]
        );

        return redirect()->back()->with('success', 'Lección completada.');
    }

    public function submitQuiz(Request $request, $courseSlug, $lessonSlug)
    {
        $student = Auth::guard('student')->user();
        $lesson = CourseLesson::where('slug', $lessonSlug)->orWhere('id', $lessonSlug)->firstOrFail();
        
        $exists = LessonProgress::where('student_id', $student->id)->where('lesson_id', $lesson->id)->exists();
        if ($exists) {
            return redirect()->back()->with('warning', 'Ya has realizado este examen.');
        }

        $questions = $lesson->attachments['questions'] ?? [];
        $earnedPoints = 0;

        foreach ($questions as $index => $q) {
            $points = intval($q['points'] ?? 0);
            $answer = $request->input('q_' . $index);

            if (isset($q['correct']) && in_array($q['type'], ['single', 'multiple'])) {
                $correct = $q['correct'];
                if (is_array($correct) && is_array($answer)) {
                    if (!array_diff($correct, $answer) && !array_diff($answer, $correct)) $earnedPoints += $points;
                } elseif ($answer == $correct) {
                    $earnedPoints += $points;
                }
            } else {
                 if(!empty($answer)) $earnedPoints += $points;
            }
        }

        LessonProgress::create([
            'student_id' => $student->id,
            'lesson_id' => $lesson->id,
            'completed' => true,
            'score' => $earnedPoints,
            'completed_at' => now()
        ]);

        return redirect()->back()->with('success', "Quiz completado. Nota: $earnedPoints");
    }

    // --- CORRECCIÓN AQUÍ: Guardado de respuestas ---
    public function submitFinalExam(Request $request, $courseSlug)
    {
        $student = Auth::guard('student')->user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $enrollment = Enrollment::where('student_id', $student->id)->where('course_id', $course->id)->first();
        
        if ($enrollment && $enrollment->final_score !== null) {
             return redirect()->back()->with('warning', 'Ya has realizado el examen final.');
        }

        $questions = $course->evaluation_config['final_exam_data']['questions'] ?? [];
        $earnedPoints = 0;
        $studentAnswers = []; // Array para guardar respuestas

        foreach ($questions as $index => $q) {
            $points = intval($q['points'] ?? 0);
            $answer = $request->input('q_' . $index);
            
            // Guardamos la respuesta con la misma clave que usará el Admin (q_0, q_1...)
            $studentAnswers['q_' . $index] = $answer;

            if (isset($q['correct']) && in_array($q['type'], ['single', 'multiple'])) {
                $correct = $q['correct'];
                if (is_array($correct) && is_array($answer)) {
                    // Comparación estricta de arrays para selección múltiple
                    if (!array_diff($correct, $answer) && !array_diff($answer, $correct)) $earnedPoints += $points;
                } elseif ($answer == $correct) {
                    $earnedPoints += $points;
                }
            } else {
                 // Preguntas abiertas: Asignamos puntos si no está vacío (o lógica personalizada)
                 if(!empty($answer)) $earnedPoints += $points;
            }
        }

        if ($enrollment) {
            $enrollment->final_score = $earnedPoints;
            $enrollment->exam_answers = $studentAnswers; // <--- GUARDADO DE RESPUESTAS AQUI
            
            // CÁLCULO DE PROGRESO (Asistencia) para calcular la nota ponderada final
            $allLessonsCollection = $course->topics->pluck('lessons')->flatten();
            $totalLessonsCount = $allLessonsCollection->count();
            $completedLessonsCount = LessonProgress::where('student_id', $student->id)
                ->whereIn('lesson_id', $allLessonsCollection->pluck('id'))
                ->where('completed', true)
                ->count();
            $attendancePercent = $totalLessonsCount > 0 ? round(($completedLessonsCount / $totalLessonsCount) * 100) : 0;

            $evalConfig = $course->evaluation_config ?? [];
            $minScore = $evalConfig['min_score'] ?? 70;
            $attWeight = $evalConfig['attendance_weight'] ?? 20;
            $examWeight = $evalConfig['final_assessment_weight'] ?? 80;

            $attPoints = ($attendancePercent * $attWeight) / 100;
            
            // Calcular total de puntos posibles del examen final
            $maxPossible = 100;
            $questionsList = $evalConfig['final_exam_data']['questions'] ?? [];
            $calculatedTotal = collect($questionsList)->sum(function($q) {
                return intval($q['points'] ?? 0);
            });
            if ($calculatedTotal > 0) $maxPossible = $calculatedTotal;

            $examPoints = ($earnedPoints / $maxPossible) * $examWeight;
            $finalTotalScore = round($attPoints + $examPoints, 2);

            if ($finalTotalScore >= $minScore) {
                $enrollment->is_completed = true;
            } else {
                $enrollment->is_completed = false;
            }
            
            $enrollment->save();
        }

        return redirect()->back()->with('success', "Examen enviado. Calificación: $earnedPoints");
    }
}