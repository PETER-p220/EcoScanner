<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
public function storeDetection(Request $request)
{
    $request->validate([
        'type'     => 'required|string',
        'category' => 'required|string',
        'points'   => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    $user->total_scans += 1;

    if ($request->category === 'ewaste') {
        $user->ewaste_count += 1;
    }

    $user->points += $request->points;
    $user->save();

    return response()->json([
        'success' => true,
        'new_points' => $user->points,
        'total_scans' => $user->total_scans,
        'ewaste_count' => $user->ewaste_count,
    ]);
}
}