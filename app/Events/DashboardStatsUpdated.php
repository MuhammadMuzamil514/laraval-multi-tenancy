<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardStatsUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  array{tenant_count: int, product_count: int, inventory_value: float, low_stock_count: int, updated_at: string}  $stats
     */
    public function __construct(public array $stats) {}

    public function broadcastOn(): array
    {
        return [new Channel('dashboard-stats')];
    }

    public function broadcastAs(): string
    {
        return 'dashboard.stats.updated';
    }

    /**
     * @return array{tenant_count: int, product_count: int, inventory_value: float, low_stock_count: int, updated_at: string}
     */
    public function broadcastWith(): array
    {
        return $this->stats;
    }
}
