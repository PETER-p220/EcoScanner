<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        $query = Report::with('user')->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('type')) {
            $query->where('waste_type', $request->type);
        }

        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'priority':
                    $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
                    break;
                default:
                    $query->latest();
            }
        }

        $reports = $query->paginate(12);

        // Statistics
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $verifiedReports = Report::where('status', 'verified')->count();
        $myReports = Report::where('user_id', Auth::id())->count();

        return view('reports.index', compact(
            'reports',
            'totalReports',
            'pendingReports',
            'verifiedReports',
            'myReports'
        ));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'waste_type' => 'required|in:ewaste,plastic,organic,hazardous,general',
            'priority' => 'required|in:low,medium,high',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'waste_type' => $validated['waste_type'],
            'priority' => $validated['priority'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'] ?? null,
            'status' => 'pending',
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'report_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('reports', $imageName, 'public');
            $data['image_path'] = $path;
        }

        $report = Report::create($data);

        // Award points to user
        $user = Auth::user();
        if (method_exists($user, 'addPoints')) {
            $user->addPoints(10, 'Submitted report');
        } else {
            $user->points += 10;
            $user->total_reports += 1;
            $user->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Report submitted successfully!',
                'redirect' => route('reports.show', $report->id),
                'points_earned' => 10,
            ]);
        }

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Report submitted successfully! You earned 10 points.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $report->load('user');
        return view('reports.show', compact('report'));
    }

    /**
     * Update the specified report.
     */
    public function update(Request $request, Report $report)
    {
        // Only allow admin or report owner to update
        if (Auth::id() !== $report->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,verified,investigating,resolved,rejected',
            'admin_notes' => 'sometimes|string|max:1000',
        ]);

        $report->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Report updated successfully!',
            ]);
        }

        return back()->with('success', 'Report updated successfully!');
    }

    /**
     * Remove the specified report from storage.
     */
    public function destroy(Report $report)
    {
        // Only allow admin or report owner to delete
        if (Auth::id() !== $report->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($report->image_path) {
            Storage::disk('public')->delete($report->image_path);
        }

        $report->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Report deleted successfully!',
            ]);
        }

        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully!');
    }

    /**
     * Get map data for reports (for map view).
     */
    public function getMapData(Request $request)
    {
        $query = Report::with('user:id,name');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('waste_type', $request->type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $reports = $query->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
                'waste_type' => $report->waste_type,
                'priority' => $report->priority,
                'status' => $report->status,
                'user_name' => $report->user->name ?? 'Anonymous',
                'created_at' => $report->created_at->format('M d, Y'),
                'image_url' => $report->image_path ? Storage::url($report->image_path) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'reports' => $reports,
        ]);
    }
}