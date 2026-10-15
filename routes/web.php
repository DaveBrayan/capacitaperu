<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminInstructorController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminEnrollmentController;
use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\GuestRegistrationController;
use App\Models\Certificate;
use App\Http\Controllers\ClassroomController;
use App\Models\Course;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Front\CatalogController;
use App\Http\Controllers\AdminPromotionController;
use App\Http\Controllers\AdminAdvisorController;
use App\Http\Controllers\AdminSettingController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (ACCESO LIBRE / QR / INVITACIONES)
|--------------------------------------------------------------------------
*/

Route::get('/', function () { 
    // 1. Cursos para el Hero Carousel (Los últimos 3 publicados)
    $carouselCourses = Course::where('status', 'published')
                        ->withCount('lessons')
                        ->latest()
                        ->take(3)
                        ->get();

    // 2. Cursos para la cuadrícula (Los últimos 3 publicados)
    $latestCourses = Course::where('status', 'published')
                           ->with(['categories', 'instructor'])
                           ->latest()
                           ->take(3)
                           ->get();

    // 3. Categorías para la nueva sección
    $categories = App\Models\Category::where('status', 1)
                    ->whereIn('target_type', ['course', 'both'])
                    ->withCount('courses')
                    ->get();

    return view('front.home', compact('carouselCourses', 'latestCourses', 'categories')); 
})->name('home');


Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog');
Route::get('/diplomados', [CatalogController::class, 'diplomados'])->name('diplomados');
Route::get('/curso/{slug}', [CatalogController::class, 'show'])->name('course.show');
Route::get('/nosotros', function () { return view('front.about'); })->name('about');
Route::get('/carrito', function () { return view('front.cart'); })->name('cart');
Route::get('/contacto', function () { return view('front.contact'); })->name('contact');
Route::get('/terminos', function () { return view('front.terms'); })->name('terms');
Route::get('/privacidad', function () { return view('front.privacy'); })->name('privacy');
Route::post('/reclamaciones', [\App\Http\Controllers\ClaimController::class, 'store'])->name('claims.store');

// --- FORMULARIO DE REGISTRO POR INVITACIÓN (PÚBLICO) ---
Route::get('/registro-invitado/exito', [App\Http\Controllers\GuestRegistrationController::class, 'showSuccess'])->name('enrollment.guest.success');
Route::get('/registro-invitado/{token}', [App\Http\Controllers\GuestRegistrationController::class, 'showForm'])->name('enrollment.guest.form');
Route::post('/registro-invitado/{token}', [App\Http\Controllers\GuestRegistrationController::class, 'processRegistration'])->name('enrollment.guest.process');

// Asistencia Pública
Route::get('/asistencia/{courseSlug}/{moduleId}', [AttendanceController::class, 'show'])->name('attendance.show');
Route::post('/asistencia/{courseSlug}/{moduleId}', [AttendanceController::class, 'store'])->name('attendance.store');

// Validación de Certificado (QR)
Route::get('/validar/certificado/{uuid}', function ($uuid) {
    $certificate = Certificate::where('uuid', strtoupper($uuid))
        ->with(['student', 'course.instructor'])
        ->firstOrFail();

    $executive_signature = \App\Models\Setting::where('key', 'individual_cert_executive_signature')->value('value');
    $academic_signature  = \App\Models\Setting::where('key', 'individual_cert_academic_signature')->value('value');
    $executive_seal      = \App\Models\Setting::where('key', 'individual_cert_executive_seal')->value('value');
    $academic_seal       = \App\Models\Setting::where('key', 'individual_cert_academic_seal')->value('value');

    return view('front.certificate-view', compact('certificate', 'executive_signature', 'academic_signature', 'executive_seal', 'academic_seal'));
})->name('certificate.verify');

