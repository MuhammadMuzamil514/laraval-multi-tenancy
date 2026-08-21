<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium">Managed tenants</h3>
                        <a href="{{ route('tenants.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Create tenant
                        </a>
                    </div>

                    @if ($tenants->isEmpty())
                        <p class="text-gray-500">No tenants yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($tenants as $tenant)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold">{{ data_get($tenant, 'data.name') ?? $tenant->id }}</p>
                                            <p class="text-sm text-gray-500">{{ $tenant->domains->first()->domain ?? $tenant->id }}</p>
                                        </div>
                                        <a href="{{ route('tenants.show', $tenant) }}" class="text-sm text-indigo-600 hover:text-indigo-500">View</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

