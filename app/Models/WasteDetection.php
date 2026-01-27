<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteDetection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waste_type',
        'category',
        'confidence',
        'points_awarded',
        'image_path',
        'latitude',
        'longitude',
        'location_name',
    ];

    protected $casts = [
        'confidence' => 'float',
        'points_awarded' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}