<?php

use App\Jobs\ProcessTenantSetupJob;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Stancl\Tenancy\Events\TenantCreated;

it('creates a tenant and dispatches a setup job', function () {
    Queue::fake();

    $user = User::factory()->create();
    Event::fake([TenantCreated::class]);

    $response = $this->actingAs($user)
        ->post('/tenants', [
            'name' => 'Acme Labs',
            'domain' => 'acme-labs.localhost',
        ]);

    $response->assertRedirect(route('tenants.index'));

    $tenant = Tenant::query()->latest('created_at')->first();

    expect($tenant)->not->toBeNull()
        ->and($tenant->name)->toBe('Acme Labs')
        ->and($tenant->domains()->first()->domain)->toBe('acme-labs.localhost');

    Queue::assertPushed(ProcessTenantSetupJob::class, function (ProcessTenantSetupJob $job) use ($tenant) {
        return $job->tenant->id === $tenant->id;
    });
});
