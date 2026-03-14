<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('peserta', function (Blueprint $table) {
    if (!Schema::hasColumn('peserta', 'latitude')) {
        $table->string('latitude')->nullable();
    }
    if (!Schema::hasColumn('peserta', 'longitude')) {
        $table->string('longitude')->nullable();
    }
});

echo "Columns latitude and longitude added successfully.\n";
