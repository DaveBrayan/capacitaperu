<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = [
    'users',
    'advisors',
    'promotions',
    'individual_certificates',
    'individual_certificate_students',
    'enrollments',
    'categories',
    'instructors',
    'students',
    'courses'
];

foreach ($tables as $t) {
    if (Schema::hasTable($t)) {
        $cols = Schema::getColumnListing($t);
        echo "Table [{$t}]:\n";
        foreach ($cols as $c) {
            if (str_contains($c, 'user') || str_contains($c, 'creator') || str_contains($c, 'admin') || str_contains($c, 'staff')) {
                echo "  -> matches: {$c}\n";
            }
        }
        // Print all columns for context
        echo "  All: " . implode(', ', $cols) . "\n\n";
    } else {
        echo "Table [{$t}] does not exist.\n\n";
    }
}
