<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\LessonProgress;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\ProjectSubmission; // <--- ESTA ES LA LÍNEA QUE CORRIGE EL ERROR 500
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'Todos');

        $query = Student::whereHas('enrollments', function($q) {
            $q->where('status', 'approved');
        });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ci_nit', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status !== 'Todos') {
            $val = ($status === 'Activos') ? 'active' : 'inactive';
            $query->where('status', $val);
        }

        $students = $query->withCount(['enrollments' => function($q) {
            $q->where('status', 'approved');
        }])->with(['enrollments' => function($q) {
            $q->where('status', 'approved')->latest()->take(2);
        }, 'enrollments.course'])
        ->orderBy('name', 'asc')
        ->paginate(15)
        ->withQueryString();
        
        $avatars = [
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Bob',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Mila',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Jack',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Zoe',
        ];

        return view('admin.students.index', compact('students', 'avatars', 'search'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
        ]);

        $student->fill($request->except(['avatar_file', 'password']));

        if ($request->hasFile('avatar_file')) {
            if ($student->avatar_url && !Str::startsWith($student->avatar_url, 'http')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $student->avatar_url));
            }
            $path = $request->file('avatar_file')->store('avatars/students', 'public');
            $student->avatar_url = 'storage/' . $path;
        }

        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->back()->with('success', 'Información actualizada.');
    }

    public function resetPassword(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $newPass = Str::random(8); 
        
        $student->password = Hash::make($newPass);
        $student->force_pass_reset = true;
        $student->save();

        return redirect()->back()
            ->with('success', 'Contraseña restablecida.')
            ->with('credentials_modal', [
                'name' => $student->name,
                'email' => $student->email,
                'password' => $newPass,
                'course' => 'Acceso General'
            ]);
    }

    public function resetPin(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->pin = null;
        $student->save();

        return redirect()->back()
            ->with('success', 'PIN eliminado. El estudiante deberá configurar uno nuevo al ingresar.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->back()->with('success', 'Estudiante eliminado.');
    }

    public function history($id)
    {
        $student = Student::findOrFail($id);
        
        $history = Enrollment::with(['course.topics.lessons'])
            ->where('student_id', $id)
            ->where('status', 'approved')
            ->get()
            ->map(function ($enrollment) use ($student) {
                $course = $enrollment->course;
                $certificate = Certificate::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->first();

                $progressData = LessonProgress::where('student_id', $student->id)
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->get()
                    ->keyBy('lesson_id');

                $topics = $course->topics->map(function ($topic) use ($progressData) {
                    return [
                        'id' => $topic->id,
                        'title' => $topic->title,
                        'lessons' => $topic->lessons->map(function ($lesson) use ($progressData) {
                            $prog = $progressData[$lesson->id] ?? null;
                            return [
                                'id' => $lesson->id,
                                'title' => $lesson->title,
                                'type' => $lesson->type,
                                'is_completed' => $prog ? (bool)$prog->completed : false,
                                'score' => $prog ? $prog->score : null
                            ];
                        })
                    ];
                });

                return [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'progress_percent' => $enrollment->progress_percent,
                    'final_score' => $enrollment->final_score,
                    'exam_answers' => $enrollment->exam_answers, // Enviamos las respuestas
                    'is_completed' => (bool)$enrollment->is_completed,
                    'enrolled_at' => $enrollment->enrolled_at->format('d/m/Y'),
                    'certificate_uuid' => $certificate ? $certificate->uuid : null,
                    'evaluation_config' => $course->evaluation_config,
                    'topics' => $topics
                ];
            });

        return response()->json($history);
    }

    public function toggleProgress(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'lesson_id' => 'required|exists:course_lessons,id',
            'completed' => 'required|boolean'
        ]);

        $studentId = $request->student_id;
        $lessonId = $request->lesson_id;
        $completed = $request->completed;

        LessonProgress::updateOrCreate(
            ['student_id' => $studentId, 'lesson_id' => $lessonId],
            ['completed' => $completed, 'completed_at' => $completed ? now() : null]
        );

        $lesson = \App\Models\CourseLesson::find($lessonId);
        $course = $lesson->course;
        
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonProgress::where('student_id', $studentId)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->where('completed', true)
            ->count();

        $percentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->progress_percent = $percentage;
            
            $certUuid = null;
            $hasFinalEval = !empty($course->evaluation_config['final_exam_enabled']) || !empty($course->evaluation_config['final_project_enabled']);

            if ($percentage == 100 && !$hasFinalEval) {
                $enrollment->is_completed = true;
                $cert = Certificate::firstOrCreate(
                    ['student_id' => $studentId, 'course_id' => $course->id],
                    ['uuid' => (string) Str::uuid(), 'issued_at' => now()]
                );
                $certUuid = $cert->uuid;
            } elseif ($percentage < 100 && !$hasFinalEval) {
                $enrollment->is_completed = false;
            }
            
            $enrollment->save();

            return response()->json([
                'success' => true,
                'new_progress' => $percentage,
                'is_completed' => (bool)$enrollment->is_completed,
                'certificate_uuid' => $certUuid
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function resetEvaluation($studentId, $courseId)
    {
        try {
            $enrollment = Enrollment::where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->first();

            if ($enrollment) {
                $enrollment->final_score = null;
                $enrollment->exam_answers = null;
                $enrollment->is_completed = false;
                $enrollment->save();
            }

            ProjectSubmission::where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->delete();

            return response()->json(['success' => true, 'message' => 'Evaluación reiniciada correctamente.']);

        } catch (\Exception $e) {
            Log::error("Error resetEvaluation: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error en servidor: ' . $e->getMessage()], 500);
        }
    }
}