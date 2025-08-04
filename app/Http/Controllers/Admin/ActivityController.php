<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    // Show all activities (index)
    public function index()
    {
        $activities = Activity::all(); // Fetch all activities from the database
        return view('admin.activities', compact('activities'));
    }

    // Show the form to create a new activity
    public function create()
    {
        return view('admin.activities.create'); // Render the create activity view
    }

    // Store a new activity in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle file upload for activity image (if uploaded)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
        }

        // Store the new activity
        Activity::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'image' => $imagePath, // Save image path in the database
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully!');
    }

    // Update an existing activity in the database
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle file upload for activity image (if uploaded)
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($activity->image) {
                // Remove old image from storage
                Storage::delete('public/images/' . $activity->image);
            }

            // Store the new image and update the activity image path
            $imagePath = $request->file('image')->store('public/images');
            $activity->image = $imagePath; // Update image path in the database
        }

        // Update the activity
        $activity->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully!');
    }

    // Delete an activity from the database
    public function destroy(Activity $activity)
    {
        // Delete the associated image file if it exists
        if ($activity->image) {
            // Ensure the image exists before attempting to delete
            if (Storage::exists('public/images/' . $activity->image)) {
                Storage::delete('public/images/' . $activity->image); // Delete the file using Storage facade
            }
        }

        // Delete the activity
        $activity->delete();

        // Redirect to activities list with success message
        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully!');
    }
}
