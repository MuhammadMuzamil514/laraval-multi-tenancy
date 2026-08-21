<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant_name ?? 'Tenant' }} Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-5xl px-4 py-10">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <a class="text-sm text-slate-500 hover:text-slate-900" href="{{ route('tenant.dashboard', ['tenantDomain' => tenant('id')]) }}">{{ tenant('name') }} Dashboard</a>
                <h1 class="mt-2 text-3xl font-bold">Products</h1>
            </div>
            <a class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700" href="{{ route('tenant.products.create', ['tenantDomain' => tenant('id')]) }}">Add product</a>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Stock</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $product->name }}</td>
                            <td class="px-6 py-4">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">{{ $product->stock }}</td>
                            <td class="px-6 py-4 text-right">
                                <a class="mr-3 text-sm font-semibold text-blue-600" href="{{ route('tenant.products.edit', ['tenantDomain' => tenant('id'), 'product' => $product]) }}">Edit</a>
                                <form class="inline" method="POST" action="{{ route('tenant.products.destroy', ['tenantDomain' => tenant('id'), 'product' => $product]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm font-semibold text-red-600" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-10 text-center text-slate-500" colspan="4">No products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
