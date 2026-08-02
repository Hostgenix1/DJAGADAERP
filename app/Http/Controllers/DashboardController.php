<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $service)
    {
    }

    public function index()
    {
        $stats = $this->service->getStats();
        $revenue = $this->service->getMonthlyRevenue();
        $pipeline = $this->service->getPipelineByStage();
        $activities = $this->service->getRecentActivity();
        $topCustomers = $this->service->getTopCustomers();

        return view('dashboard', compact('stats', 'revenue', 'pipeline', 'activities', 'topCustomers'));
    }
}
