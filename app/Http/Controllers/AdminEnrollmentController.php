<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\RegistrationToken; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminEnrollmentController extends Controller
{
    /**
     * Muestra la lista de inscripciones con soporte para búsqueda
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Enrollment::with(['student', 'course']);

        // LÓGICA DEL BUSCADOR
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function($cq) use ($search) {
                    $cq->where('title', 'like', "%{$search}%");
                });
            });
        }

        $enrollments = $query->orderBy('created_at', 'desc')
                             ->paginate(15)
                             ->withQueryString(); // Mantiene el parámetro 'search' en la paginación
        
        $courses = Course::where('status', 'published')->get();
        $students = Student::orderBy('name', 'asc')->get();
                                 
        return view('admin.enrollments.index', compact('enrollments', 'courses', 'students', 'search'));
    }

    /**
     * Inscribir un ESTUDIANTE NUEVO (Crea cuenta + Matrícula)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:8',
            'phone' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'receipt_file' => 'nullable|image|max:4096',
        ]);

        DB::beginTransaction();
        try {
            $student = new Student();
            $student->name = $request->name;
            $student->email = $request->email;
            $student->password = Hash::make($request->password);
            $student->phone = $request->phone;
            $student->country = 'Bolivia';
            $student->status = 'active';
            $student->username = Str::slug($request->name) . rand(100, 999);
            $student->avatar_url = 'https://ui-avatars.com/api/?name='.urlencode($request->name).'&background=213A57&color=fff';
            $student->user_id = auth()->id();
            $student->save();

            $course = Course::findOrFail($request->course_id);
            $enrollment = new Enrollment();
            $enrollment->student_id = $student->id;
            $enrollment->course_id = $course->id;
            $enrollment->price_paid = $course->price;
            $enrollment->enrolled_at = now();
            $enrollment->status = 'approved'; 
            $enrollment->progress_percent = 0;
            $enrollment->user_id = auth()->id();

            if ($request->hasFile('receipt_file')) {
                $path = $request->file('receipt_file')->store('receipts', 'public');
                $enrollment->receipt_url = 'storage/' . $path;
            }

            $enrollment->save();
            DB::commit();

            return redirect()->back()
                ->with('success', 'Estudiante nuevo registrado e inscrito con éxito.')
                ->with('credentials_modal', [
                    'name' => $student->name,
                    'email' => $student->email,
                    'password' => $request->password,
                    'course' => $course->title
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en store enrollment: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al inscribir: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Inscribir un ESTUDIANTE ANTIGUO
     */
    public function storeExisting(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'receipt_file' => 'nullable|image|max:4096'
        ]);

        $exists = Enrollment::where('student_id', $request->student_id)
                            ->where('course_id', $request->course_id)
                            ->exists();

        if ($exists) {
            return back()->with('error', 'Este estudiante ya se encuentra inscrito en este curso.');
        }

        try {
            $course = Course::findOrFail($request->course_id);
            $enrollment = new Enrollment();
            $enrollment->student_id = $request->student_id;
            $enrollment->course_id = $course->id;
            $enrollment->price_paid = $course->price;
            $enrollment->enrolled_at = now();
            $enrollment->status = 'approved';
            $enrollment->user_id = auth()->id();
            
            if ($request->hasFile('receipt_file')) {
                $path = $request->file('receipt_file')->store('receipts', 'public');
                $enrollment->receipt_url = 'storage/' . $path;
            }
            $enrollment->save();

            return redirect()->back()->with('success', 'Estudiante antiguo matriculado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al procesar la matrícula.');
        }
    }

    public function approve(Request $request, $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            if ($enrollment->student && $enrollment->student->status === 'inactive') {
                $enrollment->student->update(['status' => 'active']);
            }
            $enrollment->status = 'approved';
            $enrollment->save();
            return redirect()->back()->with('success', 'Acceso concedido correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al aprobar la inscripción.');
        }
    }

    public function destroy($id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            if ($enrollment->receipt_url) {
                $path = str_replace('storage/', '', $enrollment->receipt_url);
                Storage::disk('public')->delete($path);
            }
            $enrollment->delete();
            return redirect()->back()->with('success', 'Inscripción eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar.');
        }
    }

    /**
     * Genera el token único para el link de registro
     */
    public function generateRegistrationToken(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        try {
            $tokenString = Str::random(32);
            RegistrationToken::create([
                'token' => $tokenString,
                'course_id' => $request->course_id,
                'type' => 'new',
                'is_used' => false,
                'expires_at' => now()->addDays(7)
            ]);

            return response()->json([
                'url' => route('enrollment.guest.form', ['token' => $tokenString])
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}