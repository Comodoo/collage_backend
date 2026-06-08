<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Update Business Of Information Technology program
DB::table('programs')
    ->where('code', 'BBSC')
    ->update(['tuition_fee' => 4500000.00]); // 4.5 million TSH

// Update BSC-CS if it's too low (currently 150000.00)
DB::table('programs')
    ->where('code', 'BSC-CS')
    ->update(['tuition_fee' => 5000000.00]); // 5 million TSH

echo "Tuition fees updated successfully.\n";
