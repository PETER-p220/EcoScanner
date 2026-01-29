<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'waste_type',
        'priority',
        'status',
        'latitude',
        'longitude',
        'address',
        'image_path',
        'admin_notes',
        'verified_at',
        'resolved_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'verified_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        
        // Return placeholder based on waste type
        $placeholders = [
            'ewaste' => 'https://via.placeholder.com/400x300/8b5cf6/ffffff?text=E-Waste',
            'plastic' => 'https://via.placeholder.com/400x300/3b82f6/ffffff?text=Plastic',
            'organic' => 'https://via.placeholder.com/400x300/84cc16/ffffff?text=Organic',
            'hazardous' => 'https://via.placeholder.com/400x300/ef4444/ffffff?text=Hazardous',
            'general' => 'https://via.placeholder.com/400x300/64748b/ffffff?text=General+Waste',
        ];

        return $placeholders[$this->waste_type] ?? $placeholders['general'];
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'verified' => 'success',
            'investigating' => 'info',
            'resolved' => 'primary',
            'rejected' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get the priority icon.
     */
    public function getPriorityIconAttribute()
    {
        $icons = [
            'high' => '🔥',
            'medium' => '⚠️',
            'low' => 'ℹ️',
        ];

        return $icons[$this->priority] ?? 'ℹ️';
    }

    /**
     * Scope a query to only include reports of a given status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include reports of a given priority.
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include reports of a given waste type.
     */
    public function scopeWasteType($query, $type)
    {
        return $query->where('waste_type', $type);
    }

    /**
     * Scope a query to only include reports within a radius.
     */
    public function scopeNearby($query, $latitude, $longitude, $radius = 5)
    {
        // Using Haversine formula to calculate distance
        return $query->selectRaw("
            *,
            (
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )
            ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<', $radius)
        ->orderBy('distance');
    }
}