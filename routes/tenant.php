<?php

declare(strict_types=1);

use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\TenantProductController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::domain('{tenantDomain}.localhost')
    ->middleware([
        'web',
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
        'auth',
    ])
    ->group(function () {
        Route::get('/', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
        Route::resource('products', TenantProductController::class)
            ->except(['show'])
            ->names('tenant.products');
    });
