<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$courses = \App\Models\Course::take(5)->get();
foreach ($courses as $course) {
    echo "Title: " . $course->title . " | Duration: " . $course->duration . "\n";
}
