<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

it('has the stancl tenancy package installed and configured', function () {
    expect(class_exists(\Stancl\Tenancy\TenancyServiceProvider::class))->toBeTrue()
        ->and(config('tenancy.central_domains'))->toContain('localhost')
        ->and(config('tenancy.bootstrappers'))->toContain(
            \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class
        );
});

it('registers the application TenancyServiceProvider', function () {
    expect(app()->getProviders(\App\Providers\TenancyServiceProvider::class))->not->toBeEmpty();
});

it('exposes tenant routes guarded by tenancy middleware', function () {
    $route = collect(Route::getRoutes()->getRoutes())
        ->first(fn ($r) => $r->getName() === 'tenant.dashboard');

    expect($route)->not->toBeNull()
        ->and($route->gatherMiddleware())
        ->toContain(InitializeTenancyByDomain::class)
        ->toContain(PreventAccessFromCentralDomains::class)
        ->and($route->getDomain())->toBe('{tenantDomain}.localhost');
});

it('registers tenant resource routes for products', function () {
    $names = collect(Route::getRoutes()->getRoutes())
        ->map->getName()
        ->filter(fn ($name) => str_starts_with((string) $name, 'tenant.products.'))
        ->values();

    expect($names)->toContain('tenant.products.index')
        ->toContain('tenant.products.store')
        ->toContain('tenant.products.update')
        ->toContain('tenant.products.destroy');
});

it('ships a dedicated tenant migrations directory', function () {
    expect(is_dir(database_path('migrations/tenant')))->toBeTrue()
        ->and(glob(database_path('migrations/tenant/*.php')))->not->toBeEmpty();
});
