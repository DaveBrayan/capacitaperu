<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminMediaController extends Controller
{
    /**
     * Lista todas las imágenes de la carpeta media para la galería.
     */
    public function list()
    {
        $disk = Storage::disk('public');
        $files = $disk->files('media');
        
        $assets = collect($files)
            ->filter(function ($file) {
                // Solo imágenes
                return preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $file);
            })
            ->map(function ($file) use ($disk) {
                return [
                    'url' => asset('storage/' . $file),
                    'name' => basename($file),
                    'size' => $disk->exists($file) ? $disk->size($file) : 0,
                    'last_modified' => $disk->exists($file) ? $disk->lastModified($file) : 0,
                ];
            })
            ->values();

        return response()->json($assets);
    }

    /**
     * Sube una nueva imagen.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048', // Máx 2MB
        ]);

        if ($request->file('file')->isValid()) {
            // Guardar en storage/app/public/media
            $path = $request->file('file')->store('media', 'public');
            $disk = Storage::disk('public');
            
            // Devolver metadatos completos y la URL pública absoluta
            return response()->json([
                'url' => asset('storage/' . $path),
                'name' => basename($path),
                'size' => $disk->exists($path) ? $disk->size($path) : 0,
                'last_modified' => $disk->exists($path) ? $disk->lastModified($path) : time(),
                'success' => true
            ]);
        }

        return response()->json(['error' => 'Archivo no válido'], 400);
    }

    /**
     * Renombra una imagen en el disco y actualiza referencias en BD si aplica.
     */
    public function rename(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
            'new_name' => 'required|string|max:150',
        ]);

        $url = $request->input('url');
        $rawNewName = trim($request->input('new_name'));

        // Convertir URL a path relativo
        $oldPath = str_replace(asset('storage/'), '', $url);
        $oldPath = ltrim($oldPath, '/');

        if (!Storage::disk('public')->exists($oldPath)) {
            return response()->json(['error' => 'El archivo no fue encontrado en el servidor.'], 404);
        }

        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
        $baseName = pathinfo($rawNewName, PATHINFO_FILENAME);
        $slugName = Str::slug($baseName, '_');
        if (empty($slugName)) {
            $slugName = 'imagen_' . time();
        }

        $newFileName = $slugName . '.' . strtolower($extension);
        $newPath = 'media/' . $newFileName;

        // Evitar sobreescritura si ya existe otro archivo con ese nombre
        $counter = 1;
        while (Storage::disk('public')->exists($newPath) && $newPath !== $oldPath) {
            $newFileName = $slugName . '_' . $counter . '.' . strtolower($extension);
            $newPath = 'media/' . $newFileName;
            $counter++;
        }

        if ($newPath !== $oldPath) {
            Storage::disk('public')->move($oldPath, $newPath);

            // Actualizar referencias si está en plantillas de certificados
            $oldRef = $oldPath;
            $newRef = $newPath;

            \App\Models\Course::where('certificate_template', 'like', '%' . $oldRef . '%')
                ->each(function ($course) use ($oldRef, $newRef) {
                    $json = json_encode($course->certificate_template);
                    $json = str_replace($oldRef, $newRef, $json);
                    $course->certificate_template = json_decode($json, true);
                    $course->save();
                });

            \App\Models\IndividualCertificate::where('certificate_template', 'like', '%' . $oldRef . '%')
                ->each(function ($cert) use ($oldRef, $newRef) {
                    $json = json_encode($cert->certificate_template);
                    $json = str_replace($oldRef, $newRef, $json);
                    $cert->certificate_template = json_decode($json, true);
                    $cert->save();
                });

            \App\Models\CourseCertificate::where('certificate_template', 'like', '%' . $oldRef . '%')
                ->each(function ($cert) use ($oldRef, $newRef) {
                    $json = json_encode($cert->certificate_template);
                    $json = str_replace($oldRef, $newRef, $json);
                    $cert->certificate_template = json_decode($json, true);
                    $cert->save();
                });
        }

        $disk = Storage::disk('public');
        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $newPath),
            'name' => $newFileName,
            'size' => $disk->exists($newPath) ? $disk->size($newPath) : 0,
            'last_modified' => $disk->exists($newPath) ? $disk->lastModified($newPath) : time(),
        ]);
    }

    /**
     * Elimina una imagen.
     */
    public function delete(Request $request)
    {
        $url = $request->input('url');
        
        if (!$url) {
            return response()->json(['error' => 'URL no proporcionada'], 400);
        }

        // Convertir URL absoluta a path relativo del storage
        // Ej: http://dominio.com/storage/media/foto.jpg -> media/foto.jpg
        $path = str_replace(asset('storage/'), '', $url);
        
        // Limpieza extra por si acaso la URL venía diferente
        $path = ltrim($path, '/');

        // Verificar si la imagen está en uso en las plantillas guardadas en la BD
        $inCourse = \App\Models\Course::where('certificate_template', 'like', '%' . $path . '%')->exists();
        $inIndividual = \App\Models\IndividualCertificate::where('certificate_template', 'like', '%' . $path . '%')->exists();
        $inCourseCert = \App\Models\CourseCertificate::where('certificate_template', 'like', '%' . $path . '%')->exists();

        if ($inCourse || $inIndividual || $inCourseCert) {
            return response()->json([
                'success' => false,
                'used' => true,
                'error' => 'Esta imagen no se puede eliminar porque se está usando.'
            ]);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Archivo no encontrado o ya eliminado'], 404);
    }

    /**
     * Método opcional show por si alguna ruta antigua lo llama,
     * aunque ya no se usa para la galería.
     */
    public function show($filename)
    {
        $path = storage_path('app/public/media/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file, 200)->header("Content-Type", $type);
    }
}