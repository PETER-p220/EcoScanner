<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    public function storeDetection(Request $request)
    {
        // Log the incoming request
        Log::info('Scan detection attempt', [
            'authenticated' => Auth::check(),
            'session_id' => session()->getId(),
            'has_csrf' => $request->hasHeader('X-CSRF-TOKEN'),
            'method' => $request->method(),
            'ip' => $request->ip()
        ]);

        // Check authentication first
        if (!Auth::check()) {
            Log::warning('Scan attempted without authentication');
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated. Please login again.',
                'redirect' => route('login')
            ], 401);
        }

        // Validate request
        try {
            $request->validate([
                'type'     => 'required|string',
                'category' => 'required|string',
                'points'   => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid data',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            Log::info('Processing scan for user', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'type' => $request->type,
                'category' => $request->category,
                'points' => $request->points,
                'current_points' => $user->points,
                'current_scans' => $user->total_scans
            ]);

            // Update user stats
            $user->total_scans = ($user->total_scans ?? 0) + 1;

            if (strtolower($request->category) === 'ewaste') {
                $user->ewaste_count = ($user->ewaste_count ?? 0) + 1;
            }

            $user->points = ($user->points ?? 0) + $request->points;
            
            // Save to database
            $saved = $user->save();

            if (!$saved) {
                Log::error('Failed to save user data');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save data'
                ], 500);
            }

            Log::info('Scan saved successfully', [
                'user_id' => $user->id,
                'new_points' => $user->points,
                'new_scans' => $user->total_scans,
                'new_ewaste' => $user->ewaste_count
            ]);

            return response()->json([
                'success' => true,
                'new_points' => $user->points,
                'total_scans' => $user->total_scans,
                'ewaste_count' => $user->ewaste_count ?? 0,
                'message' => 'Detection saved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Scan detection exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}