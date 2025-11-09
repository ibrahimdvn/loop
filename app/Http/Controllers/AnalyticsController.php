<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->middleware('auth');
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show analytics dashboard
     */
    public function index()
    {
        $overview = $this->analyticsService->getPlatformOverview();
        $engagement = $this->analyticsService->getUserEngagement('7d');
        $contentPerformance = $this->analyticsService->getContentPerformance('7d');
        $userGrowth = $this->analyticsService->getUserGrowth('30d');
        $realTime = $this->analyticsService->getRealTimeMetrics();

        return view('analytics.dashboard', compact(
            'overview',
            'engagement', 
            'contentPerformance',
            'userGrowth',
            'realTime'
        ));
    }

    /**
     * Get platform overview API
     */
    public function overview()
    {
        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getPlatformOverview()
        ]);
    }

    /**
     * Get user engagement metrics API
     */
    public function engagement(Request $request)
    {
        $period = $request->get('period', '7d');
        
        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getUserEngagement($period)
        ]);
    }

    /**
     * Get content performance API
     */
    public function contentPerformance(Request $request)
    {
        $period = $request->get('period', '7d');
        
        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getContentPerformance($period)
        ]);
    }

    /**
     * Get user growth API
     */
    public function userGrowth(Request $request)
    {
        $period = $request->get('period', '30d');
        
        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getUserGrowth($period)
        ]);
    }

    /**
     * Get real-time metrics API
     */
    public function realTime()
    {
        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getRealTimeMetrics()
        ]);
    }

    /**
     * Get user-specific analytics
     */
    public function userAnalytics($userId)
    {
        // Check if user can view analytics for this user
        if (Auth::id() != $userId && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getUserAnalytics($userId)
        ]);
    }

    /**
     * Clear analytics cache
     */
    public function clearCache()
    {
        $this->analyticsService->clearCache();
        
        return response()->json([
            'success' => true,
            'message' => 'Analytics cache cleared successfully'
        ]);
    }
}
