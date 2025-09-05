<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Convert a stored image path to a path relative to the "public" disk,
     * so we can safely call Storage::disk('public')->delete($path).
     * Handles:
     *  - backslashes -> forward slashes
     *  - "public/" or "app/public/" prefixes
     *  - "storage/" (URL-style) -> remove it
     *  - full URLs / absolute paths -> return null (we won't try to delete)
     */
    private function normalizePublicDiskPath(?string $path): ?string
    {
        if (!$path) return null;

        $p = str_replace('\\', '/', $path);

        // If it's a URL or an absolute path, don't attempt filesystem delete.
        if (preg_match('#^https?://#', $p) || str_starts_with($p, '/')) {
            return null;
        }

        // Strip common prefixes to make it relative to "public" disk
        if (str_starts_with($p, 'public/'))      $p = substr($p, 7);
        if (str_starts_with($p, 'app/public/'))  $p = substr($p, 11);
        if (str_starts_with($p, 'storage/'))     $p = substr($p, 8); // from URL style to disk path

        return $p;
    }

    // Show all activities
    public function index(Request $request)
    {
        $status = $request->get('status'); // 'all'|'active'|'inactive'
        $search = $request->get('q');

        $query = Activity::query();

        if ($status && in_array(strtolower($status), ['active', 'inactive'])) {
            $query->where('status', ucfirst($status));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        $activities = $query->orderBy('id')->paginate(10)->withQueryString();
        
        return view('admin.activities', compact('activities'));
    }

    // Show the form to create (not used if you use the modal)
    public function create()
    {
        return view('admin.activities.create');
    }

    // Store a new activity
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        // Save path relative to the "public" disk: e.g. "images/abc.jpg"
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('images', 'public')
            : null;

        Activity::create([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
            'image'       => $imagePath,
        ]);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity created successfully!');
    }

    // Update an activity
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
        ];

        if ($request->hasFile('image')) {
            // Delete old file if we can resolve a public-disk path
            if ($activity->image) {
                $old = $this->normalizePublicDiskPath($activity->image);
                if ($old) {
                    Storage::disk('public')->delete($old);
                }
            }
            // Store new file relative to "public" disk
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity updated successfully!');
    }

    // Delete an activity
    public function destroy(Activity $activity)
    {
        if ($activity->image) {
            $old = $this->normalizePublicDiskPath($activity->image);
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
        }

        $activity->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity deleted successfully!');
    }

    // Delete only the image of an activity (AJAX from the edit modal)
    public function destroyImage(Activity $activity)
    {
        if ($activity->image) {
            $old = $this->normalizePublicDiskPath($activity->image);
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $activity->update(['image' => null]);
        }

        return back()->with('success', 'Activity image removed.');
    }

    
}
