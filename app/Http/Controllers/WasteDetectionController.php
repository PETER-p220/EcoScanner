<?php

namespace App\Http\Controllers;

use App\Models\WasteDetection;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WasteDetectionController extends Controller
{
    private $wasteCategories = [
        'bottle' => ['category' => 'plastic', 'points' => 5],
        'cup' => ['category' => 'plastic', 'points' => 4],
        'cell phone' => ['category' => 'ewaste', 'points' => 25],
        'laptop' => ['category' => 'ewaste', 'points' => 30],
        'keyboard' => ['category' => 'ewaste', 'points' => 15],
        'banana' => ['category' => 'organic', 'points' => 3],
        'apple' => ['category' => 'organic', 'points' => 3],
        'orange' => ['category' => 'organic', 'points' => 3],
        'backpack' => ['category' => 'textile', 'points' => 10],
    ];

    public function store(Request $request)
    {
        $request->validate([
            'waste_type' => 'required|string',
            'confidence' => 'required|numeric|between:0,1',
            'image' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $wasteType = strtolower($request->waste_type);
        
        // Get category and points
        $wasteInfo = $this->wasteCategories[$wasteType] ?? [
            'category' => 'mixed',
            'points' => 2
        ];

        // Save image if provided
        $imagePath = null;
        if ($request->image) {
            $image = $request->image;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'waste_' . Str::uuid() . '.png';
            $path = 'waste-images/' . $imageName;
            Storage::disk('public')->put($path, base64_decode($image));
            $imagePath = $path;
        }

        // Create detection record
        $detection = WasteDetection::create([
            'user_id' => $user->id,
            'waste_type' => $wasteType,
            'category' => $wasteInfo['category'],
            'confidence' => $request->confidence,
            'points_awarded' => $wasteInfo['points'],
            'image_path' => $imagePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_name' => $request->location_name ?? 'Dar es Salaam',
        ]);

        // Award points
        $user->addPoints($wasteInfo['points'], "Detected: {$wasteType}");
        
        // Update user stats
        $user->increment('total_scans');
        if ($wasteInfo['category'] === 'ewaste') {
            $user->increment('ewaste_count');
        }

        // Check and award badges
        $this->checkBadges($user);

        return response()->json([
            'success' => true,
            'detection' => $detection,
            'points_awarded' => $wasteInfo['points'],
            'user_points' => $user->points,
            'user_level' => $user->level,
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        
        $detections = $user->wasteDetections()
            ->latest()
            ->paginate(20);

        $stats = [
            'total_detections' => $user->wasteDetections()->count(),
            'by_category' => $user->wasteDetections()
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get(),
            'total_points_earned' => $user->wasteDetections()->sum('points_awarded'),
        ];

        return view('waste.index', compact('detections', 'stats'));
    }

    private function checkBadges($user)
    {
        $badges = Badge::all();
        
        foreach ($badges as $badge) {
            // Check if user already has badge
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $earned = false;

            switch ($badge->criteria_type) {
                case 'first_scan':
                    $earned = $user->total_scans >= 1;
                    break;
                case 'total_scans':
                    $earned = $user->total_scans >= $badge->criteria_value;
                    break;
                case 'ewaste_count':
                    $earned = $user->ewaste_count >= $badge->criteria_value;
                    break;
                case 'total_reports':
                    $earned = $user->total_reports >= $badge->criteria_value;
                    break;
                case 'points':
                    $earned = $user->points >= $badge->criteria_value;
                    break;
            }

            if ($earned) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                $user->addPoints($badge->points_required, "Badge earned: {$badge->name}");
            }
        }
    }
}