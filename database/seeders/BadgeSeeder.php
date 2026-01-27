<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Scan',
                'description' => 'Complete your first waste detection',
                'icon' => '🎯',
                'points_required' => 10,
                'criteria_type' => 'first_scan',
                'criteria_value' => 1,
            ],
            [
                'name' => 'E-Waste Hunter',
                'description' => 'Detect 5 e-waste items',
                'icon' => '⚡',
                'points_required' => 50,
                'criteria_type' => 'ewaste_count',
                'criteria_value' => 5,
            ],
            [
                'name' => 'Community Reporter',
                'description' => 'File your first waste report',
                'icon' => '📢',
                'points_required' => 25,
                'criteria_type' => 'total_reports',
                'criteria_value' => 1,
            ],
            [
                'name' => 'Scan Master',
                'description' => 'Complete 50 waste detections',
                'icon' => '🔥',
                'points_required' => 100,
                'criteria_type' => 'total_scans',
                'criteria_value' => 50,
            ],
            [
                'name' => 'Eco Champion',
                'description' => 'Reach 500 total points',
                'icon' => '🏆',
                'points_required' => 500,
                'criteria_type' => 'points',
                'criteria_value' => 500,
            ],
            [
                'name' => 'Plastic Fighter',
                'description' => 'Detect 20 plastic waste items',
                'icon' => '♻️',
                'points_required' => 75,
                'criteria_type' => 'total_scans',
                'criteria_value' => 20,
            ],
            [
                'name' => 'Report Champion',
                'description' => 'File 10 waste reports',
                'icon' => '📝',
                'points_required' => 150,
                'criteria_type' => 'total_reports',
                'criteria_value' => 10,
            ],
            [
                'name' => 'E-Waste Expert',
                'description' => 'Detect 20 e-waste items',
                'icon' => '💻',
                'points_required' => 200,
                'criteria_type' => 'ewaste_count',
                'criteria_value' => 20,
            ],
            [
                'name' => 'Elite Scanner',
                'description' => 'Complete 100 waste detections',
                'icon' => '⭐',
                'points_required' => 250,
                'criteria_type' => 'total_scans',
                'criteria_value' => 100,
            ],
            [
                'name' => 'Dar es Salaam Hero',
                'description' => 'Reach 1000 total points',
                'icon' => '🦸',
                'points_required' => 1000,
                'criteria_type' => 'points',
                'criteria_value' => 1000,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}