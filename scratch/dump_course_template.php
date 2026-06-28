<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$course = \App\Models\Course::whereNotNull('certificate_template')->first();
if ($course) {
    echo "Title: " . $course->title . "\n";
    print_r($course->certificate_template);
} else {
    echo "No courses with template found.\n";
}
