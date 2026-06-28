<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

$affected = DB::table('certificates')
    ->where('uuid', 'like', 'YATI-%')
    ->update([
        'uuid' => DB::raw("REPLACE(uuid, 'YATI-', '')")
    ]);

echo "Registros actualizados: " . $affected . "\n";
