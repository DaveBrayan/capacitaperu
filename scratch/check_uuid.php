<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = \App\Models\IndividualCertificateStudent::where('fullname', 'like', '%Juan%')->first();
if ($student) {
    echo "Estudiante: " . $student->fullname . "\n";
    echo "UUID Actual: " . $student->uuid . "\n";
} else {
    echo "Estudiante no encontrado\n";
}
