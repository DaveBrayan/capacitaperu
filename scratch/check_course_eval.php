<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$course = \App\Models\Course::first();
if ($course) {
    echo "Title: " . $course->title . "\n";
    echo "Duration: " . $course->duration . "\n";
    echo "Evaluation Config: " . json_encode($course->evaluation_config, JSON_PRETTY_PRINT) . "\n";
}
