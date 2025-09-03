<?php

namespace App\Http\Controllers\Backend;

use App\Domains\Ai\Models\Event;
use Carbon\Carbon;

/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        try {
            // Get event statistics
            $totalEvents = Event::count();
            $upcomingEvents = Event::where('date', '>=', Carbon::today())->count();
            $thisMonthEvents = Event::whereMonth('created_at', Carbon::now()->month)->count();
            
            // Get recent events (last 5)
            $recentEvents = Event::orderBy('created_at', 'desc')->take(5)->get();
            
            // Get upcoming events (next 3)
            $nextEvents = Event::where('date', '>=', Carbon::today())
                ->orderBy('date', 'asc')
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            // Fallback if Event model has issues
            $totalEvents = 0;
            $upcomingEvents = 0;
            $thisMonthEvents = 0;
            $recentEvents = collect([]);
            $nextEvents = collect([]);
        }
        
        // PDF processing statistics - use fallback values since model doesn't exist
        $totalPdfs = 0;
        $processedPdfs = 0;
        $pendingPdfs = 0;
        $recentPdfs = collect([]);
        
        return view('backend.dashboard', compact(
            'totalEvents',
            'upcomingEvents', 
            'thisMonthEvents',
            'recentEvents',
            'nextEvents',
            'totalPdfs',
            'processedPdfs',
            'pendingPdfs',
            'recentPdfs'
        ));
    }
}
