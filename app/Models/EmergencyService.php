<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'address',
        'phone',
        'emergency_phone',
        'latitude',
        'longitude',
        'google_place_id',
        'is_24_7',
        'operating_hours',
    ];

    protected $casts = [
        'is_24_7' => 'boolean',
        'operating_hours' => 'array',
    ];

    public function scopeNearby($query, $lat, $lng, $radius = 5)
    {
        // Radius in kilometers
        $haversine = "(6371 * acos(cos(radians($lat)) 
                     * cos(radians(latitude)) 
                     * cos(radians(longitude) - radians($lng)) 
                     + sin(radians($lat)) 
                     * sin(radians(latitude))))";
        
        return $query->selectRaw("*, $haversine AS distance")
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance');
    }

    public function getDistanceAttribute()
    {
        return round($this->attributes['distance'] ?? 0, 1);
    }
}
