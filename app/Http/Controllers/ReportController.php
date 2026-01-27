<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Auth::user()->reports()
            ->latest()
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'waste_type' => 'required|in:plastic,ewaste,organic,mixed',
            'severity' => 'required|in:low,medium,high',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_name' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Save image if provided
        $imagePath = null;
        if ($request->image) {
            $image = $request->image;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'report_' . Str::uuid() . '.jpg';
            $path = 'reports/' . $imageName;
            Storage::disk('public')->put($path, base64_decode($image));
            $imagePath = $path;
        }

        $report = Report::create([
            'user_id' => $user->id,
            'waste_type' => $request->waste_type,
            'severity' => $request->severity,
            'description' => $request->description,
            'image_path' => $imagePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_name' => $request->location_name ?? 'Dar es Salaam',
            'status' => 'pending',
        ]);

        // Award points for reporting
        $points = match($request->severity) {
            'low' => 25,
            'medium' => 50,
            'high' => 75,
            default => 25,
        };

        $user->addPoints($points, 'Filed waste report');
        $user->increment('total_reports');

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully!',
            'report' => $report,
            'points_awarded' => $points,
        ]);
    }

    public function show(Report $report)
    {
        // Check authorization
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    public function getMapData()
    {
        $reports = Report::with('user:id,name')
            ->where('status', 'pending')
            ->latest()
            ->take(100)
            ->get();

        return response()->json([
            'success' => true,
            'reports' => $reports->map(function($report) {
                return [
                    'id' => $report->id,
                    'waste_type' => $report->waste_type,
                    'severity' => $report->severity,
                    'latitude' => $report->latitude,
                    'longitude' => $report->longitude,
                    'location_name' => $report->location_name,
                    'description' => $report->description,
                    'user_name' => $report->user->name,
                    'created_at' => $report->created_at->format('Y-m-d H:i'),
                ];
            }),
        ]);
    }
}