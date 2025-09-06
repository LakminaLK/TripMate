<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLocationRequest;
use App\Http\Requests\Admin\UpdateLocationRequest;
use App\Models\Activity;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LocationImage;


class LocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $status = strtolower($request->get('status', 'all'));
        
        $locations = Location::when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->with('activities:id,name')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $activities = Activity::orderBy('name')->get(['id','name']);
        return view('admin.locations', compact('locations','activities','search'));
    }

    public function store(StoreLocationRequest $request)
{
    $location = Location::create($request->only('name', 'description', 'status'));

    // main image
    if ($request->hasFile('main_image')) {
        $path = $request->file('main_image')->store('locations', 'public');
        $location->update(['main_image' => $path]);
    }

    // gallery images
    if ($request->hasFile('gallery')) {
        foreach ($request->file('gallery') as $file) {
            if (!$file) continue;
            $path = $file->store('locations/gallery', 'public');
            $location->images()->create(['path' => $path]);
        }
    }

    $location->activities()->sync($request->input('activities', []));
    return back()->with('success', 'Location created successfully.');
}

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->only('name', 'description', 'status'));    // replace main image if new one uploaded
    if ($request->hasFile('main_image')) {
        if ($location->main_image && Storage::disk('public')->exists($location->main_image)) {
            Storage::disk('public')->delete($location->main_image);
        }
        $path = $request->file('main_image')->store('locations', 'public');
        $location->update(['main_image' => $path]);
    }

    // append new gallery images
    if ($request->hasFile('gallery')) {
        foreach ($request->file('gallery') as $file) {
            if (!$file) continue;
            $path = $file->store('locations/gallery', 'public');
            $location->images()->create(['path' => $path]);
        }
    }

    $location->activities()->sync($request->input('activities', []));
    return back()->with('success', 'Location updated successfully.');
}

// DELETE one gallery image (AJAX)
public function destroyImage(Request $request, LocationImage $image)
{
    if ($image->path && Storage::disk('public')->exists($image->path)) {
        Storage::disk('public')->delete($image->path);
    }
    $image->delete();

    if ($request->expectsJson()) {
        return response()->json(['ok' => true, 'id' => (int)$image->id]);
    }
    return back();
}

// DELETE main image (AJAX)
public function destroyMainImage(Request $request, Location $location)
{
    if ($location->main_image && Storage::disk('public')->exists($location->main_image)) {
        Storage::disk('public')->delete($location->main_image);
    }
    $location->update(['main_image' => null]);

    if ($request->expectsJson()) {
        return response()->json(['ok' => true]);
    }
    return back();
}

    public function destroy(Location $location)
    {
        $location->activities()->detach();
        $location->delete();
        return back()->with('success', 'Location deleted successfully.');
    }
}
