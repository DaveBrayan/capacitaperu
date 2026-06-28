<?php
use App\Models\Course;
use App\Models\Enrollment;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$course = Course::find(1);
$enrollment = Enrollment::where('student_id', 2)->where('course_id', 1)->first();

$enrollment->is_completed = true;
$enrollment->save();
echo "Deivid enrollment updated to is_completed = true\n";
