<?php

// app/Http/Controllers/Tourist/LocationController.php
namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Support\Facades\Schema;

class LocationController extends Controller
{
    public function show(\App\Models\Location $location)
{
    $query = $location->hotels()->orderBy('name');

    // Only add the filter if the column exists (safe on any DB)
    if (Schema::hasColumn('hotels', 'status')) {
        $query->where('status', 'Active');
    }

    $hotels = $query->get();

    return view('tourist.location', [
        'location' => $location,
        'hotels'   => $hotels,
    ]);
}

}
