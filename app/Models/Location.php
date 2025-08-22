<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'main_image'];

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_location')
        ->withTimestamps();
    }

    public function images()
{
    return $this->hasMany(LocationImage::class);
}

public function hotels()
{
    return $this->hasMany(Hotel::class);
}

}

