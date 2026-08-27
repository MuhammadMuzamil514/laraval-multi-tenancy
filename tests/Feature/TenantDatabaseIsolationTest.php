<?php

use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;

/**
 * These tests provision real tenant databases (sqlite files) so we can prove
 * that tenant data is truly isolated from the central DB and from each other.
 */
beforeEach(function () {
    config()->set('tenancy.database.managers.sqlite', \Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class);
});

afterEach(function () {
    Tenant::all()->each(function (Tenant $tenant) {
        $file = database_path($tenant->database()->getName());
        $tenant->delete();
        if (is_file($file)) {
            @unlink($file);
        }
    });
});

function makeTenant(string $id): Tenant
{
    return Tenant::create(['id' => $id, 'data' => ['name' => ucfirst($id)]]);
}

it('creates and migrates a separate database per tenant', function () {
    $tenant = makeTenant('alpha');

    expect(is_file(database_path($tenant->database()->getName())))->toBeTrue();

    $tenant->run(function () {
        expect(\Illuminate\Support\Facades\Schema::hasTable('products'))->toBeTrue();
    });
});

it('keeps products written in one tenant invisible to another tenant and to the central db', function () {
    $alpha = makeTenant('alpha');
    $beta = makeTenant('beta');

    $alpha->run(function () {
        Product::create(['name' => 'Alpha Widget', 'price' => 10, 'stock' => 5]);
    });

    $beta->run(function () {
        Product::create(['name' => 'Beta Gadget', 'price' => 20, 'stock' => 8]);
    });

    $alpha->run(function () {
        expect(Product::pluck('name')->all())->toBe(['Alpha Widget']);
    });

    $beta->run(function () {
        expect(Product::pluck('name')->all())->toBe(['Beta Gadget']);
    });

    // Central connection has its own products table (from RefreshDatabase) and sees neither.
    expect(Product::count())->toBe(0);
});

it('keeps the User model bound to the central database inside tenant context', function () {
    $user = User::factory()->create();
    $tenant = makeTenant('alpha');

    $tenant->run(function () use ($user) {
        // Users are a central resource: even with the default connection swapped
        // to the tenant, the User model still reads from the central DB.
        expect(User::whereKey($user->getKey())->exists())->toBeTrue();
    });
});
