<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class AdminInstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::withCount('courses')->orderBy('created_at', 'desc')->paginate(9);
        return view('admin.instructors.index', compact('instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:instructors,email',
            'password' => 'required|min:8',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'certificate_name' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $instructor = new Instructor();
        $instructor->name = $request->name;
        $instructor->email = $request->email;
        $instructor->password = Hash::make($request->password);
        $instructor->specialty = $request->specialty;
        $instructor->phone = $request->phone;
        $instructor->bio = $request->bio;
        $instructor->status = $request->status ?? 'active';
        $instructor->certificate_name = $request->certificate_name;
        $instructor->user_id = auth()->id();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('instructors/avatars', 'public');
            $instructor->avatar_url = 'storage/' . $path;
        }

        if ($request->hasFile('signature')) {
            // Guardamos en 'public' pero accederemos via ruta especial
            $path = $request->file('signature')->store('instructors/signatures', 'public');
            $instructor->signature_url = 'storage/' . $path;
        }

        $instructor->save();

        return redirect()->back()->with('success', 'Docente creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $instructor = Instructor::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:instructors,email,' . $id,
            'password' => 'nullable|min:8',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'certificate_name' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $instructor->name = $request->name;
        $instructor->email = $request->email;
        $instructor->specialty = $request->specialty;
        $instructor->phone = $request->phone;
        $instructor->bio = $request->bio;
        $instructor->status = $request->status;
        $instructor->certificate_name = $request->certificate_name;

        if ($request->filled('password')) {
            $instructor->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($instructor->avatar_url && !str_starts_with($instructor->avatar_url, 'http')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $instructor->avatar_url));
            }
            $path = $request->file('avatar')->store('instructors/avatars', 'public');
            $instructor->avatar_url = 'storage/' . $path;
        }

        if ($request->hasFile('signature')) {
            if ($instructor->signature_url && !str_starts_with($instructor->signature_url, 'http')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $instructor->signature_url));
            }
            $path = $request->file('signature')->store('instructors/signatures', 'public');
            $instructor->signature_url = 'storage/' . $path;
        }

        $instructor->save();

        return redirect()->back()->with('success', 'Docente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);
        
        if ($instructor->courses()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar porque tiene cursos asignados.');
        }

        if ($instructor->avatar_url && !str_starts_with($instructor->avatar_url, 'http')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $instructor->avatar_url));
        }
        if ($instructor->signature_url && !str_starts_with($instructor->signature_url, 'http')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $instructor->signature_url));
        }

        $instructor->delete();
        return redirect()->back()->with('success', 'Docente eliminado.');
    }

    /**
     * NUEVO MÉTODO: Servir la imagen de la firma directamente
     * Evita el error 403 Forbidden al pasar por PHP
     */
    public function getSignature($filename)
    {
        // Ruta física real en el disco
        $path = storage_path('app/public/instructors/signatures/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}