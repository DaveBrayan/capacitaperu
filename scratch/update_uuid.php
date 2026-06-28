<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = \App\Models\IndividualCertificateStudent::where('fullname', 'like', '%Juan%')->first();
if ($student) {
    $prefix = strtoupper(\Illuminate\Support\Str::random(4));
    $year = date('Y');
    $suffix = strtoupper(\Illuminate\Support\Str::random(4));
    $newUuid = "{$prefix}-{$year}-{$suffix}";
    
    $student->uuid = $newUuid;
    $student->save();
    
    echo "Actualizado!\n";
    echo "Estudiante: " . $student->fullname . "\n";
    echo "Nuevo UUID: " . $student->uuid . "\n";
} else {
    echo "Estudiante no encontrado\n";
}
