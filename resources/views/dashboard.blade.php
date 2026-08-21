<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div
        class="py-12"
        x-data="{
            stats: @js($stats),
            live: window.dashboardLiveConnected === true || window.Echo?.connector?.pusher?.connection?.state === 'connected',
            formatCurrency(value) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
            },
            updateStats(event) {
                this.stats = event.detail;
                this.live = true;
            },
            markLive() {
                this.live = true;
            }
        }"
        x-init="window.addEventListener('dashboard-stats-updated', updateStats); window.addEventListener('dashboard-live-connected', markLive); const connection = window.Echo?.connector?.pusher?.connection; if (connection) { live = connection.state === 'connected'; connection.bind('state_change', ({ current }) => { live = current === 'connected'; }); }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 p-8 text-white shadow-lg">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-indigo-100">Overview</p>
                        <h1 class="mt-2 text-3xl font-bold">Welcome back!</h1>
                    </div>
                    <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <p class="text-sm text-indigo-100">Status</p>
                        <p class="text-lg font-semibold">{{ __('You\'re logged in!') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Tenants</p>
                    <p class="mt-4 text-4xl font-bold text-gray-900" x-text="stats.tenant_count"></p>
                    <a class="mt-5 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800" href="{{ route('tenants.index') }}">Manage tenants</a>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Products</p>
                    <p class="mt-4 text-4xl font-bold text-gray-900" x-text="stats.product_count"></p>
                    <a class="mt-5 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800" href="{{ route('products.index') }}">Open catalog</a>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Inventory value</p>
                    <p class="mt-4 text-4xl font-bold text-gray-900" x-text="formatCurrency(stats.inventory_value)"></p>
                    <p class="mt-5 text-sm text-gray-500">Price multiplied by stock</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Low stock</p>
                    <p class="mt-4 text-4xl font-bold text-amber-600" x-text="stats.low_stock_count"></p>
                    <p class="mt-5 text-sm text-gray-500">10 units or fewer</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-5 text-sm text-gray-600 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <span><span class="mr-2 inline-block h-2 w-2 rounded-full" :class="live ? 'bg-emerald-500' : 'bg-gray-300'"></span><span x-text="live ? 'Live updates connected' : 'Waiting for live updates'"></span></span>
                <span>Updated <time x-text="new Date(stats.updated_at).toLocaleTimeString()"></time></span>
            </div>
        </div>
    </div>
</x-app-layout>
