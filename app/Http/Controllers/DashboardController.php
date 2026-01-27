<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WasteDetection;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Load user relationships
        $user->load(['badges', 'wasteDetections' => function($query) {
            $query->latest()->take(10);
        }]);

        // Global statistics
        $globalStats = [
            'total_scans' => WasteDetection::count(),
            'total_reports' => Report::count(),
            'total_points' => User::sum('points'),
            'active_users' => User::whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->subDays(7))
                ->count(),
        ];

        // User's recent activity
        $recentDetections = $user->wasteDetections()
            ->latest()
            ->take(5)
            ->get();

        $recentReports = $user->reports()
            ->latest()
            ->take(5)
            ->get();

        // Leaderboard
        $leaderboard = User::orderBy('points', 'desc')
            ->take(10)
            ->get();

        // User's rank
        $userRank = User::where('points', '>', $user->points)->count() + 1;

        return view('dashboard.index', compact(
            'user',
            'globalStats',
            'recentDetections',
            'recentReports',
            'leaderboard',
            'userRank'
        ));
    }

    public function getStats()
    {
        $user = Auth::user();

        return response()->json([
            'user' => [
                'points' => $user->points,
                'level' => $user->level,
                'total_scans' => $user->total_scans,
                'total_reports' => $user->total_reports,
                'ewaste_count' => $user->ewaste_count,
                'progress' => $user->progress_to_next_level,
                'points_to_next_level' => $user->points_to_next_level,
            ],
            'badges' => $user->badges()->get(),
        ]);
    }
}