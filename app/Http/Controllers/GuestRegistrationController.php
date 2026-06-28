<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\RegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GuestRegistrationController extends Controller
{
    /**
     * Muestra el formulario de registro validando que el token sea útil
     */
    public function showForm(Request $request, $token)
    {
        // 1. Validar el token en la base de datos y que no haya sido usado
        $registrationToken = RegistrationToken::where('token', $token)
            ->where('is_used', false)
            ->first();

        // Si no existe, fue usado o expiró, error 404
        if (!$registrationToken) {
            abort(404, 'Este enlace de invitación ya no es válido, ha expirado o ya fue utilizado.');
        }

        $course = $registrationToken->course;
        $type = $registrationToken->type;

        return view('front.guest-registration', compact('course', 'type', 'token'));
    }

    /**
     * Procesa el registro y marca el token como "destruido" (usado)
     */
    public function processRegistration(Request $request, $token)
    {
        // Validar token nuevamente para evitar doble envío
        $registrationToken = RegistrationToken::where('token', $token)
            ->where('is_used', false)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'receipt_file' => 'required|image|max:4096',
        ]);

        DB::beginTransaction();
        try {
            $course = $registrationToken->course;
            
            // Generar contraseña temporal legible para el estudiante
            $tempPassword = 'CP' . rand(100000, 999999);

            // 1. Crear estudiante
            $student = new Student();
            $student->name = $request->name;
            $student->email = $request->email;
            $student->password = Hash::make($tempPassword);
            $student->status = 'active';
            $student->force_pass_reset = true; // Forzar cambio de contraseña en su primer login
            $student->username = Str::slug($request->name) . rand(100, 999);
            $student->avatar_url = 'https://ui-avatars.com/api/?name='.urlencode($request->name).'&background=213A57&color=fff';
            $student->save();

            // 2. Crear Inscripción Pendiente
            $enrollment = new Enrollment();
            $enrollment->student_id = $student->id;
            $enrollment->course_id = $course->id;
            $enrollment->price_paid = $course->price;
            $enrollment->status = 'pending';
            
            if ($request->hasFile('receipt_file')) {
                $path = $request->file('receipt_file')->store('receipts', 'public');
                $enrollment->receipt_url = 'storage/' . $path;
            }
            $enrollment->save();

            // 3. DESTRUIR EL LINK (Marcar como usado)
            $registrationToken->update(['is_used' => true]);

            DB::commit();

            return redirect()->route('enrollment.guest.success')->with('registration_data', [
                'name' => $student->name,
                'email' => $student->email,
                'password' => $tempPassword,
                'course' => $course->title
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el registro: ' . $e->getMessage())->withInput();
        }
    }

    public function showSuccess()
    {
        $data = session('registration_data');
        if (!$data) return redirect()->route('login');
        return view('front.registration-success', compact('data'));
    }
}