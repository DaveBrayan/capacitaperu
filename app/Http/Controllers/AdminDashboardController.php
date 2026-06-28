<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filtro por defecto: month
        $filter = $request->input('filter', 'month');
        $now = Carbon::now();
        
        // Configurar rangos de fecha
        if ($filter === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
            $prevStartDate = $now->copy()->subDay()->startOfDay();
            $prevEndDate = $now->copy()->subDay()->endOfDay();
            $periodLabel = 'Hoy';
            $chartInterval = 'PT1H'; // Por horas
            $chartFormat = 'H:00';
            $dbFormat = 'H';
        } elseif ($filter === '7_days') {
            $startDate = $now->copy()->subDays(6)->startOfDay(); // Últimos 7 días incluyendo hoy
            $endDate = $now->copy()->endOfDay();
            $prevStartDate = $now->copy()->subDays(13)->startOfDay();
            $prevEndDate = $now->copy()->subDays(7)->endOfDay();
            $periodLabel = 'Últimos 7 días';
            $chartInterval = 'P1D'; // Por días
            $chartFormat = 'd M';
            $dbFormat = 'Y-m-d';
        } else { // month
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $prevStartDate = $now->copy()->subMonth()->startOfMonth();
            $prevEndDate = $now->copy()->subMonth()->endOfMonth();
            $periodLabel = 'Este Mes';
            $chartInterval = 'P1D'; // Por días
            $chartFormat = 'd';
            $dbFormat = 'Y-m-d';
        }

        // 1. ESTUDIANTES (Nuevos en el periodo)
        $totalStudents = Student::count(); // Histórico total para mostrar
        $newStudentsCurrent = Student::whereBetween('created_at', [$startDate, $endDate])->count();
        $newStudentsPrev = Student::whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        
        $studentGrowth = 0;
        if ($newStudentsPrev > 0) {
            $studentGrowth = (($newStudentsCurrent - $newStudentsPrev) / $newStudentsPrev) * 100;
        } else {
            $studentGrowth = $newStudentsCurrent > 0 ? 100 : 0;
        }

        // 2. CURSOS ACTIVOS
        $activeCourses = Course::where('status', 'published')->where('type', 'course')->count();
        $newCoursesPeriod = Course::where('status', 'published')
            ->where('type', 'course')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 2.2 DIPLOMADOS ACTIVOS
        $activeDiplomas = Course::where('status', 'published')->where('type', 'diploma')->count();
        $newDiplomasPeriod = Course::where('status', 'published')
            ->where('type', 'diploma')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 2.3 CERTIFICADOS INDIVIDUALES
        $totalIndividualCertificates = \App\Models\IndividualCertificate::count();
        $newIndividualCertificatesPeriod = \App\Models\IndividualCertificate::whereBetween('created_at', [$startDate, $endDate])->count();

        // 3. INGRESOS (En el periodo)
        $currentRevenue = Enrollment::whereBetween('created_at', [$startDate, $endDate])->sum('price_paid');
        $prevRevenue = Enrollment::whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('price_paid');

        $revenueGrowth = 0;
        if ($prevRevenue > 0) {
            $revenueGrowth = (($currentRevenue - $prevRevenue) / $prevRevenue) * 100;
        } else {
            $revenueGrowth = $currentRevenue > 0 ? 100 : 0;
        }

        // 4. TASA DE FINALIZACIÓN (Histórico General)
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('is_completed', true)->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100) : 0;

        // 5. INSCRIPCIONES RECIENTES (Filtradas por el periodo, o últimas 5 si es muy corto)
        $recentQuery = Enrollment::with(['student', 'course'])
            ->orderBy('created_at', 'desc');
        
        // Si el filtro es "hoy" y hay pocas, mostramos las de hoy. Si no hay de hoy, muestra vacío o las últimas históricas según prefieras.
        // Aquí forzaremos a mostrar las últimas 5 generales para que la tabla no se vea vacía si no hubo ventas hoy.
        $recentEnrollments = $recentQuery->take(5)->get();

        // 6. DATOS PARA GRÁFICO
        $chartLabels = [];
        $chartData = [];
        $courseChartData = [];
        $diplomaChartData = [];
        $certificateChartData = [];

        // Obtener ventas brutas del periodo
        $rawSales = Enrollment::whereBetween('created_at', [$startDate, $endDate])
            ->select('created_at', 'price_paid')
            ->get();

        // Obtener inscripciones del periodo para separar por curso/diplomado
        $rawEnrollments = Enrollment::whereBetween('created_at', [$startDate, $endDate])
            ->with('course')
            ->get();

        // Obtener certificados individuales del periodo
        $rawCertificates = \App\Models\IndividualCertificate::whereBetween('created_at', [$startDate, $endDate])
            ->select('created_at')
            ->get();

        // Generar periodo de fechas/horas para llenar huecos en 0
        $period = new \DatePeriod($startDate, new \DateInterval($chartInterval), $endDate);

        foreach ($period as $dt) {
            $label = $dt->format($chartFormat);
            $chartLabels[] = $label;

            // 1. Ingresos
            if ($filter === 'today') {
                $sum = $rawSales->filter(function ($sale) use ($dt) {
                    return $sale->created_at->format('H') == $dt->format('H');
                })->sum('price_paid');
            } else {
                $sum = $rawSales->filter(function ($sale) use ($dt) {
                    return $sale->created_at->format('Y-m-d') == $dt->format('Y-m-d');
                })->sum('price_paid');
            }
            $chartData[] = $sum;

            // 2. Conteo Cursos
            $courseCount = $rawEnrollments->filter(function ($enrollment) use ($dt, $filter) {
                if (!$enrollment->course || $enrollment->course->type !== 'course') return false;
                if ($filter === 'today') {
                    return $enrollment->created_at->format('H') == $dt->format('H');
                } else {
                    return $enrollment->created_at->format('Y-m-d') == $dt->format('Y-m-d');
                }
            })->count();
            $courseChartData[] = $courseCount;

            // 3. Conteo Diplomados
            $diplomaCount = $rawEnrollments->filter(function ($enrollment) use ($dt, $filter) {
                if (!$enrollment->course || $enrollment->course->type !== 'diploma') return false;
                if ($filter === 'today') {
                    return $enrollment->created_at->format('H') == $dt->format('H');
                } else {
                    return $enrollment->created_at->format('Y-m-d') == $dt->format('Y-m-d');
                }
            })->count();
            $diplomaChartData[] = $diplomaCount;

            // 4. Conteo Certificados
            $certCount = $rawCertificates->filter(function ($cert) use ($dt, $filter) {
                if ($filter === 'today') {
                    return $cert->created_at->format('H') == $dt->format('H');
                } else {
                    return $cert->created_at->format('Y-m-d') == $dt->format('Y-m-d');
                }
            })->count();
            $certificateChartData[] = $certCount;
        }

        return view('admin.dashboard', compact(
            'filter',
            'periodLabel',
            'totalStudents', 
            'newStudentsCurrent', 
            'studentGrowth',
            'activeCourses',
            'newCoursesPeriod',
            'activeDiplomas',
            'newDiplomasPeriod',
            'totalIndividualCertificates',
            'newIndividualCertificatesPeriod',
            'currentRevenue',
            'revenueGrowth',
            'completionRate',
            'recentEnrollments',
            'chartLabels',
            'chartData',
            'courseChartData',
            'diplomaChartData',
            'certificateChartData'
        ));
    }
}