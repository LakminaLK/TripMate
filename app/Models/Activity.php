<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', 'image'];


    public function locations()
    {
        return $this->belongsToMany(Location::class, 'activity_location')
        ->withTimestamps();
    }
}

