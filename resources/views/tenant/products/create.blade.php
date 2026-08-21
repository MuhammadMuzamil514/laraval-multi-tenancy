<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-2xl px-4 py-10">
        <a class="text-sm text-slate-500" href="{{ route('tenant.products.index', ['tenantDomain' => tenant('id')]) }}">Back to products</a>
        <h1 class="mt-3 text-3xl font-bold">Add product</h1>
        <form class="mt-8 space-y-5 rounded-lg bg-white p-6 shadow" method="POST" action="{{ route('tenant.products.store', ['tenantDomain' => tenant('id')]) }}">
            @csrf
            @include('tenant.products.form')
            <button class="rounded bg-slate-900 px-4 py-2 font-semibold text-white" type="submit">Save product</button>
        </form>
    </main>
</body>
</html>
