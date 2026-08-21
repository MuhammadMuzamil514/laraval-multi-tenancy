<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$failed = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->limit(5)->get();
$cnt = DB::table('failed_jobs')->count();

echo "failed_jobs count: $cnt\n\n";
foreach ($failed as $f) {
    echo "id: {$f->id}\nconnection: {$f->connection}\nqueue: {$f->queue}\nfailed_at: {$f->failed_at}\nexception: {$f->exception}\n----\n";
}
