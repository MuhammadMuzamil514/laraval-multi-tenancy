<?php

namespace App\Http\Controllers;

use App\Events\DashboardStatsUpdated;
use App\Models\Product;
use App\Services\DashboardStats;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($validated);
        $this->broadcastDashboardStats();

        return redirect()->route('products.index')->with('status', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product): View
    {
        return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($validated);
        $this->broadcastDashboardStats();

        return redirect()->route('products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        $this->broadcastDashboardStats();

        return redirect()->route('products.index')->with('status', 'Product deleted successfully.');
    }

    private function broadcastDashboardStats(): void
    {
        try {
            DashboardStatsUpdated::dispatch(app(DashboardStats::class)->snapshot());
        } catch (BroadcastException $exception) {
            Log::warning('Dashboard stats broadcast unavailable.', [
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
