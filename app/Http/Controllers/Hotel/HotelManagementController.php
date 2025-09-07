<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HotelManagementController extends Controller
{
    public function index()
    {
        $hotel = Auth::guard('hotel')->user();
        $hotel->load(['location', 'images', 'facilities']);
        
        return view('hotel.management.index', compact('hotel'));
    }

    public function edit()
    {
        $hotel = Auth::guard('hotel')->user();
        $hotel->load(['location', 'images', 'facilities']);
        $facilities = Facility::orderBy('category', 'asc')->orderBy('name', 'asc')->get();
        $locations = Location::where('status', 'Active')->orderBy('name', 'asc')->get();
        
        return view('hotel.management.edit', compact('hotel', 'facilities', 'locations'));
    }

    public function update(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        
        $request->validate([
            'description' => 'nullable|string|max:2000',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|regex:/^[0-9]{9}$/',
            'website' => 'nullable|url|max:255',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'location_id' => 'nullable|exists:locations,id',
            'map_url' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'additional_images' => 'nullable|array|max:10',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'description', 'address', 'website',
            'star_rating', 'location_id', 'latitude', 'longitude', 'map_url'
        ]);

        // Handle phone number with +94 prefix
        if ($request->filled('phone')) {
            $data['phone'] = '+94' . $request->phone;
        }

        // Parse Google Maps share link if provided
        if ($request->filled('map_url')) {
            // Try to resolve shortened URLs to get coordinates
            $mapUrl = $request->map_url;
            
            // For shortened URLs, try to follow redirects to get the full URL
            if (strpos($mapUrl, 'maps.app.goo.gl') !== false) {
                try {
                    // Follow redirects to get the full URL
                    $context = stream_context_create([
                        'http' => [
                            'method' => 'HEAD',
                            'follow_location' => true,
                            'max_redirects' => 5,
                            'timeout' => 10
                        ]
                    ]);
                    
                    $headers = @get_headers($mapUrl, 1, $context);
                    if ($headers && isset($headers['Location'])) {
                        $fullUrl = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
                        if (preg_match('/@(-?[0-9\.]+),(-?[0-9\.]+)/', $fullUrl, $matches)) {
                            $data['latitude'] = $matches[1];
                            $data['longitude'] = $matches[2];
                        }
                    }
                } catch (Exception $e) {
                    // If redirect resolution fails, just store the URL
                }
            } else {
                // Extract coordinates directly from the URL if possible
                if (preg_match('/@(-?[0-9\.]+),(-?[0-9\.]+)/', $mapUrl, $matches)) {
                    $data['latitude'] = $matches[1];
                    $data['longitude'] = $matches[2];
                }
            }
            
            // Store the map URL
            $data['map_url'] = $mapUrl;
        }
        // Handle main image upload
        if ($request->hasFile('main_image')) {
            // Delete old main image if exists
            if ($hotel->main_image) {
                Storage::disk('public')->delete($hotel->main_image);
            }
            
            $data['main_image'] = $request->file('main_image')->store('hotel/main-images', 'public');
        }

        // Update hotel details
        $hotel->update($data);

        // Update facilities
        if ($request->has('facilities')) {
            $hotel->facilities()->sync($request->facilities);
        } else {
            $hotel->facilities()->detach();
        }

        // Handle additional images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $index => $image) {
                $imagePath = $image->store('hotel/additional-images', 'public');
                
                $hotel->images()->create([
                    'image_path' => $imagePath,
                    'alt_text' => $hotel->name . ' - Image ' . ($index + 1),
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('hotel.management.index')
            ->with('success', 'Hotel information updated successfully!');
    }

    public function deleteImage($imageId)
    {
        $hotel = Auth::guard('hotel')->user();
        $image = $hotel->images()->find($imageId);
        
        if ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    public function deleteMainImage()
    {
        $hotel = Auth::guard('hotel')->user();
        
        if ($hotel->main_image) {
            Storage::disk('public')->delete($hotel->main_image);
            $hotel->update(['main_image' => null]);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    public function toggleStatus()
    {
        $hotel = Auth::guard('hotel')->user();
        $hotel->status = $hotel->status === 'Active' ? 'Inactive' : 'Active';
        $hotel->save();
        
        return redirect()->route('hotel.management.index')
            ->with('success', 'Hotel status updated to ' . $hotel->status);
    }
}
