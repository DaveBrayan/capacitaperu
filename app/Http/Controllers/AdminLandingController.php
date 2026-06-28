<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminLandingController extends Controller
{
    /**
     * Muestra la vista del editor de la Landing Page.
     */
    public function edit()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.landing.edit', compact('settings'));
    }

    /**
     * Guarda los cambios del contenido de la Landing Page.
     */
    public function update(Request $request)
    {
        // 1. Obtener campos de texto excluyendo los archivos cargados
        $fileKeys = [
            'app_logo',
            'app_logo_white',
            'home_hero_bg',
            'home_hero_right_img',
            'home_about_img',
            'about_hero_bg',
            'about_hist_img',
            'about_eq_m1_img',
            'about_eq_m2_img',
            'about_eq_m3_img',
            'about_eq_m4_img',
            'contact_hero_bg'
        ];

        $textFields = $request->except(array_merge(['_token', '_method'], $fileKeys));

        // Obtener todos los settings existentes indexados por clave para optimizar y evitar SELECTs innecesarios
        $settingsModels = Setting::all()->keyBy('key');

        \Illuminate\Support\Facades\DB::transaction(function () use ($textFields, $settingsModels, $request, $fileKeys) {
            // 2. Guardar todos los campos de texto únicamente si su valor ha cambiado
            foreach ($textFields as $key => $value) {
                $existingModel = $settingsModels->get($key);
                $existingValue = $existingModel ? $existingModel->value : null;

                // Normalizar valores para evitar falsas diferencias por nulos o cadenas vacías
                $newValueNormalized = ($value === null || $value === '') ? null : $value;
                $existingValueNormalized = ($existingValue === null || $existingValue === '') ? null : $existingValue;

                if ($newValueNormalized !== $existingValueNormalized) {
                    if ($existingModel) {
                        $existingModel->value = $value;
                        $existingModel->save();
                    } else {
                        Setting::create([
                            'key' => $key,
                            'value' => $value
                        ]);
                    }
                }
            }

            // 3. Procesar y guardar archivos de imágenes
            foreach ($fileKeys as $key) {
                if ($request->hasFile($key)) {
                    // Guardar la imagen en la carpeta pública "landing"
                    $path = $request->file($key)->store('landing', 'public');
                    $publicPath = 'storage/' . $path;

                    $existingModel = $settingsModels->get($key);
                    if ($existingModel) {
                        // Eliminar imagen anterior del disco si existe
                        $oldValue = $existingModel->value;
                        if ($oldValue && strpos($oldValue, 'storage/') === 0) {
                            $oldDiskPath = str_replace('storage/', '', $oldValue);
                            if (Storage::disk('public')->exists($oldDiskPath)) {
                                Storage::disk('public')->delete($oldDiskPath);
                            }
                        }

                        $existingModel->value = $publicPath;
                        $existingModel->save();
                    } else {
                        Setting::create([
                            'key' => $key,
                            'value' => $publicPath
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Contenido de la Landing Page actualizado correctamente.');
    }
}
