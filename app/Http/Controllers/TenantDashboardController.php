<?php

namespace App\Http\Controllers;

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
