<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'face_encoding',
        'face_image_path',
        'points',
        'level',
        'total_scans',
        'total_reports',
        'ewaste_count',
        'location',
        'language_preference',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'face_encoding' => 'array',
        'points' => 'integer',
        'level' => 'integer',
        'total_scans' => 'integer',
        'total_reports' => 'integer',
        'ewaste_count' => 'integer',
    ];

    public function wasteDetections()
    {
        return $this->hasMany(WasteDetection::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function addPoints($points, $reason = null)
    {
        $this->points += $points;
        
        // Check for level up
        $requiredPoints = $this->level * 100;
        if ($this->points >= $requiredPoints) {
            $this->level++;
        }
        
        $this->save();

        // Log point transaction
        $this->pointTransactions()->create([
            'points' => $points,
            'reason' => $reason,
        ]);

        return $this;
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function getProgressToNextLevelAttribute()
    {
        $currentLevelPoints = ($this->level - 1) * 100;
        $nextLevelPoints = $this->level * 100;
        $progress = (($this->points - $currentLevelPoints) / ($nextLevelPoints - $currentLevelPoints)) * 100;
        
        return min(100, max(0, $progress));
    }

    public function getPointsToNextLevelAttribute()
    {
        $nextLevelPoints = $this->level * 100;
        return max(0, $nextLevelPoints - $this->points);
    }
}