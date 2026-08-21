<?php

use App\Jobs\ProcessTenantSetupJob;
use App\Models\Tenant;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$tenant = Tenant::create([
    'id' => 'demo-test-'.uniqid(),
    'name' => 'Demo Test',
    'data' => ['plan' => 'basic'],
]);

ProcessTenantSetupJob::dispatch($tenant);

echo "enqueued\n";
echo DB::table('jobs')->count()."\n";
