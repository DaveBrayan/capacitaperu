<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$course = \App\Models\Course::first();
if ($course) {
    echo "Hours in template: " . ($course->certificate_template['hours'] ?? 'N/A') . "\n";
    echo "Duration: " . $course->duration . "\n";
}
