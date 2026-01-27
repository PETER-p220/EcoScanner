<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waste_type',
        'severity',
        'description',
        'image_path',
        'latitude',
        'longitude',
        'location_name',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsResolved()
    {
        $this->status = 'resolved';
        $this->resolved_at = now();
        $this->save();
    }
}