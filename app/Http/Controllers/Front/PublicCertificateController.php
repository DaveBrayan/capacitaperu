<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\IndividualCertificateStudent;
use App\Models\Certificate;
use Illuminate\Http\Request;

class PublicCertificateController extends Controller
{
    public function individualIndex(Request $request)
    {
        $dni = $request->query('dni');
        $certificates = collect();
        $studentName = '';

        if ($request->filled('dni')) {
            // 1. Certificados Individuales
            $individualResults = IndividualCertificateStudent::where('dni', $dni)
                ->with(['certificate.instructor', 'certificate.category'])
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'uuid' => $item->uuid,
                        'title' => $item->certificate->title,
                        'category' => $item->certificate->category->name ?? 'Certificación Individual',
                        'hours' => $item->is_custom && !empty($item->hours) ? $item->hours : $item->certificate->hours,
                        'student_name' => $item->fullname,
                        'view_route' => route('front.certificate.individual.view', $item->uuid)
                    ];
                });

            $certificates = $certificates->concat($individualResults);

            // 2. Certificados de Cursos
            $courseResults = Certificate::whereHas('student', function ($query) use ($dni) {
                $query->where('ci_nit', $dni);
            })
                ->with(['course', 'student'])
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'uuid' => $item->uuid,
                        'title' => $item->course->title,
                        'category' => 'Curso Regular',
                        'hours' => $item->course->duration ?: ($item->course->certificate_template['hours'] ?? ''),
                        'student_name' => $item->student->name,
                        'view_route' => route('certificate.verify', $item->uuid)
                    ];
                });

            $certificates = $certificates->concat($courseResults);

            if ($certificates->isNotEmpty()) {
                $studentName = $certificates->first()->student_name;
            }
        }

        return view('front.individual-certificate-search', compact('certificates', 'dni', 'studentName'));
    }

    public function showPublic($uuid)
    {
        // Buscamos por UUID (que es lo que el estudiante tiene o busca)
        $certificate = IndividualCertificateStudent::where('uuid', $uuid)
            ->with(['certificate.instructor', 'certificate.category'])
            ->firstOrFail();

        // Traemos las firmas globales
        $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');

        return view('front.certificate-view', [
            'certificate' => $certificate,
            'type' => 'individual',
            'executive_signature' => $executive_signature,
            'academic_signature' => $academic_signature
        ]);
    }
}
