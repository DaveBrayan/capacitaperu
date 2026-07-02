<?php

namespace App\Http\Controllers;

use App\Models\IndividualCertificate;
use App\Models\Instructor;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminIndividualCertificateController extends Controller
{
    public function index()
    {
        $certificates = IndividualCertificate::with(['instructor', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.individual-certificates.index', compact('certificates'));
    }

    public function create()
    {
        $instructors = Instructor::where('status', 'active')->get();
        $categories = Category::where('status', true)->get();
        $courses = \App\Models\Course::where('status', 'published')->get();
        $certificate = new IndividualCertificate();
        $linkedCourseId = null;
        return view('admin.individual-certificates.create', compact('instructors', 'categories', 'courses', 'certificate', 'linkedCourseId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'registration_number' => 'nullable|string',
            'hours' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $certificate = new IndividualCertificate();
            $certificate->title = $request->title;
            $certificate->slug = Str::slug($request->title) . '-' . uniqid();
            $certificate->registration_number = $request->registration_number;
            $certificate->hours = $request->hours;
            $certificate->instructor_id = $request->instructor_id;
            $certificate->category_id = $request->category_id;
            $certificate->start_date = $request->start_date;
            $certificate->end_date = $request->end_date;
            $certificate->status = $request->input('status', 'published');
            $certificate->user_id = auth()->id();

            if ($request->filled('certificate_data')) {
                $certificate->certificate_template = json_decode($request->certificate_data, true);
            }

            $certificate->save();

            // Link to course
            if ($request->filled('course_id')) {
                \App\Models\Course::where('individual_certificate_id', $certificate->id)->update(['individual_certificate_id' => null]);
                $course = \App\Models\Course::find($request->course_id);
                if ($course) {
                    $course->individual_certificate_id = $certificate->id;
                    $course->save();
                }
            }

            if ($request->filled('students_json')) {
                $students = json_decode($request->students_json, true);
                if (is_array($students)) {
                    // --- CALCULATE REGISTRATION NUMBERS AND SET CUSTOM FIELDS IN PHP ---
                    $baseReg = trim($certificate->registration_number ?? '');
                    $baseNum = 0;
                    $prefix = $baseReg;
                    $suffix = '';

                    if (preg_match('/^(.*?)(\d+)([^\d]*)$/', $baseReg, $matches)) {
                        $prefix = $matches[1];
                        $baseNum = (int)$matches[2];
                        $suffix = $matches[3];
                    }

                    $standardCount = 0;
                    $customDateGroups = [];
                    $groupCount = 0;

                    foreach ($students as $student) {
                        $isCustom = !empty($student['is_custom']);
                        
                        $studentStartDate = null;
                        if (!empty($student['start_date']) && $isCustom) {
                            $studentStartDate = is_string($student['start_date']) ? explode('T', $student['start_date'])[0] : null;
                        }

                        $studentEndDate = null;
                        if (!empty($student['end_date']) && $isCustom) {
                            $studentEndDate = is_string($student['end_date']) ? explode('T', $student['end_date'])[0] : null;
                        }

                        $studentHours = (!empty($student['hours']) && $isCustom) ? (int)$student['hours'] : null;

                        $studentIssueDate = null;
                        if (!empty($student['issue_date']) && $isCustom) {
                            $studentIssueDate = is_string($student['issue_date']) ? explode('T', $student['issue_date'])[0] : null;
                        }

                        if (!$isCustom) {
                            $standardCount++;
                            $computedNum = $baseNum + $standardCount;
                            $regNum = $prefix . $computedNum . $suffix;
                            
                            $certificate->students()->create([
                                'fullname' => $student['fullname'],
                                'dni' => $student['dni'],
                                'is_custom' => false,
                                'start_date' => null,
                                'end_date' => null,
                                'hours' => null,
                                'issue_date' => null,
                                'registration_number' => $regNum,
                                'user_id' => auth()->id()
                            ]);
                        } else {
                            $issueDateKey = $studentIssueDate ?: ($studentEndDate ?: '');
                            if (empty($issueDateKey)) {
                                $issueDateKey = $certificate->end_date ? (is_string($certificate->end_date) ? $certificate->end_date : $certificate->end_date->format('Y-m-d')) : 'default';
                            }
                            
                            if (!isset($customDateGroups[$issueDateKey])) {
                                $groupCount++;
                                $customDateGroups[$issueDateKey] = [
                                    'group_index' => $groupCount,
                                    'student_count' => 0
                                ];
                            }
                            
                            $customDateGroups[$issueDateKey]['student_count']++;
                            $groupIndex = $customDateGroups[$issueDateKey]['group_index'];
                            $studentInGroupIndex = $customDateGroups[$issueDateKey]['student_count'];
                            
                            $computedNum = $baseNum + ($groupIndex * 100) + $studentInGroupIndex;
                            $regNum = $prefix . $computedNum . $suffix;

                            $certificate->students()->create([
                                'fullname' => $student['fullname'],
                                'dni' => $student['dni'],
                                'is_custom' => true,
                                'start_date' => $studentStartDate,
                                'end_date' => $studentEndDate,
                                'hours' => $studentHours,
                                'issue_date' => $studentIssueDate,
                                'registration_number' => $regNum,
                                'user_id' => auth()->id()
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            $targetTab = $request->input('active_tab', 'info');
            return redirect()->to(route('admin.individual-certificates.edit', $certificate->id) . '#' . $targetTab)
                             ->with('success', 'Certificado individual creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store individual certificate: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $certificate = IndividualCertificate::with(['students.user'])->findOrFail($id);
        $instructors = Instructor::where('status', 'active')->get();
        $categories = Category::where('status', true)->get();
        $courses = \App\Models\Course::where('status', 'published')->get();
        $linkedCourseId = \App\Models\Course::where('individual_certificate_id', $certificate->id)->value('id');

        $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');

        return view('admin.individual-certificates.create', compact('certificate', 'instructors', 'categories', 'courses', 'linkedCourseId', 'executive_signature', 'academic_signature'));
    }

    public function update(Request $request, $id)
    {
        $certificate = IndividualCertificate::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
        ]);

        DB::beginTransaction();
        try {
            $certificate->title = $request->title;
            $certificate->registration_number = $request->registration_number;
            $certificate->hours = $request->hours;
            $certificate->instructor_id = $request->instructor_id;
            $certificate->category_id = $request->category_id;
            $certificate->start_date = $request->start_date;
            $certificate->end_date = $request->end_date;
            $certificate->status = $request->input('status', 'published');

            if ($request->filled('certificate_data')) {
                $certificate->certificate_template = json_decode($request->certificate_data, true);
            }

            $certificate->save();

            // Link to course
            \App\Models\Course::where('individual_certificate_id', $certificate->id)->update(['individual_certificate_id' => null]);
            if ($request->filled('course_id')) {
                $course = \App\Models\Course::find($request->course_id);
                if ($course) {
                    $course->individual_certificate_id = $certificate->id;
                    $course->save();
                }
            }

            if ($request->filled('students_json')) {
                $students = json_decode($request->students_json, true);
                if (is_array($students)) {
                    $existingIds = collect($students)->pluck('id')->filter()->toArray();
                    
                    // 1. Eliminar estudiantes que ya no están en la lista enviada
                    $certificate->students()->whereNotIn('id', $existingIds)->delete();

                    // --- CALCULATE REGISTRATION NUMBERS AND SET CUSTOM FIELDS IN PHP ---
                    $baseReg = trim($certificate->registration_number ?? '');
                    $baseNum = 0;
                    $prefix = $baseReg;
                    $suffix = '';

                    if (preg_match('/^(.*?)(\d+)([^\d]*)$/', $baseReg, $matches)) {
                        $prefix = $matches[1];
                        $baseNum = (int)$matches[2];
                        $suffix = $matches[3];
                    }

                    $standardCount = 0;
                    $customDateGroups = [];
                    $groupCount = 0;

                    // 2. Actualizar existentes y crear nuevos
                    foreach ($students as $student) {
                        $isCustom = !empty($student['is_custom']);
                        
                        $studentStartDate = null;
                        if (!empty($student['start_date']) && $isCustom) {
                            $studentStartDate = is_string($student['start_date']) ? explode('T', $student['start_date'])[0] : null;
                        }

                        $studentEndDate = null;
                        if (!empty($student['end_date']) && $isCustom) {
                            $studentEndDate = is_string($student['end_date']) ? explode('T', $student['end_date'])[0] : null;
                        }

                        $studentHours = (!empty($student['hours']) && $isCustom) ? (int)$student['hours'] : null;

                        $studentIssueDate = null;
                        if (!empty($student['issue_date']) && $isCustom) {
                            $studentIssueDate = is_string($student['issue_date']) ? explode('T', $student['issue_date'])[0] : null;
                        }

                        if (!$isCustom) {
                            $standardCount++;
                            $computedNum = $baseNum + $standardCount;
                            $regNum = $prefix . $computedNum . $suffix;
                            
                            $data = [
                                'fullname' => $student['fullname'],
                                'dni' => $student['dni'],
                                'is_custom' => false,
                                'start_date' => null,
                                'end_date' => null,
                                'hours' => null,
                                'issue_date' => null,
                                'registration_number' => $regNum
                            ];
                        } else {
                            $issueDateKey = $studentIssueDate ?: ($studentEndDate ?: '');
                            if (empty($issueDateKey)) {
                                $issueDateKey = $certificate->end_date ? (is_string($certificate->end_date) ? $certificate->end_date : $certificate->end_date->format('Y-m-d')) : 'default';
                            }
                            
                            if (!isset($customDateGroups[$issueDateKey])) {
                                $groupCount++;
                                $customDateGroups[$issueDateKey] = [
                                    'group_index' => $groupCount,
                                    'student_count' => 0
                                ];
                            }
                            
                            $customDateGroups[$issueDateKey]['student_count']++;
                            $groupIndex = $customDateGroups[$issueDateKey]['group_index'];
                            $studentInGroupIndex = $customDateGroups[$issueDateKey]['student_count'];
                            
                            $computedNum = $baseNum + ($groupIndex * 100) + $studentInGroupIndex;
                            $regNum = $prefix . $computedNum . $suffix;

                            $data = [
                                'fullname' => $student['fullname'],
                                'dni' => $student['dni'],
                                'is_custom' => true,
                                'start_date' => $studentStartDate,
                                'end_date' => $studentEndDate,
                                'hours' => $studentHours,
                                'issue_date' => $studentIssueDate,
                                'registration_number' => $regNum
                            ];
                        }

                        $data['user_id'] = auth()->id();

                        if (!empty($student['id'])) {
                            $studentModel = \App\Models\IndividualCertificateStudent::find($student['id']);
                            if ($studentModel) {
                                $studentModel->update($data);
                            }
                        } else {
                            $certificate->students()->create($data);
                        }
                    }
                }
            }

            DB::commit();
            $targetTab = $request->input('active_tab', 'info');
            return redirect()->to(route('admin.individual-certificates.edit', $certificate->id) . '#' . $targetTab)
                             ->with('success', 'Certificado actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update individual certificate: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getSignatures()
    {
        $executive = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');

        return response()->json([
            'executive' => $executive ? asset($executive) : null,
            'academic' => $academic ? asset($academic) : null,
        ]);
    }

    public function updateSignatures(Request $request)
    {
        $request->validate([
            'executive_signature' => 'nullable|image|max:2048',
            'academic_signature' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('executive_signature')) {
            $path = $request->file('executive_signature')->store('signatures', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'individual_cert_executive_signature'],
                ['value' => 'storage/' . $path]
            );
        }

        if ($request->hasFile('academic_signature')) {
            $path = $request->file('academic_signature')->store('signatures', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'individual_cert_academic_signature'],
                ['value' => 'storage/' . $path]
            );
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, $id)
    {
        $certificate = IndividualCertificate::findOrFail($id);
        
        // Delete cover
        if ($certificate->cover_image) {
            $oldPath = str_replace('storage/', '', $certificate->cover_image);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
        
        // Unlink from course first
        \App\Models\Course::where('individual_certificate_id', $certificate->id)->update(['individual_certificate_id' => null]);
        
        $certificate->delete();
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Certificado eliminado.']);
        }
        
        return redirect()->route('admin.individual-certificates.index')->with('success', 'Certificado eliminado.');
    }

    public function getStudents($id)
    {
        $certificate = IndividualCertificate::findOrFail($id);
        $students = $certificate->students()->with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($students);
    }

    public function addStudent(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
        ]);

        $certificate = IndividualCertificate::findOrFail($id);
        $student = $certificate->students()->create([
            'fullname' => $request->fullname,
            'dni' => $request->dni,
        ]);

        return response()->json($student);
    }

    public function removeStudent($studentId)
    {
        $student = \App\Models\IndividualCertificateStudent::findOrFail($studentId);
        $student->delete();
        return response()->json(['success' => true]);
    }

    public function showPublic($slug)
    {
        $certificate = IndividualCertificate::where('slug', $slug)
            ->with(['instructor', 'category'])
            ->firstOrFail();

        $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');
            
        return view('front.certificate-view', [
            'certificate' => $certificate,
            'type' => 'individual_preview', // Tipo especial para preview de certificado base
            'executive_signature' => $executive_signature,
            'academic_signature' => $academic_signature
        ]);
    }
}
