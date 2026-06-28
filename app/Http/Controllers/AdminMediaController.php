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
        // Obtener todos los archivos del disco 'public' en la carpeta 'media'
        $files = Storage::disk('public')->files('media');
        
        // Filtrar y mapear a URLs completas
        $assets = collect($files)
            ->filter(function ($file) {
                // Solo imágenes
                return preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $file);
            })
            ->map(function ($file) {
                // Generar URL pública directa (ej: /storage/media/archivo.jpg)
                return asset('storage/' . $file);
            })
            ->values(); // Reindexar array

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
            
            // Devolver la URL pública absoluta
            return response()->json([
                'url' => asset('storage/' . $path),
                'success' => true
            ]);
        }

        return response()->json(['error' => 'Archivo no válido'], 400);
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

        if ($inCourse || $inIndividual) {
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