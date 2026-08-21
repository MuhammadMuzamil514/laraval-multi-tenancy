<?php

namespace App\Http\Controllers;

use App\Events\DashboardStatsUpdated;
use App\Jobs\ProcessTenantSetupJob;
use App\Models\Tenant;
use App\Services\DashboardStats;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function index()
    {
        return view('tenants.index', [
            'tenants' => Tenant::with('domains')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', Rule::unique('domains', 'domain')],
        ]);

        $domain = Str::lower($validated['domain']);

        $data = [
            'name' => $validated['name'],
            'created_by' => auth()->id(),
        ];

        // If the tenants table has an explicit `name` column (older schemas),
        // insert via the query builder to ensure the NOT NULL column is populated.
        if (Schema::hasColumn('tenants', 'name')) {
            // Build insert payload that fills any NOT NULL columns with sensible defaults
            try {
                $columns = DB::select('SHOW COLUMNS FROM tenants');
                $insert = [];

                foreach ($columns as $col) {
                    $field = $col->Field;

                    if ($field === 'id') {
                        $insert['id'] = $domain;

                        continue;
                    }

                    if ($field === 'data') {
                        $insert['data'] = json_encode($data);

                        continue;
                    }

                    if ($field === 'created_at' || $field === 'updated_at') {
                        $insert[$field] = now();

                        continue;
                    }

                    // If column is NOT NULL and has no default, provide a fallback
                    $isNotNullNoDefault = ($col->Null === 'NO' && $col->Default === null);

                    if ($field === 'name') {
                        $insert['name'] = $validated['name'];
                    } elseif ($field === 'email') {
                        // Prefer domain if it looks like an email, otherwise synthesize
                        $insert['email'] = str_contains($domain, '@') ? $domain : ($domain.'@example.test');
                    } elseif ($field === 'password') {
                        $insert['password'] = bcrypt(Str::random(12));
                    } else {
                        if ($isNotNullNoDefault && ! array_key_exists($field, $insert)) {
                            $insert[$field] = '';
                        }
                    }
                }

                DB::table('tenants')->insert($insert);
                $tenant = Tenant::find($domain);
            } catch (\Throwable $e) {
                Log::warning('Direct insert into tenants failed, falling back to Eloquent', ['error' => $e->getMessage()]);
                $tenant = new Tenant;
                $tenant->id = $domain;
                $tenant->setAttribute('data', $data);
                $tenant->setAttribute('name', $validated['name']);
                $tenant->save();
            }
        } else {
            $tenant = new Tenant;
            $tenant->id = $domain;
            $tenant->setAttribute('data', $data);
            $tenant->setAttribute('name', $validated['name']);
            $tenant->save();
        }

        Log::info('Created tenant', ['id' => $tenant->id, 'data' => $data]);

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        ProcessTenantSetupJob::dispatch($tenant);
        DashboardStatsUpdated::dispatch(app(DashboardStats::class)->snapshot());

        return redirect()->route('tenants.index')->with('status', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant)
    {
        return view('tenants.show', ['tenant' => $tenant->load('domains')]);
    }

    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', ['tenant' => $tenant->load('domains')]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $tenant->update([
            'data' => array_merge($tenant->data ?? [], ['name' => $validated['name']]),
        ]);

        return redirect()->route('tenants.index');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        DashboardStatsUpdated::dispatch(app(DashboardStats::class)->snapshot());

        return redirect()->route('tenants.index');
    }
}
