<?php

namespace App\Http\Controllers;

use App\Models\IndividualCertificateStudent;
use App\Models\Certificate;
use App\Models\DownloadLog;
use App\Models\Student;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDownloadCenterController extends Controller
{
    public function index(Request $request)
    {
        $downloadLogs = DownloadLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalDownloads = DownloadLog::count();
        $totalCertificates = DownloadLog::sum('certificates_count');
        $totalStudents = DownloadLog::distinct('student_dni')->count('student_dni');

        return view('admin.downloads.index', compact(
            'downloadLogs',
            'totalDownloads',
            'totalCertificates',
            'totalStudents'
        ));
    }

    public function searchStudentCertificates(Request $request)
    {
        $dni = trim($request->input('dni', ''));

        if (empty($dni)) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor ingrese un número de DNI o documento.'
            ], 422);
        }

        $fmtDate = function($date) {
            if (!$date) return '';
            $d = Carbon::parse($date);
            $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            return $d->day . ' de ' . $months[$d->month - 1] . ' del ' . $d->year;
        };

        $executive_signature = Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature  = Setting::where('key', 'individual_cert_academic_signature')->value('value');
        $executive_seal      = Setting::where('key', 'individual_cert_executive_seal')->value('value');
        $academic_seal       = Setting::where('key', 'individual_cert_academic_seal')->value('value');

        // 1. Certificados Individuales
        $individualStudents = IndividualCertificateStudent::where('dni', $dni)
            ->with(['certificate.instructor', 'certificate.category'])
            ->get();

        // 2. Certificados de Cursos
        $courseCertificates = Certificate::whereHas('student', function ($query) use ($dni) {
                $query->where('ci_nit', $dni);
            })
            ->with(['student', 'course.instructor', 'course.individualCertificate', 'course.courseCertificate'])
            ->get();

        // Búsqueda aproximada si no hay resultados exactos
        if ($individualStudents->isEmpty() && $courseCertificates->isEmpty()) {
            $individualStudents = IndividualCertificateStudent::where('dni', 'like', "%{$dni}%")
                ->with(['certificate.instructor', 'certificate.category'])
                ->get();

            $courseCertificates = Certificate::whereHas('student', function ($query) use ($dni) {
                    $query->where('ci_nit', 'like', "%{$dni}%");
                })
                ->with(['student', 'course.instructor', 'course.individualCertificate', 'course.courseCertificate'])
                ->get();
        }

        $studentName = 'Estudiante';
        if ($individualStudents->isNotEmpty()) {
            $studentName = $individualStudents->first()->fullname;
        } elseif ($courseCertificates->isNotEmpty()) {
            $studentName = $courseCertificates->first()->student->name ?? 'Estudiante';
        } else {
            $dbStudent = Student::where('ci_nit', $dni)->orWhere('ci_nit', 'like', "%{$dni}%")->first();
            if ($dbStudent) {
                $studentName = $dbStudent->name;
            }
        }

        $certificates = [];

        // Procesar certificados individuales
        foreach ($individualStudents as $item) {
            $cert = $item->certificate;
            if (!$cert) continue;

            $instName = $cert->instructor->certificate_name ?? $cert->instructor->name ?? '';
            $issueDate = $item->issue_date ?: ($item->end_date ?: $cert->end_date);
            $tpl = $cert->certificate_template ?? [];

            $realData = [
                'is_custom' => (bool)$item->is_custom,
                'student_name' => $item->fullname,
                'course_title' => $cert->title,
                'instructor_name' => $instName,
                'date' => $fmtDate($issueDate),
                'hours' => (string)($item->hours ?: $cert->hours),
                'start_date' => $fmtDate($item->start_date ?: $cert->start_date),
                'end_date' => $fmtDate($item->end_date ?: $cert->end_date),
                'registration_number' => (string)($item->registration_number ?: $cert->registration_number),
                'base_date' => $fmtDate($cert->end_date),
                'base_hours' => (string)$cert->hours,
                'base_start_date' => $fmtDate($cert->start_date),
                'base_end_date' => $fmtDate($cert->end_date),
                'base_registration_number' => (string)$cert->registration_number,
                'verification_url' => route('front.certificate.individual.view', ['uuid' => $item->uuid]),
                'instructor_signature' => !empty($cert->instructor->signature_url) ? asset($cert->instructor->signature_url) : null,
                'executive_signature' => !empty($executive_signature) ? asset($executive_signature) : null,
                'academic_signature' => !empty($academic_signature) ? asset($academic_signature) : null,
                'executive_seal' => !empty($executive_seal) ? asset($executive_seal) : null,
                'academic_seal' => !empty($academic_seal) ? asset($academic_seal) : null,
            ];

            $certificates[] = [
                'id' => 'ind_' . $item->id,
                'uuid' => $item->uuid,
                'type' => 'individual',
                'type_label' => 'Certificado Individual',
                'title' => $cert->title,
                'category' => $cert->category->name ?? 'General',
                'hours' => (string)($item->hours ?: $cert->hours),
                'date' => $fmtDate($issueDate),
                'registration_number' => $realData['registration_number'],
                'student_name' => $item->fullname,
                'student_dni' => $item->dni,
                'view_url' => route('front.certificate.individual.view', ['uuid' => $item->uuid]),
                'templateData' => $tpl,
                'realData' => $realData
            ];
        }

        // Procesar certificados de cursos
        foreach ($courseCertificates as $item) {
            $course = $item->course;
            if (!$course) continue;

            $instName = $course->instructor->certificate_name ?? $course->instructor->name ?? '';
            $courseMeta = $course->metadata ?? [];
            $tpl = $course->active_certificate_template ?? $course->certificate_template ?? [];

            $realData = [
                'is_custom' => false,
                'student_name' => $item->student->name ?? 'Estudiante',
                'course_title' => $course->title,
                'instructor_name' => $instName,
                'date' => $item->issued_at ? $fmtDate($item->issued_at) : '',
                'hours' => (string)($course->duration ?? ($courseMeta['hours'] ?? '')),
                'start_date' => isset($courseMeta['start_date']) ? $fmtDate($courseMeta['start_date']) : '',
                'end_date' => isset($courseMeta['end_date']) ? $fmtDate($courseMeta['end_date']) : '',
                'registration_number' => (string)($courseMeta['registration_number'] ?? $item->uuid),
                'verification_url' => route('certificate.verify', ['uuid' => $item->uuid]),
                'instructor_signature' => !empty($course->instructor->signature_url) ? asset($course->instructor->signature_url) : null,
                'executive_signature' => !empty($executive_signature) ? asset($executive_signature) : null,
                'academic_signature' => !empty($academic_signature) ? asset($academic_signature) : null,
                'executive_seal' => !empty($executive_seal) ? asset($executive_seal) : null,
                'academic_seal' => !empty($academic_seal) ? asset($academic_seal) : null,
            ];

            $certificates[] = [
                'id' => 'crs_' . $item->id,
                'uuid' => $item->uuid,
                'type' => 'course',
                'type_label' => $course->type === 'diploma' ? 'Diplomado' : 'Curso Regular',
                'title' => $course->title,
                'category' => 'Programa Académico',
                'hours' => $realData['hours'],
                'date' => $realData['date'],
                'registration_number' => $realData['registration_number'],
                'student_name' => $item->student->name ?? 'Estudiante',
                'student_dni' => $item->student->ci_nit ?? $dni,
                'view_url' => route('certificate.verify', ['uuid' => $item->uuid]),
                'templateData' => $tpl,
                'realData' => $realData
            ];
        }

        return response()->json([
            'success' => true,
            'student' => [
                'name' => $studentName,
                'dni' => $dni
            ],
            'count' => count($certificates),
            'certificates' => $certificates
        ]);
    }

    public function logDownload(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string',
            'student_dni' => 'required|string',
            'format' => 'required|in:zip,merged_pdf',
            'certificates_count' => 'required|integer|min:1',
            'certificates_details' => 'nullable|array'
        ]);

        $log = DownloadLog::create([
            'user_id' => auth()->id(),
            'student_name' => $request->student_name,
            'student_dni' => $request->student_dni,
            'format' => $request->format,
            'certificates_count' => $request->certificates_count,
            'certificates_details' => $request->certificates_details
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Descarga registrada correctamente.',
            'log_id' => $log->id
        ]);
    }
}
