<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', 'image', 'main_image'];

    /**
     * If you ever serialize Activity to arrays/JSON, include image_url automatically.
     * (Not required for Blade usage; handy for APIs.)
     */
    protected $appends = ['image_url'];

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'activity_location')
            ->withTimestamps();
    }

    /**
     * Normalized public URL for the activity's image.
     * Uses `image` column first, then falls back to `main_image`.
     * Handles:
     *  - Full URLs (http/https)
     *  - Absolute paths
     *  - Storage public disk paths like "public/activities/file.jpg" → "/storage/activities/file.jpg"
     *  - Empty value → placeholder asset
     */
    public function getImageUrlAttribute(): string
    {
        $raw = $this->image ?? $this->main_image;

        if (!$raw) {
            return asset('images/placeholder.jpg');
        }

        // Full URL or absolute path
        if (Str::startsWith($raw, ['http://', 'https://', '/'])) {
            return $raw;
        }

        // Strip "public/" prefix if saved via Storage public disk
        if (Str::startsWith($raw, 'public/')) {
            $raw = Str::after($raw, 'public/');
        }

        // Serve through the /storage symlink
        return asset('storage/' . ltrim($raw, '/'));
    }
}
