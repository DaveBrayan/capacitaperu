<?php

namespace App\Http\Controllers;

use App\Models\CourseCertificate;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminCourseCertificateController extends Controller
{
    public function index()
    {
        $certificates = CourseCertificate::with(['instructor', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.course-certificates.index', compact('certificates'));
    }

    public function create(Request $request)
    {
        $selectedCourseId = $request->query('course_id');
        $selectedIndividualId = $request->query('individual_certificate_id');
        $courses = Course::orderBy('title')->get();
        $instructors = Instructor::where('status', 'active')->get();
        $certificate = new CourseCertificate();
        
        if ($selectedCourseId) {
            $certificate->course_id = $selectedCourseId;
            $course = Course::find($selectedCourseId);
            if ($course) {
                $certificate->title = $course->title;
                $certificate->instructor_id = $course->instructor_id;
                $certificate->hours = $course->duration;
                
                // Prefill dates if they exist in course metadata
                if (!empty($course->metadata['start_date'])) {
                    $certificate->start_date = $course->metadata['start_date'];
                }
                if (!empty($course->metadata['end_date'])) {
                    $certificate->end_date = $course->metadata['end_date'];
                }
            }
        } elseif ($selectedIndividualId) {
            $certificate->individual_certificate_id = $selectedIndividualId;
            $indCert = \App\Models\IndividualCertificate::find($selectedIndividualId);
            if ($indCert) {
                $certificate->title = $indCert->title;
                $certificate->instructor_id = $indCert->instructor_id;
                $certificate->hours = $indCert->hours;
                $certificate->start_date = $indCert->start_date;
                $certificate->end_date = $indCert->end_date;
            }
        }
        
        return view('admin.course-certificates.create', compact('courses', 'instructors', 'certificate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'course_id' => 'nullable|exists:courses,id',
            'individual_certificate_id' => 'nullable|exists:individual_certificates,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'registration_number' => 'nullable|string',
            'hours' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $certificate = new CourseCertificate();
            $certificate->title = $request->title;
            $certificate->course_id = $request->course_id;
            $certificate->individual_certificate_id = $request->individual_certificate_id;
            $certificate->instructor_id = $request->instructor_id;
            $certificate->registration_number = $request->registration_number;
            $certificate->hours = $request->hours;
            $certificate->start_date = $request->start_date;
            $certificate->end_date = $request->end_date;
            $certificate->status = $request->input('status', 'published');
            $certificate->user_id = auth()->id();

            if ($request->filled('certificate_data')) {
                $certificate->certificate_template = json_decode($request->certificate_data, true);
            }

            $certificate->save();

            // Link this certificate back to the course if course_id is set
            if ($request->filled('course_id')) {
                $course = Course::findOrFail($request->course_id);
                $course->course_certificate_id = $certificate->id;
                $course->save();
            }

            DB::commit();
            $targetTab = $request->input('active_tab', 'info');
            return redirect()->to(route('admin.course-certificates.edit', $certificate->id) . '#' . $targetTab)
                             ->with('success', 'Certificado de curso creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store course certificate: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $certificate = CourseCertificate::findOrFail($id);
        $courses = Course::orderBy('title')->get();
        $instructors = Instructor::where('status', 'active')->get();

        $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');

        return view('admin.course-certificates.create', compact('certificate', 'courses', 'instructors', 'executive_signature', 'academic_signature'));
    }

    public function update(Request $request, $id)
    {
        $certificate = CourseCertificate::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'course_id' => 'nullable|exists:courses,id',
            'individual_certificate_id' => 'nullable|exists:individual_certificates,id',
            'instructor_id' => 'nullable|exists:instructors,id',
        ]);

        DB::beginTransaction();
        try {
            // Unlink old course if it changed
            if ($certificate->course_id != $request->course_id) {
                $oldCourse = Course::find($certificate->course_id);
                if ($oldCourse && $oldCourse->course_certificate_id == $certificate->id) {
                    $oldCourse->course_certificate_id = null;
                    $oldCourse->save();
                }
            }

            $certificate->title = $request->title;
            $certificate->course_id = $request->course_id;
            $certificate->individual_certificate_id = $request->individual_certificate_id;
            $certificate->instructor_id = $request->instructor_id;
            $certificate->registration_number = $request->registration_number;
            $certificate->hours = $request->hours;
            $certificate->start_date = $request->start_date;
            $certificate->end_date = $request->end_date;
            $certificate->status = $request->input('status', 'published');

            if ($request->filled('certificate_data')) {
                $certificate->certificate_template = json_decode($request->certificate_data, true);
            }

            $certificate->save();

            // Link to the new course
            $course = Course::findOrFail($request->course_id);
            $course->course_certificate_id = $certificate->id;
            $course->save();

            DB::commit();
            $targetTab = $request->input('active_tab', 'info');
            return redirect()->to(route('admin.course-certificates.edit', $certificate->id) . '#' . $targetTab)
                             ->with('success', 'Certificado actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update course certificate: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $certificate = CourseCertificate::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Unlink from course first
            $course = Course::find($certificate->course_id);
            if ($course && $course->course_certificate_id == $certificate->id) {
                $course->course_certificate_id = null;
                $course->save();
            }

            $certificate->delete();
            DB::commit();
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Certificado eliminado.']);
            }
            
            return redirect()->route('admin.course-certificates.index')->with('success', 'Certificado eliminado.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting course certificate: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error al eliminar el certificado.']);
            }
            
            return redirect()->route('admin.course-certificates.index')->with('error', 'Error al eliminar el certificado.');
        }
    }

    public function viewTeacherCertificate($uuid)
    {
        $certificate = CourseCertificate::where('uuid', $uuid)
            ->with(['instructor', 'course'])
            ->firstOrFail();

        $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature  = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');

        return view('front.teacher-certificate-view', compact('certificate', 'executive_signature', 'academic_signature'));
    }
}
