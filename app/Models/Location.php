<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'main_image',
        'additional_description',
        'additional_images', // JSON/CSV/newline list supported
        'things',            // JSON/newline list supported
        'latitude',
        'longitude',
        'google_place_id',
        'status',
    ];

    /**
     * If you store JSON in additional_images/things, these casts help.
     * (Safe even if you sometimes store plain strings; accessors below handle both.)
     */
    protected $casts = [
        'additional_images' => 'array',
        'things'            => 'array',
    ];

    /**
     * Include these virtual attributes when serializing (handy for APIs).
     */
    protected $appends = ['image_url', 'gallery_images', 'things_list'];

    /* =========================
     |  Relationships
     ========================= */

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_location')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(LocationImage::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    /* =========================
     |  Accessors
     ========================= */

    /**
     * Normalized public URL for the main image.
     */
    public function getImageUrlAttribute(): string
    {
        $raw = $this->main_image;

        if (!$raw) {
            return asset('images/placeholder.jpg');
        }

        // Full URL or absolute path
        if (Str::startsWith($raw, ['http://', 'https://', '/'])) {
            return $raw;
        }

        // Strip "public/" if saved via Storage public disk
        if (Str::startsWith($raw, 'public/')) {
            $raw = Str::after($raw, 'public/');
        }

        // Serve via /storage symlink
        return asset('storage/' . ltrim($raw, '/'));
    }

    /**
     * Array of normalized URLs for the gallery images.
     * Accepts: JSON array, PHP array, comma/newline-separated string.
     */
    public function getGalleryImagesAttribute(): array
    {
        // Use the proper images relationship
        return $this->images()
            ->get()
            ->map(function($img) {
                $path = $img->path;
                if (!$path) return null;
                
                // Full URL or absolute path
                if (Str::startsWith($path, ['http://', 'https://', '/'])) {
                    return $path;
                }
                
                // Strip "public/" if saved via Storage public disk
                if (Str::startsWith($path, 'public/')) {
                    $path = Str::after($path, 'public/');
                }
                
                // Serve via /storage symlink
                return asset('storage/' . ltrim($path, '/'));
            })
            ->filter()
            ->values()
            ->all();

        return array_values(array_filter(array_map($normalize, $raw ?: [])));
    }

    /**
     * Turns "things" into a clean bullet list.
     * Accepts: JSON array, PHP array, newline-separated string.
     */
    public function getThingsListAttribute(): array
    {
        $raw = $this->things;

        if (is_array($raw)) {
            $arr = $raw;
        } else {
            $decoded = json_decode($raw ?? '', true);
            $arr = is_array($decoded)
                ? $decoded
                : preg_split('/\r\n|\r|\n/', (string) $raw, -1, PREG_SPLIT_NO_EMPTY);
        }

        // Trim and remove empties
        return array_values(array_filter(array_map('trim', $arr ?: [])));
    }
}
