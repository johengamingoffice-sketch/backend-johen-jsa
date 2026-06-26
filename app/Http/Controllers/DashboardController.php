<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function index(Request $request)
    {
        $stats = $this->dashboardService->getStats();
        $availableYears = $this->dashboardService->getAvailableYears();
        $selectedYear = $request->integer('year', $availableYears[0] ?? now()->year);
        $payrolls = $this->dashboardService->getPayrollsByYear($selectedYear);

        return view('dashboard.index', compact('stats', 'availableYears', 'selectedYear', 'payrolls'));
    }
}
