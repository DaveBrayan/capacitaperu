<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\LessonProgress; // Importante agregar esto
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    public function getOrCreate(Request $request, Course $course)
    {
        Log::info("--- [CERTIFICADO] Intento de generar certificado ---");
        
        $student = auth('student')->user();
        Log::info("Estudiante ID: " . $student->id . " | Curso ID: " . $course->id);

        // 1. Validar Inscripción
        try {
            $enrollment = Enrollment::where('student_id', $student->id)
                                    ->where('course_id', $course->id)
                                    ->firstOrFail();
        } catch (\Exception $e) {
            Log::error("[CERTIFICADO] No se encontró inscripción.");
            return redirect()->back()->with('warning', 'No estás inscrito en este curso.');
        }

        // 2. CALCULAR PROGRESO (Asistencia)
        $totalLessons = $course->lessons()->count();
        $completedCount = LessonProgress::where('student_id', $student->id)
            ->where('completed', true)
            ->whereIn('lesson_id', $course->lessons()->select('id'))
            ->count();

        $progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        // 3. Obtener Configuración de Evaluación
        $evalConfig = $course->evaluation_config ?? [];
        $minScore = $evalConfig['min_score'] ?? 70;
        $attWeight = $evalConfig['attendance_weight'] ?? 20;
        $examWeight = $evalConfig['final_assessment_weight'] ?? 80;
        
        $isExamEnabled = $evalConfig['final_exam_enabled'] ?? false;
        $isProjectEnabled = $evalConfig['final_project_enabled'] ?? false;
        $hasExam = $isExamEnabled || $isProjectEnabled;

        // 4. Calcular Nota Final Ponderada (Igual que en la Vista)
        $examScore = $enrollment->final_score ?? 0;
        
        // Obtener la escala máxima del examen/proyecto para normalización
        $maxPossible = 100;
        if ($isProjectEnabled) {
            $maxPossible = $evalConfig['final_project_data']['score'] ?? 100;
        } elseif ($isExamEnabled) {
            $questions = $evalConfig['final_exam_data']['questions'] ?? [];
            $calculatedTotal = collect($questions)->sum(function($q) {
                return intval($q['points'] ?? 0);
            });
            if ($calculatedTotal > 0) $maxPossible = $calculatedTotal;
        }
        if ($maxPossible <= 0) $maxPossible = 100;

        // Puntos por asistencia
        $attPoints = ($progress * $attWeight) / 100;
        // Puntos por examen/proyecto normalizado
        $examPoints = ($examScore / $maxPossible) * $examWeight;
        
        $finalTotalScore = round($attPoints + $examPoints, 2);

        Log::info("[CERTIFICADO] Progreso: $progress% | Exam Score: $examScore | Final Total: $finalTotalScore (Min: $minScore)");

        // 5. Validaciones de Negocio (Alineadas con classroom/show.blade.php)
        
        if ($hasExam) {
            // Si hay examen, el requisito es la nota final aprobatoria
            $canGetCertificate = $finalTotalScore >= $minScore;
            $failReason = "Tu nota final ($finalTotalScore) no alcanza el mínimo ($minScore).";
        } else {
            // Si NO hay examen, el requisito es el 100% de progreso
            $canGetCertificate = $progress >= 100;
            $failReason = "Debes completar todas las lecciones (100%) para obtener el certificado.";
        }

        if (!$canGetCertificate) {
            Log::warning("[CERTIFICADO] Falló: $failReason");
            return redirect()->back()->with('warning', $failReason);
        }

        // 6. Validar Fecha de Publicación
        $certConfig = $course->certificate_template ?? [];
        $releaseMode = $certConfig['release_mode'] ?? 'completion';
        
        if ($releaseMode === 'scheduled' && !empty($certConfig['release_date'])) {
            $releaseDate = Carbon::parse($certConfig['release_date']);
            if (now()->lessThan($releaseDate)) {
                return redirect()->back()->with('warning', 'Certificado disponible a partir del ' . $releaseDate->format('d/m/Y'));
            }
        }

        // 7. Generar o Recuperar Registro de Certificado
        $cert = Certificate::firstOrCreate(
            ['student_id' => $student->id, 'course_id' => $course->id],
            [
                'uuid' => strtoupper(Str::random(4)) . '-' . now()->year . '-' . strtoupper(Str::random(4)),
                'issued_at' => now(),
            ]
        );

        return redirect()->route('certificate.verify', $cert->uuid);
    }

}