<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Tenant;

class DashboardStats
{
    /**
     * @return array{tenant_count: int, product_count: int, inventory_value: float, low_stock_count: int, updated_at: string}
     */
    public function snapshot(): array
    {
        return [
            'tenant_count' => Tenant::count(),
            'product_count' => Product::count(),
            'inventory_value' => (float) Product::query()->selectRaw('COALESCE(SUM(price * stock), 0) as total')->value('total'),
            'low_stock_count' => Product::where('stock', '<=', 10)->count(),
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
