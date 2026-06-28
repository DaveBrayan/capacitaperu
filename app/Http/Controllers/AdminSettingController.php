<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'app_logo', 'app_logo_white']);

        // Obtener todos los settings existentes indexados por clave
        $settingsModels = Setting::all()->keyBy('key');

        DB::transaction(function () use ($data, $settingsModels, $request) {
            // 1. Guardar todos los campos de texto únicamente si su valor ha cambiado
            foreach ($data as $key => $value) {
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

            // 2. Procesar logo principal
            if ($request->hasFile('app_logo')) {
                $path = $request->file('app_logo')->store('public/img');
                $publicPath = 'storage/' . str_replace('public/', '', $path);

                $existingModel = $settingsModels->get('app_logo');
                if ($existingModel) {
                    // Eliminar logo principal anterior si existe
                    $oldValue = $existingModel->value;
                    if ($oldValue && strpos($oldValue, 'storage/') === 0) {
                        $oldDiskPath = 'public/' . str_replace('storage/', '', $oldValue);
                        if (Storage::disk('local')->exists($oldDiskPath)) {
                            Storage::disk('local')->delete($oldDiskPath);
                        }
                    }

                    $existingModel->value = $publicPath;
                    $existingModel->save();
                } else {
                    Setting::create([
                        'key' => 'app_logo',
                        'value' => $publicPath
                    ]);
                }
            }

            // 3. Procesar logo blanco
            if ($request->hasFile('app_logo_white')) {
                $path = $request->file('app_logo_white')->store('public/img');
                $publicPath = 'storage/' . str_replace('public/', '', $path);

                $existingModel = $settingsModels->get('app_logo_white');
                if ($existingModel) {
                    // Eliminar logo blanco anterior si existe
                    $oldValue = $existingModel->value;
                    if ($oldValue && strpos($oldValue, 'storage/') === 0) {
                        $oldDiskPath = 'public/' . str_replace('storage/', '', $oldValue);
                        if (Storage::disk('local')->exists($oldDiskPath)) {
                            Storage::disk('local')->delete($oldDiskPath);
                        }
                    }

                    $existingModel->value = $publicPath;
                    $existingModel->save();
                } else {
                    Setting::create([
                        'key' => 'app_logo_white',
                        'value' => $publicPath
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Configuraciones actualizadas correctamente.');
    }

    /**
     * Ejecuta comandos Artisan desde la interfaz web (solo para superadmin).
     */
    public function runCommand(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Acción no autorizada.');
        }

        $command = $request->input('command');

        try {
            $output = '';
            if ($command === 'storage_link') {
                // 1. Asegurar que la carpeta de origen exista
                $target = storage_path('app/public');
                if (!file_exists($target)) {
                    mkdir($target, 0755, true);
                }

                // 2. Detectar la ruta real de la carpeta pública (soportando subcarpetas o carpetas hermanas como public_html)
                $basePath = base_path();
                $linkPath = null;

                $possiblePublicPaths = [
                    dirname($basePath) . '/public_html', // Hermano cPanel (ej: /home/capacit7/public_html)
                    $basePath . '/../public_html',       // Alternativa Hermano
                    $basePath . '/public_html',          // Interno
                    dirname($basePath) . '/httpdocs',    // Hermano Plesk
                    $basePath . '/httpdocs',             // Interno Plesk
                    $basePath . '/public',               // Laravel Estándar
                ];

                foreach ($possiblePublicPaths as $p) {
                    if (file_exists($p) && is_dir($p)) {
                        $linkPath = $p . '/storage';
                        break;
                    }
                }

                if (!$linkPath) {
                    $linkPath = $basePath . '/public/storage';
                }

                // 3. Eliminar enlace roto o archivo existente si impide la creación
                if (file_exists($linkPath) || is_link($linkPath)) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        if (is_link($linkPath)) {
                            unlink($linkPath);
                        } elseif (is_dir($linkPath)) {
                            rmdir($linkPath);
                        }
                    } else {
                        unlink($linkPath);
                    }
                }

                // 4. Intentar crear enlace simbólico de forma directa
                $success = false;
                try {
                    $success = symlink($target, $linkPath);
                    if ($success) {
                        $output = "Enlace simbólico creado con éxito desde PHP:\n$linkPath -> $target";
                    }
                } catch (\Exception $e) {
                    $output = "La función PHP symlink() falló: " . $e->getMessage() . "\nIntentando vía Artisan...\n";
                }

                if (!$success) {
                    // Fallback: Ejecutar comando Artisan
                    Artisan::call('storage:link');
                    $output .= Artisan::output();
                }

                // Añadir nota del fallback automático
                $output .= "\n\n[INFO] Se ha activado la ruta de fallback en routes/web.php. Las imágenes se mostrarán correctamente incluso si el enlace simbólico del hosting falla.";

            } elseif ($command === 'clear_cache') {
                Artisan::call('optimize:clear');
                $output = Artisan::output();
            } else {
                return redirect()->back()->with('error_command', 'Comando no reconocido.');
            }

            return redirect()->back()->with('success_command', "Comando ejecutado con éxito:\n" . $output);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_command', 'Error al ejecutar comando: ' . $e->getMessage() . "\n\nNota: Si no se pudo crear el symlink, no te preocupes, el sistema ya cuenta con una ruta de fallback automática para cargar las imágenes sin problema.");
        }
    }

    /**
     * Ejecuta consultas SQL directamente (solo para superadmin).
     */
    public function runQuery(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Acción no autorizada.');
        }

        $sql = $request->input('sql');

        if (empty($sql)) {
            return redirect()->back()->with('error_query', 'La consulta SQL no puede estar vacía.');
        }

        $trimmedSql = trim(strtolower($sql));
        $isSelect = strpos($trimmedSql, 'select') === 0 || 
                    strpos($trimmedSql, 'show') === 0 || 
                    strpos($trimmedSql, 'describe') === 0 || 
                    strpos($trimmedSql, 'explain') === 0;

        try {
            if ($isSelect) {
                $results = DB::select($sql);
                // Convertir a un array simple asociativo
                $resultsArray = json_decode(json_encode($results), true);
                return redirect()->back()->with([
                    'query_results' => $resultsArray,
                    'query_sql' => $sql,
                    'success_query' => 'Consulta ejecutada con éxito. ' . count($results) . ' filas encontradas.'
                ]);
            } else {
                DB::statement($sql);
                return redirect()->back()->with([
                    'success_query' => 'Consulta de base de datos ejecutada con éxito (Operación completada).'
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error_query', 'Error de consulta SQL: ' . $e->getMessage())->withInput();
        }
    }
}
