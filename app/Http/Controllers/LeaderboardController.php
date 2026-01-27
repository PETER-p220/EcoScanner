<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = User::orderBy('points', 'desc')
            ->take(50)
            ->get();

        $user = Auth::user();
        $userRank = User::where('points', '>', $user->points)->count() + 1;

        return view('leaderboard.index', compact('leaderboard', 'user', 'userRank'));
    }

    public function getTopUsers(Request $request)
    {
        $limit = $request->input('limit', 10);
        
        $users = User::orderBy('points', 'desc')
            ->take($limit)
            ->get()
            ->map(function($user, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $user->name,
                    'points' => $user->points,
                    'level' => $user->level,
                    'total_scans' => $user->total_scans,
                    'avatar' => $user->face_image_path 
                        ? Storage::url($user->face_image_path) 
                        : null,
                ];
            });

        return response()->json([
            'success' => true,
            'leaderboard' => $users,
        ]);
    }
}