// --- CERTIFICADOS INDIVIDUALES (BÚSQUEDA Y VISTA) ---
Route::get('/certificado', [\App\Http\Controllers\Front\PublicCertificateController::class, 'individualIndex'])->name('front.certificate.individual.search');
Route::get('/certificado/ver/{uuid}', [\App\Http\Controllers\Front\PublicCertificateController::class, 'showPublic'])->name('front.certificate.individual.view');
Route::get('/certificado/slug/{slug}', [\App\Http\Controllers\AdminIndividualCertificateController::class, 'showPublic'])->name('front.certificate.individual');

// --- CERTIFICADOS DE DOCENTES (VISTA PÚBLICA) ---
Route::get('/certificado-docente/ver/{uuid}', [\App\Http\Controllers\AdminCourseCertificateController::class, 'viewTeacherCertificate'])->name('teacher-certificate.view');

// Servidor de Media Privado/Público
Route::get('/public-media/{filename}', function ($filename) {
    $path = storage_path('app/public/media/' . $filename);
    if (!file_exists($path)) {
        $path = storage_path('app/public/' . $filename);
    }
    if (!file_exists($path)) {
        abort(404);
    }
    $file = \Illuminate\Support\Facades\File::get($path);
    $type = \Illuminate\Support\Facades\File::mimeType($path);
    $response = \Illuminate\Support\Facades\Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->where('filename', '.*')->name('media.show');

// Ruta Fallback para Storage (Si el enlace simbólico storage:link falla o no está creado)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    $file = \Illuminate\Support\Facades\File::get($filePath);
    $type = \Illuminate\Support\Facades\File::mimeType($filePath);
    return \Illuminate\Support\Facades\Response::make($file, 200, [
        'Content-Type' => $type,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.fallback');



/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de configuración de cuenta (ACCESIBLES SIEMPRE QUE ESTÉS AUTENTICADO)
Route::middleware(['auth:web,student'])->group(function () {
    Route::get('/configurar-credenciales', [AuthController::class, 'showSetup'])->name('auth.setup');
    Route::post('/configurar-credenciales', [AuthController::class, 'updateCredentials'])->name('auth.setup.store');
});


/*
|--------------------------------------------------------------------------
| RUTAS ESTUDIANTES (PROTEGIDAS POR SETUP.CHECK)
|--------------------------------------------------------------------------
*/
// Agregamos 'setup.check' aquí para obligar a configurar perfil/pin antes de entrar
Route::prefix('estudiante')->name('student.')->middleware(['auth:student', 'setup.check'])->group(function () {
    
    Route::get('/mi-aprendizaje', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/mis-certificados', function () { return view('student.certificates'); })->name('certificates');
    
    Route::get('/perfil', [App\Http\Controllers\Student\ProfileController::class, 'show'])->name('profile');
    Route::put('/perfil', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/seguridad', [App\Http\Controllers\Student\ProfileController::class, 'updateSecurity'])->name('profile.security');
    
    Route::get('/lista-deseos', function () { return view('student.wishlist'); })->name('wishlist');
    Route::get('/facturacion', [App\Http\Controllers\Student\BillingController::class, 'index'])->name('billing');
    
    Route::get('/certificado/{id}', function ($id) {
        $cert = Certificate::findOrFail($id);
        return redirect()->route('certificate.verify', ['uuid' => str_replace('YATI-', '', $cert->uuid)]);
    })->name('certificate.view');

    Route::get('/certificado/obtener/{course}', [App\Http\Controllers\Student\CertificateController::class, 'getOrCreate'])->name('certificate.claim');
});


/*
|--------------------------------------------------------------------------
| RUTAS AULA VIRTUAL (CLASSROOM) - (PROTEGIDAS POR SETUP.CHECK)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:student', 'setup.check'])->group(function () { 
    // Vista principal del aula
    Route::get('/clase/{course}/{lesson?}', [ClassroomController::class, 'show'])->name('classroom.show');

    // Acciones académicas
    Route::post('/clase/{course}/leccion/{lesson}/completar', [ClassroomController::class, 'completeLesson'])->name('classroom.lesson.complete');
    Route::post('/clase/{course}/leccion/{lesson}/quiz', [ClassroomController::class, 'submitQuiz'])->name('classroom.quiz.submit');
    
    // Evaluación Final
    Route::post('/clase/{course}/final/examen', [ClassroomController::class, 'submitFinalExam'])->name('classroom.final.exam');
    
    // Proyecto Final (Subida y Cancelación)
    Route::post('/clase/{course}/final/proyecto', [ClassroomController::class, 'submitFinalProject'])->name('classroom.final.project');
    Route::delete('/clase/{course}/final/proyecto/cancelar', [ClassroomController::class, 'cancelFinalProject'])->name('classroom.final.project.cancel');
});

/*
|--------------------------------------------------------------------------
| RUTAS ADMINISTRADOR (PROTEGIDAS POR SETUP.CHECK)
|--------------------------------------------------------------------------
*/
// Agregamos 'setup.check' aquí para obligar a admins a cambiar pass/pin si es necesario
Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'setup.check'])->group(function () {
    
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestión Académica
    Route::resource('cursos', AdminCourseController::class)->names('courses');
    Route::resource('diplomados', AdminCourseController::class)->names('diplomas');
    Route::resource('docentes', AdminInstructorController::class)->names('instructors');
    Route::get('/docentes/firma/{filename}', [AdminInstructorController::class, 'getSignature'])->name('instructors.signature');
    Route::get('/docentes/{id}/cursos-certificados', [AdminInstructorController::class, 'getCoursesWithCertificates'])->name('instructors.courses-certificates');
    Route::post('/cursos/{id}/vincular-certificado', [AdminInstructorController::class, 'linkCertificateToCourse'])->name('courses.link-certificate');
    Route::resource('categorias', AdminCategoryController::class)->names('categories');
    Route::resource('certificados-individuales', \App\Http\Controllers\AdminIndividualCertificateController::class)->names('individual-certificates');
    Route::resource('certificados-cursos', \App\Http\Controllers\AdminCourseCertificateController::class)->names('course-certificates');
    Route::resource('plantillas-certificados', \App\Http\Controllers\AdminCertificateTemplateController::class)->names('certificate-templates');
    Route::post('plantillas-certificados/{id}/duplicar', [\App\Http\Controllers\AdminCertificateTemplateController::class, 'duplicate'])->name('certificate-templates.duplicate');
    Route::get('plantillas-certificados-api/list', [\App\Http\Controllers\AdminCertificateTemplateController::class, 'getTemplatesJson'])->name('certificate-templates.api.list');
    Route::get('individual-certificates/global/firmas', [\App\Http\Controllers\AdminIndividualCertificateController::class, 'getSignatures'])->name('individual-certificates.get-signatures');
    Route::post('individual-certificates/global/firmas', [\App\Http\Controllers\AdminIndividualCertificateController::class, 'updateSignatures'])->name('individual-certificates.update-signatures');
    
    // Rutas de Estudiantes para Certificados Individuales
    Route::get('individual-certificates/{id}/estudiantes', [\App\Http\Controllers\AdminIndividualCertificateController::class, 'getStudents'])->name('individual-certificates.students.get');
    Route::post('individual-certificates/{id}/estudiantes', [\App\Http\Controllers\AdminIndividualCertificateController::class, 'addStudent'])->name('individual-certificates.students.add');
    Route::delete('individual-certificates/estudiantes/{studentId}', [\App\Http\Controllers\AdminIndividualCertificateController::class, 'removeStudent'])->name('individual-certificates.students.remove');

    // PROMOCIONES
    Route::resource('promociones', AdminPromotionController::class)->names('promotions'); 

    // Permisos de Usuarios (Staff)
    Route::get('/usuarios/{id}/permissions', [AdminStaffController::class, 'getPermissions'])->name('admin.staff.permissions.get');
    Route::post('/usuarios/{id}/permissions', [AdminStaffController::class, 'syncPermissions'])->name('admin.staff.permissions.sync');

    // Gestión de Asesores (WhatsApp)
    Route::resource('asesores', AdminAdvisorController::class)->names('advisors');

    // Gestión de Reclamaciones
    Route::resource('reclamaciones', \App\Http\Controllers\AdminClaimController::class)->names('claims')->only(['index', 'update', 'destroy']);

    // --- RUTAS NUEVAS PARA MODALES ---
    // Obtener trabajos de un curso
    Route::get('/cursos/{id}/trabajos', [AdminCourseController::class, 'getProjectSubmissions'])->name('courses.submissions');
    // Calificar un trabajo
    Route::post('/trabajos/{id}/calificar', [AdminCourseController::class, 'gradeProject'])->name('courses.grade');
    
    // Gestor de Medios
    Route::get('/media/list', [AdminMediaController::class, 'list'])->name('media.list');
    Route::get('/media/ver/{filename}', [AdminMediaController::class, 'show'])->name('media.view');
    Route::post('/media/upload', [AdminMediaController::class, 'upload'])->name('media.upload');
    Route::post('/media/rename', [AdminMediaController::class, 'rename'])->name('media.rename');
    Route::post('/media/delete', [AdminMediaController::class, 'delete'])->name('media.delete');
    
    // Inscripciones
    Route::get('/inscripciones', [AdminEnrollmentController::class, 'index'])->name('enrollments.index');
    Route::put('/inscripciones/{id}/aprobar', [AdminEnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::delete('/inscripciones/{id}', [AdminEnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    Route::post('/inscripciones', [AdminEnrollmentController::class, 'store'])->name('enrollments.store');
    
    // Inscripción de usuario antiguo
    Route::post('/inscripciones/antiguo', [AdminEnrollmentController::class, 'storeExisting'])->name('enrollments.store_existing');

    Route::post('/generar-link-registro', [AdminEnrollmentController::class, 'generateRegistrationToken'])->name('enrollments.generate_token');

    // Gestión de Usuarios Estudiantes
    Route::resource('estudiantes', AdminStudentController::class)->names('students');
    Route::put('/estudiantes/{id}/reset-password', [AdminStudentController::class, 'resetPassword'])->name('students.reset_password');
    Route::put('/estudiantes/{id}/reset-pin', [AdminStudentController::class, 'resetPin'])->name('students.reset_pin');

    // --- RUTAS NUEVAS PARA HISTORIAL ---
    Route::get('/estudiantes/{id}/historial', [AdminStudentController::class, 'history'])->name('students.history');
    Route::post('/estudiantes/progreso/toggle', [AdminStudentController::class, 'toggleProgress'])->name('students.toggle_progress');

    Route::post('/estudiantes/{id}/reset-eval/{courseId}', [AdminStudentController::class, 'resetEvaluation'])->name('students.reset_evaluation');

    // Gestión de Staff/Usuarios Administrativos
    Route::prefix('usuarios')->name('staff.')->controller(AdminStaffController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::put('/{id}/reset-password', 'resetPassword')->name('reset_password');
        Route::put('/{id}/reset-pin', 'resetPin')->name('reset_pin');
    });

    // Gestión de Copias de Seguridad (Backup)
    Route::get('/backups', [App\Http\Controllers\AdminBackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/generar', [App\Http\Controllers\AdminBackupController::class, 'generate'])->name('backups.generate');
    Route::get('/backups/descargar/{id}', [App\Http\Controllers\AdminBackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/{id}', [App\Http\Controllers\AdminBackupController::class, 'destroy'])->name('backups.destroy');

    // Gestión de la Landing Page (Contenido dinámico)
    Route::get('/landing', [App\Http\Controllers\AdminLandingController::class, 'edit'])->name('landing.edit');
    Route::post('/landing', [App\Http\Controllers\AdminLandingController::class, 'update'])->name('landing.update');

    // Configuración General y Utilidades de Superadmin
    Route::get('/configuracion', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/configuracion', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/configuracion/ejecutar-comando', [AdminSettingController::class, 'runCommand'])->name('settings.run_command');
    Route::post('/configuracion/ejecutar-consulta', [AdminSettingController::class, 'runQuery'])->name('settings.run_query');

});