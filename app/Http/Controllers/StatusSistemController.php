<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SystemStatusService;

class StatusSistemController extends Controller
{
    protected $statusService;

    public function __construct(SystemStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function index()
    {
        // Get real health check data
        $data = $this->statusService->getDetailedResults();

        $overallStatus = ($data['summary']['overall_status'] ?? 'failed') === 'ok' ? 'healthy' : 'unhealthy';
        $categories = $data['checks_by_category'] ?? [];

        // Map categories to view format - ensure all arrays exist
        $groupedResults = [
            'application' => $categories['system']['checks'] ?? [],
            'database' => $categories['database']['checks'] ?? [],
            'system' => $categories['storage']['checks'] ?? [],
            'cache_queue' => $categories['cache']['checks'] ?? []
        ];

        return view('bantuan.status-sistem', compact(
            'overallStatus',
            'groupedResults'
        ));
    }

    public function refresh()
    {
        // Mock refresh - just redirect with success message
        return redirect()->route('bantuan.status-sistem')
            ->with('success', 'Status sistem telah dikemaskini.');
    }
}
