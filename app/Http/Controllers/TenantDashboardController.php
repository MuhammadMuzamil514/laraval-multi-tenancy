<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantDashboardController extends Controller
{
    public function index(): View
    {
        $tenant = tenant();

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'tenant_name' => $tenant?->name ?? 'Tenant',
            'tenant_id' => $tenant?->id,
            'tenant_plan' => $tenant?->data['plan'] ?? 'basic',
            'product_count' => Product::count(),
            'inventory_value' => (float) Product::query()->selectRaw('COALESCE(SUM(price * stock), 0) as total')->value('total'),
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Tenant $tenant)
    {
        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'tenant_name' => $tenant->name,
            'tenant_id' => $tenant->id,
            'tenant_plan' => $tenant->data['plan'] ?? 'basic',
        ]);
    }

    public function edit(Tenant $tenant)
    {
        //
    }

    public function update(Request $request, Tenant $tenant)
    {
        //
    }

    public function destroy(Tenant $tenant)
    {
        //
    }
}
