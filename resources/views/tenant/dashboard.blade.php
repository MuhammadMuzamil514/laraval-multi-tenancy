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
        </div>
    </div>
</body>
</html>
