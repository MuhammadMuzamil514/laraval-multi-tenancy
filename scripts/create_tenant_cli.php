<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

$name = $argv[1] ?? 'CLI Tenant';
$domain = $argv[2] ?? Str::slug($name).'.localhost';

try {
    $request = Request::create('/tenants', 'POST', [
        'name' => $name,
        'domain' => $domain,
    ]);

    $controller = app(TenantController::class);
    $response = $controller->store($request);

    echo 'Controller response: ';
    if (is_object($response) && method_exists($response, 'getStatusCode')) {
        echo $response->getStatusCode()."\n";
    } else {
        echo "OK\n";
    }
    echo "Created tenant: $domain\n";
} catch (Throwable $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
