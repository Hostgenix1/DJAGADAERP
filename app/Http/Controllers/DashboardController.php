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
        $this->authorize('view-dashboard');

        $period = in_array(request('period'), ['month', 'quarter', 'year', 'all']) ? request('period') : 'month';

        $stats = $this->service->getStats();
        $pnl = $this->service->getPnl($period);
        $shipmentStats = $this->service->getShipmentStats();
        $revenue = $this->service->getMonthlyRevenue();
        $cashFlow = $this->service->getCashFlow();
        $pipeline = $this->service->getPipelineByStage();
        $activities = $this->service->getRecentActivity();
        $topCustomers = $this->service->getTopCustomers();
        $topProducts = $this->service->getTopSellingProducts();
        $revenueByCurrency = $this->service->getRevenueByCurrency();
        $outstandingByCurrency = $this->service->getOutstandingByCurrency();
        $payablesSummary = $this->service->getPayablesSummary();
        $payablesByCurrency = $this->service->getPayablesByCurrency();
        $recentBills = $this->service->getRecentBills();
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        return view('dashboard', compact(
            'stats', 'pnl', 'shipmentStats', 'period', 'revenue', 'cashFlow', 'pipeline', 'activities',
            'topCustomers', 'topProducts', 'revenueByCurrency', 'outstandingByCurrency',
            'payablesSummary', 'payablesByCurrency', 'recentBills', 'defaultCurrency'
        ));
    }
}
