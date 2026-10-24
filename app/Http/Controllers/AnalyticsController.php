<?php

namespace App\Http\Controllers;

use App\Services\Analytics\AnalyticsService;

class AnalyticsController extends Controller
{
    /**
     * Analytics resumidos para utilizador normal.
     */
    public function summary(AnalyticsService $analyticsService)
    {
        return response()->json([
            'data' => $analyticsService->userSummary(),
        ]);
    }

    /**
     * Analytics completos apenas para admin.
     */
    public function admin(AnalyticsService $analyticsService)
    {
        return response()->json([
            'data' => $analyticsService->adminAnalytics(),
        ]);
    }
}
