<?php

use App\Events\DashboardStatsUpdated;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Event;
use Stancl\Tenancy\Events\TenantCreated;

it('shows the current dashboard statistics', function () {
    $user = User::factory()->create();
    Event::fake([TenantCreated::class]);
    Tenant::create(['id' => 'tenant-one', 'data' => ['name' => 'Tenant One']]);
    Product::create(['name' => 'Keyboard', 'price' => 80, 'stock' => 4]);
    Product::create(['name' => 'Monitor', 'price' => 200, 'stock' => 20]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    expect($response->viewData('stats'))
        ->toMatchArray([
            'tenant_count' => 1,
            'product_count' => 2,
            'inventory_value' => 4320.0,
            'low_stock_count' => 1,
        ]);
});

it('broadcasts refreshed statistics after creating a product', function () {
    Event::fake();
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('products.store'), [
        'name' => 'Keyboard',
        'description' => 'Mechanical keyboard',
        'price' => 80,
        'stock' => 4,
    ])->assertRedirect(route('products.index'));

    Event::assertDispatched(DashboardStatsUpdated::class, function (DashboardStatsUpdated $event) {
        return $event->broadcastOn()[0] == new Channel('dashboard-stats')
            && $event->stats['product_count'] === 1
            && $event->stats['low_stock_count'] === 1;
    });
});
