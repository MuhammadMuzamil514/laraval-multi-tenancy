<?php

use App\Http\Controllers\TenantDashboardController;
use App\Models\Tenant;
use Illuminate\Support\Facades\Event;
use Stancl\Tenancy\Events\TenantCreated;

it('renders a dashboard for the active tenant', function () {
    Event::fake([TenantCreated::class]);
    $tenant = Tenant::create([
        'id' => 'tenant-demo',
        'name' => 'Demo Tenant',
        'data' => ['plan' => 'pro'],
    ]);

    tenancy()->initialize($tenant);

    $response = app(TenantDashboardController::class)->index();

    expect($response->name())->toBe('tenant.dashboard')
        ->and($response->getData()['tenant']->id)->toBe($tenant->id)
        ->and($response->render())->toContain('Tenant Dashboard')
        ->and($response->render())->toContain('Demo Tenant');

    tenancy()->end();
});
