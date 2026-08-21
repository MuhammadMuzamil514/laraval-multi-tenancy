<?php

namespace App\Http\Controllers;

use App\Services\DashboardStats;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardStats $dashboardStats) {}

    public function __invoke(): View
    {
        return view('dashboard', [
            'stats' => $this->dashboardStats->snapshot(),
        ]);
    }
}
