<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$course = \App\Models\Course::first();
if ($course) {
    echo "Title: " . $course->title . "\n";
    echo "Duration (column): " . $course->duration . "\n";
    echo "Certificate Template: " . json_encode($course->certificate_template, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "No courses found.\n";
}
