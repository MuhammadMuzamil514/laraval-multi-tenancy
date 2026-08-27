<?php

use App\Models\Tenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

it('is a database-aware tenant model', function () {
    expect(new Tenant)->toBeInstanceOf(TenantWithDatabase::class);
});

it('is the configured tenant model', function () {
    expect(config('tenancy.tenant_model'))->toBe(Tenant::class);
});

it('stores the name in both the column and the data payload', function () {
    $tenant = new Tenant;
    $tenant->name = 'Acme Corp';

    expect($tenant->name)->toBe('Acme Corp')
        ->and($tenant->getAttribute('name'))->toBe('Acme Corp')
        ->and($tenant->data)->toMatchArray(['name' => 'Acme Corp']);
});

it('falls back to the data payload when no name column value is set', function () {
    $tenant = new Tenant;
    $tenant->setAttribute('data', ['name' => 'From Data']);

    expect($tenant->name)->toBe('From Data');
});
