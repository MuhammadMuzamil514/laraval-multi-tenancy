<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <p><strong>ID:</strong> {{ $tenant->id }}</p>
                    <p><strong>Name:</strong> {{ data_get($tenant, 'data.name') ?? 'N/A' }}</p>
                    <p><strong>Domain:</strong> {{ $tenant->domains->first()->domain ?? 'N/A' }}</p>

                    <div class="flex gap-3">
                        <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
