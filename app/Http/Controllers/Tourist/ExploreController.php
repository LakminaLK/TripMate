<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Location;
use App\Models\Hotel;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    // List activities (with optional search/location filter/sort)
    // app/Http/Controllers/Tourist/ExploreController.php
public function index(Request $request)
{
    $q         = trim((string) $request->get('q', ''));
    $location  = $request->get('location');
    $priceSort = $request->get('sort');

    $query = Activity::query()->with('locations');

    if ($q !== '') {
        $query->where(function ($x) use ($q) {
            $x->where('name', 'like', "%{$q}%")
              ->orWhere('description', 'like', "%{$q}%");
        });
    }
    if ($location) {
        $query->whereHas('locations', fn($z) => $z->where('locations.id', $location));
    }
    if ($priceSort === 'price_asc') {
        $query->orderBy('price', 'asc');
    } elseif ($priceSort === 'price_desc') {
        $query->orderBy('price', 'desc');
    } else {
        $query->latest('id');
    }

    $activities = $query->paginate(12)->withQueryString();
    $locations  = \App\Models\Location::orderBy('name')->get();

    return view('tourist.explore', [
        'activities' => $activities,
        'locations'  => $locations,
        'q'          => $q,
        'location'   => $location,
        'priceSort'  => $priceSort,
    ]);
}


    // Show a single activity + the locations linked to it
    public function showActivity(Activity $activity)
    {
        $activity->load('locations');

        return view('tourist.activity', [
            'activity'  => $activity,
            'locations' => $activity->locations,
        ]);
    }

    // Show all ACTIVE hotels for a given location
    public function hotelsByLocation(Location $location)
    {
        $hotels = Hotel::where('location_id', $location->id)
            ->where('status', 'Active')
            ->orderBy('name')
            ->paginate(12);

        return view('tourist.hotels', [
            'location' => $location,
            'hotels'   => $hotels,
        ]);
    }
}
