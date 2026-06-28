<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupLog;
use App\Models\User;
use App\Models\Advisor;
use App\Models\Promotion;
use App\Models\IndividualCertificate;
use App\Models\IndividualCertificateStudent;
use App\Models\Enrollment;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AdminBackupController extends Controller
{
    /**
     * Muestra la lista de copias de seguridad anteriores.
     */
    public function index()
    {
        $backups = BackupLog::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Genera una nueva copia de seguridad en Excel.
     */
    public function generate()
    {
        try {
            // 1. Obtener la información de todas las tablas indicadas
            
            // Pestaña 1: Staff / Equipo
            $staffData = User::whereIn('role', ['superadmin', 'admin', 'moderator'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($user) {
                    return [
                        'ID' => $user->id,
                        'Nombre' => $user->name,
                        'Nombre de Usuario' => $user->username,
                        'Email' => $user->email,
                        'Rol' => $user->role,
                        'Estado' => $user->status,
                        'Fecha de Creación' => $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 2: Asesores
            $advisorsData = Advisor::with('user')->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($advisor) {
                    return [
                        'ID' => $advisor->id,
                        'Nombre' => $advisor->name,
                        'Cargo o Puesto' => $advisor->position,
                        'Teléfono' => $advisor->phone,
                        'Estado' => $advisor->status,
                        'Creado Por' => $advisor->user ? $advisor->user->name : 'N/A',
                        'Fecha de Creación' => $advisor->created_at ? $advisor->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 3: Promociones
            $promotionsData = Promotion::with('user')->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($promo) {
                    return [
                        'ID' => $promo->id,
                        'Nombre' => $promo->name,
                        'Código' => $promo->code,
                        'Tipo de Descuento' => $promo->discount_type === 'percent' ? 'Porcentaje' : 'Monto Fijo',
                        'Valor' => $promo->value,
                        'Alcance' => $promo->target_type,
                        'Fecha de Inicio' => $promo->start_date ? $promo->start_date->format('d/m/Y H:i:s') : 'N/A',
                        'Fecha de Fin' => $promo->end_date ? $promo->end_date->format('d/m/Y H:i:s') : 'N/A',
                        'Activo' => $promo->is_active ? 'Sí' : 'No',
                        'Creado Por' => $promo->user ? $promo->user->name : 'N/A',
                        'Fecha de Creación' => $promo->created_at ? $promo->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 4: Certificados Individuales (Detalle Estudiantes con sus UUIDs y Códigos de Registro)
            $individualCertificatesData = IndividualCertificateStudent::with(['certificate', 'user'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($studCert) {
                    // Fallbacks si no son personalizados, trayendo datos del certificado base
                    $hours = $studCert->hours ?? ($studCert->certificate ? $studCert->certificate->hours : 'N/A');
                    
                    $issueDate = $studCert->issue_date 
                        ? $studCert->issue_date->format('d/m/Y') 
                        : ($studCert->certificate && $studCert->certificate->end_date 
                            ? $studCert->certificate->end_date->format('d/m/Y') 
                            : ($studCert->created_at ? $studCert->created_at->format('d/m/Y') : 'N/A'));
                            
                    $startDate = $studCert->start_date 
                        ? $studCert->start_date->format('d/m/Y') 
                        : ($studCert->certificate && $studCert->certificate->start_date 
                            ? $studCert->certificate->start_date->format('d/m/Y') 
                            : 'N/A');
                            
                    $endDate = $studCert->end_date 
                        ? $studCert->end_date->format('d/m/Y') 
                        : ($studCert->certificate && $studCert->certificate->end_date 
                            ? $studCert->certificate->end_date->format('d/m/Y') 
                            : 'N/A');

                    return [
                        'ID Registro' => $studCert->id,
                        'ID Certificado' => $studCert->individual_certificate_id,
                        'Título Certificado' => $studCert->certificate ? $studCert->certificate->title : 'N/A',
                        'Nombre Completo' => $studCert->fullname,
                        'DNI / Documento' => $studCert->dni,
                        'UUID' => $studCert->uuid,
                        'Número Registro' => $studCert->registration_number,
                        'Horas Académicas' => $hours,
                        'Fecha Emisión' => $issueDate,
                        'Fecha Inicio' => $startDate,
                        'Fecha Fin' => $endDate,
                        'Personalizado' => $studCert->is_custom ? 'Sí' : 'No',
                        'Creado Por' => $studCert->user ? $studCert->user->name : 'N/A',
                        'Fecha de Creación' => $studCert->created_at ? $studCert->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 5: Inscritos
            $enrollmentsData = Enrollment::with(['student', 'course', 'user'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($enroll) {
                    return [
                        'ID' => $enroll->id,
                        'Estudiante' => $enroll->student ? $enroll->student->name : 'N/A',
                        'Email Estudiante' => $enroll->student ? $enroll->student->email : 'N/A',
                        'Programa / Curso' => $enroll->course ? $enroll->course->title : 'N/A',
                        'Tipo de Programa' => $enroll->course ? ($enroll->course->type === 'diploma' ? 'Diplomado' : 'Curso') : 'N/A',
                        'Precio Pagado' => $enroll->price_paid,
                        'Progreso (%)' => $enroll->progress_percent,
                        'Completado' => $enroll->is_completed ? 'Sí' : 'No',
                        'Estado de Inscripción' => $enroll->status,
                        'Creado Por' => $enroll->user ? $enroll->user->name : 'N/A',
                        'Fecha de Inscripción' => $enroll->enrolled_at ? $enroll->enrolled_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 6: Categorías
            $categoriesData = Category::with('user')->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($cat) {
                    return [
                        'ID' => $cat->id,
                        'Nombre' => $cat->name,
                        'Slug' => $cat->slug,
                        'Descripción' => $cat->description,
                        'Icono' => $cat->icon,
                        'Estado' => $cat->status ? 'Activo' : 'Inactivo',
                        'Creado Por' => $cat->user ? $cat->user->name : 'N/A',
                        'Fecha de Creación' => $cat->created_at ? $cat->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 7: Docentes
            $instructorsData = Instructor::with('user')->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($inst) {
                    return [
                        'ID' => $inst->id,
                        'Nombre' => $inst->name,
                        'Email' => $inst->email,
                        'Teléfono' => $inst->phone,
                        'Especialidad' => $inst->specialty,
                        'Estado' => $inst->status,
                        'Creado Por' => $inst->user ? $inst->user->name : 'N/A',
                        'Fecha de Creación' => $inst->created_at ? $inst->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 8: Estudiantes (Incluyendo información de sus certificados)
            $studentsData = Student::with('user')->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($stud) {
                    // 1. Certificados de cursos regulares
                    $courseCerts = \App\Models\Certificate::where('student_id', $stud->id)
                        ->with('course')
                        ->get()
                        ->map(function ($cert) {
                            $hours = 'N/A';
                            if ($cert->course) {
                                $hours = $cert->course->duration ?: ($cert->course->certificate_template['hours'] ?? 'N/A');
                            }
                            return [
                                'uuid' => $cert->uuid,
                                'title' => $cert->course ? $cert->course->title : 'Curso',
                                'hours' => $hours,
                                'date' => $cert->issued_at ? $cert->issued_at->format('d/m/Y') : 'N/A',
                            ];
                        });

                    // 2. Certificados individuales (asociados por C.I. / NIT / DNI)
                    $indivCerts = collect();
                    if (!empty($stud->ci_nit)) {
                        $indivCerts = IndividualCertificateStudent::where('dni', $stud->ci_nit)
                            ->with('certificate')
                            ->get()
                            ->map(function ($ic) {
                                $hours = $ic->hours ?? ($ic->certificate ? $ic->certificate->hours : 'N/A');
                                $date = $ic->issue_date 
                                    ? $ic->issue_date->format('d/m/Y') 
                                    : ($ic->certificate && $ic->certificate->end_date 
                                        ? $ic->certificate->end_date->format('d/m/Y') 
                                        : ($ic->created_at ? $ic->created_at->format('d/m/Y') : 'N/A'));
                                return [
                                    'uuid' => $ic->uuid,
                                    'title' => $ic->certificate ? $ic->certificate->title : 'Certificado Individual',
                                    'hours' => $hours,
                                    'date' => $date,
                                ];
                            });
                    }

                    // Combinar ambos tipos de certificados
                    $allCerts = $courseCerts->concat($indivCerts);

                    $uuids = $allCerts->pluck('uuid')->filter()->implode(', ') ?: 'Ninguno';
                    $titles = $allCerts->pluck('title')->filter()->implode(' | ') ?: 'Ninguno';
                    $hours = $allCerts->pluck('hours')->filter()->implode(', ') ?: 'Ninguno';
                    $dates = $allCerts->pluck('date')->filter()->implode(', ') ?: 'Ninguno';

                    return [
                        'ID' => $stud->id,
                        'Nombre' => $stud->name,
                        'Nombre de Usuario' => $stud->username,
                        'Email' => $stud->email,
                        'Teléfono' => $stud->phone,
                        'C.I. / NIT' => $stud->ci_nit,
                        'Ciudad' => $stud->city,
                        'País' => $stud->country,
                        'Estado' => $stud->status,
                        'UUIDs Certificados' => $uuids,
                        'Cursos / Certificados' => $titles,
                        'Cargas Horarias (Horas)' => $hours,
                        'Fechas Emisión' => $dates,
                        'Creado Por' => $stud->user ? $stud->user->name : 'N/A',
                        'Fecha de Registro' => $stud->created_at ? $stud->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 9: Diplomados
            $diplomasData = Course::where('type', 'diploma')
                ->with(['instructor', 'categories', 'user'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($course) {
                    return [
                        'ID' => $course->id,
                        'Título' => $course->title,
                        'Precio Original' => $course->price,
                        'Precio Descuento' => $course->discount_price,
                        'Docente' => $course->instructor ? $course->instructor->name : 'N/A',
                        'Categorías' => $course->categories->pluck('name')->implode(', '),
                        'Nivel' => $course->level,
                        'Duración (Horas)' => $course->duration,
                        'Estado' => $course->status,
                        'Creado Por' => $course->user ? $course->user->name : 'N/A',
                        'Fecha de Creación' => $course->created_at ? $course->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // Pestaña 10: Cursos
            $coursesData = Course::where('type', 'course')
                ->with(['instructor', 'categories', 'user'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($course) {
                    return [
                        'ID' => $course->id,
                        'Título' => $course->title,
                        'Precio Original' => $course->price,
                        'Precio Descuento' => $course->discount_price,
                        'Docente' => $course->instructor ? $course->instructor->name : 'N/A',
                        'Categorías' => $course->categories->pluck('name')->implode(', '),
                        'Nivel' => $course->level,
                        'Duración (Horas)' => $course->duration,
                        'Estado' => $course->status,
                        'Creado Por' => $course->user ? $course->user->name : 'N/A',
                        'Fecha de Creación' => $course->created_at ? $course->created_at->format('d/m/Y H:i:s') : 'N/A',
                    ];
                })->toArray();

            // 2. Agrupar las hojas de cálculo en el orden invertido solicitado
            $sheets = [
                'Cursos' => $coursesData,
                'Diplomados' => $diplomasData,
                'Estudiantes' => $studentsData,
                'Docentes' => $instructorsData,
                'Categorias' => $categoriesData,
                'Inscritos' => $enrollmentsData,
                'Certificados Indiv' => $individualCertificatesData,
                'Promociones' => $promotionsData,
                'Asesores' => $advisorsData,
                'Staff y Equipo' => $staffData,
            ];

            // 3. Generar el XML de SpreadsheetML
            $xmlContent = $this->generateXmlSpreadsheet($sheets);

            // 4. Guardar archivo en almacenamiento local privado
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.xls";
            
            // Asegurar que existe la carpeta backups
            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }
            
            Storage::disk('local')->put("backups/{$filename}", $xmlContent);
            $filePath = storage_path("app/backups/{$filename}");
            
            // 5. Medir tamaño y contar registros respaldados
            $fileSizeBytes = File::exists($filePath) ? File::size($filePath) : 0;
            $formattedSize = $this->formatBytes($fileSizeBytes);
            
            $totalRecords = count($staffData) + count($advisorsData) + count($promotionsData) + 
                            count($individualCertificatesData) + count($enrollmentsData) + 
                            count($categoriesData) + count($instructorsData) + count($studentsData) + 
                            count($diplomasData) + count($coursesData);

            // 6. Crear el registro en base de datos
            BackupLog::create([
                'filename' => $filename,
                'path' => "backups/{$filename}",
                'size' => $formattedSize,
                'records_count' => $totalRecords,
            ]);

            return redirect()->route('admin.backups.index')->with('success', 'Copia de seguridad generada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')->with('error', 'Error al generar la copia de seguridad: ' . $e->getMessage());
        }
    }

    /**
     * Descarga una copia de seguridad específica.
     */
    public function download($id)
    {
        $backup = BackupLog::findOrFail($id);
        
        if (!Storage::disk('local')->exists($backup->path)) {
            return redirect()->route('admin.backups.index')->with('error', 'El archivo físico de la copia de seguridad no existe en el servidor.');
        }

        return Storage::disk('local')->download($backup->path, $backup->filename);
    }

    /**
     * Elimina el registro y el archivo físico.
     */
    public function destroy($id)
    {
        $backup = BackupLog::findOrFail($id);

        if (Storage::disk('local')->exists($backup->path)) {
            Storage::disk('local')->delete($backup->path);
        }

        $backup->delete();

        return redirect()->route('admin.backups.index')->with('success', 'Copia de seguridad eliminada del registro y del servidor.');
    }

    /**
     * Generador XML compatible con Microsoft Excel (SpreadsheetML) que soporta múltiples hojas.
     */
    private function generateXmlSpreadsheet($sheets)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        
        // Estilos de cabecera y por defecto
        $xml .= ' <Styles>' . "\n";
        $xml .= '  <Style ss:ID="Default" ss:Name="Normal">' . "\n";
        $xml .= '   <Alignment ss:Vertical="Center" ss:WrapText="1"/>' . "\n";
        $xml .= '   <Borders>' . "\n";
        $xml .= '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '   </Borders>' . "\n";
        $xml .= '   <Font ss:FontName="Segoe UI" x:Family="Swiss" ss:Size="10" ss:Color="#1F2937"/>' . "\n";
        $xml .= '   <Interior/>' . "\n";
        $xml .= '   <NumberFormat/>' . "\n";
        $xml .= '   <Protection/>' . "\n";
        $xml .= '  </Style>' . "\n";
        
        $xml .= '  <Style ss:ID="Header">' . "\n";
        $xml .= '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n";
        $xml .= '   <Font ss:FontName="Segoe UI" x:Family="Swiss" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/>' . "\n";
        $xml .= '   <Interior ss:Color="#FF7A00" ss:Pattern="Solid"/>' . "\n"; // Orange CapacitaPerú
        $xml .= '  </Style>' . "\n";
        
        $xml .= '  <Style ss:ID="TitleRow">' . "\n";
        $xml .= '   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>' . "\n";
        $xml .= '   <Font ss:FontName="Segoe UI" x:Family="Swiss" ss:Size="14" ss:Bold="1" ss:Color="#001144"/>' . "\n";
        $xml .= '  </Style>' . "\n";
        $xml .= ' </Styles>' . "\n";

        foreach ($sheets as $sheetName => $rows) {
            // Reemplazar caracteres no válidos para nombres de hojas
            $safeSheetName = substr(str_replace([':', '\\', '/', '?', '*', '[', ']'], '', $sheetName), 0, 31);
            $xml .= ' <Worksheet ss:Name="' . htmlspecialchars($safeSheetName) . '">' . "\n";
            
            // Establecer columnas por defecto más anchas
            $xml .= '  <Table ss:DefaultColumnWidth="150" ss:DefaultRowHeight="20">' . "\n";

            // Fila de Título de la Hoja
            $xml .= '   <Row ss:Height="30">' . "\n";
            $xml .= '    <Cell ss:StyleID="TitleRow"><Data ss:Type="String">' . htmlspecialchars(strtoupper($sheetName)) . ' - COPIA DE SEGURIDAD</Data></Cell>' . "\n";
            $xml .= '   </Row>' . "\n";
            
            // Fila vacía de separación
            $xml .= '   <Row ss:Height="15"></Row>' . "\n";

            if (!empty($rows)) {
                $headers = array_keys($rows[0]);
                
                // Fila de Cabecera
                $xml .= '   <Row ss:Height="25">' . "\n";
                foreach ($headers as $header) {
                    $xml .= '    <Cell ss:StyleID="Header"><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>' . "\n";
                }
                $xml .= '   </Row>' . "\n";

                // Filas de Datos
                foreach ($rows as $row) {
                    $xml .= '   <Row>' . "\n";
                    foreach ($row as $value) {
                        if (is_null($value)) {
                            $xml .= '    <Cell><Data ss:Type="String"></Data></Cell>' . "\n";
                        } elseif (is_numeric($value)) {
                            // Si empieza con 0 y tiene longitud > 1 (como un teléfono o documento), guardarlo como texto
                            if (is_string($value) && strpos($value, '0') === 0 && strlen($value) > 1) {
                                $xml .= '    <Cell><Data ss:Type="String">' . htmlspecialchars($value) . '</Data></Cell>' . "\n";
                            } else {
                                $xml .= '    <Cell><Data ss:Type="Number">' . $value . '</Data></Cell>' . "\n";
                            }
                        } else {
                            if (is_array($value) || is_object($value)) {
                                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                            }
                            $xml .= '    <Cell><Data ss:Type="String">' . htmlspecialchars($value) . '</Data></Cell>' . "\n";
                        }
                    }
                    $xml .= '   </Row>' . "\n";
                }
            } else {
                $xml .= '   <Row>' . "\n";
                $xml .= '    <Cell><Data ss:Type="String">No se encontraron registros en esta sección.</Data></Cell>' . "\n";
                $xml .= '   </Row>' . "\n";
            }

            $xml .= '  </Table>' . "\n";
            $xml .= ' </Worksheet>' . "\n";
        }

        $xml .= '</Workbook>' . "\n";
        return $xml;
    }

    /**
     * Formatea los bytes a una cadena legible.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
