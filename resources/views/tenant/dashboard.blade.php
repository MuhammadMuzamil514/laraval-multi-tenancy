<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="bg-white shadow rounded-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900">Tenant Dashboard</h1>
            <div class="mt-6 space-y-3 text-gray-700">
                <p><strong>Tenant:</strong> {{ $tenant_name }}</p>
                <p><strong>Tenant ID:</strong> {{ $tenant_id }}</p>
                <p><strong>Plan:</strong> {{ $tenant_plan }}</p>
            </div>
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded border p-4">
                    <p class="text-sm text-gray-500">Products</p>
                    <p class="mt-1 text-3xl font-bold">{{ $product_count }}</p>
                </div>
                <div class="rounded border p-4">
                    <p class="text-sm text-gray-500">Inventory value</p>
                    <p class="mt-1 text-3xl font-bold">${{ number_format($inventory_value, 2) }}</p>
                </div>
            </div>
            <a class="mt-8 inline-block rounded bg-gray-900 px-4 py-2 font-semibold text-white" href="{{ route('tenant.products.index', ['tenantDomain' => $tenant_id]) }}">Manage products</a>
        </div>
    </div>
</body>
</html>
