<?php

namespace App\Http\Controllers;

use App\Models\EmergencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmergencyServiceController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $currentLat = $request->input('latitude') ?? $request->input('lat') ?? 6.927079; // Default to center of Sri Lanka
        $currentLng = $request->input('longitude') ?? $request->input('lng') ?? 79.861244;
        $radius = $request->input('radius', 5); // Default 5km radius

        // Convert radius to meters for Google Places API
        $radiusInMeters = $radius * 1000;

        // First get local database results
        $query = EmergencyService::query();
        if ($type) {
            $query->where('type', $type);
        }
        if ($currentLat && $currentLng && is_numeric($currentLat) && is_numeric($currentLng)) {
            $query->nearby($currentLat, $currentLng, $radius);
        }
        $localServices = $query->get();

        // Then get Google Places results
        $placeType = $this->getGooglePlaceType($type);
        if ($placeType && $currentLat && $currentLng && is_numeric($currentLat) && is_numeric($currentLng)) {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
                'location' => $currentLat . ',' . $currentLng,
                'radius' => $radiusInMeters,
                'type' => $placeType,
                'key' => config('services.google.maps_api_key')
            ]);

            $results = $response->json()['results'] ?? [];
            
            // Transform Google Places results
            $googleServices = collect($results)->map(function ($place) use ($type, $currentLat, $currentLng) {
                $placeLat = $place['geometry']['location']['lat'];
                $placeLng = $place['geometry']['location']['lng'];
                
                // Calculate distance
                $distance = $this->calculateDistance($currentLat, $currentLng, $placeLat, $placeLng);
                
                return (object) [
                    'id' => $place['place_id'],
                    'name' => $place['name'],
                    'type' => $type,
                    'address' => $place['vicinity'] ?? $place['formatted_address'] ?? '',
                    'latitude' => $placeLat,
                    'longitude' => $placeLng,
                    'rating' => $place['rating'] ?? null,
                    'phone' => null, // Phone numbers require separate details API call
                    'distance' => $distance,
                    'is_24_7' => in_array('open_24_hours', $place['types'] ?? []),
                    'is_google_place' => true,
                    'photos' => $place['photos'] ?? []
                ];
            });

            // Merge local and Google results
            $services = $localServices->concat($googleServices)->sortBy('distance');
        } else {
            $services = $localServices;
        }

        // Ensure all services are objects/models
        $services = $services->map(function ($service) {
            if (is_array($service)) {
                return (object) $service;
            }
            return $service;
        });

        return view('emergency-services.index', [
            'services' => $services,
            'selectedType' => $type,
            'currentLat' => $currentLat,
            'currentLng' => $currentLng,
            'radius' => $radius
        ]);
    }

    public function show($id)
    {
        // Check if it's a Google Place ID
        if (strpos($id, 'place_') === 0) {
            // Get details from Google Places API
            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $id,
                'fields' => 'name,formatted_phone_number,formatted_address,geometry,opening_hours,website,rating,reviews',
                'key' => config('services.google.maps_api_key')
            ]);

            $place = $response->json()['result'];
            $service = [
                'id' => $id,
                'name' => $place['name'],
                'type' => request('type', 'hospital'),
                'address' => $place['formatted_address'],
                'phone' => $place['formatted_phone_number'] ?? null,
                'latitude' => $place['geometry']['location']['lat'],
                'longitude' => $place['geometry']['location']['lng'],
                'website' => $place['website'] ?? null,
                'rating' => $place['rating'] ?? null,
                'is_24_7' => isset($place['opening_hours']) && collect($place['opening_hours']['periods'] ?? [])->every(function ($period) {
                    return $period['open']['time'] === '0000' && (!isset($period['close']) || $period['close']['time'] === '0000');
                }),
                'operating_hours' => $place['opening_hours']['weekday_text'] ?? null,
                'is_google_place' => true,
                'reviews' => $place['reviews'] ?? []
            ];
        } else {
            // Get from local database
            $service = EmergencyService::findOrFail($id);
        }

        return view('emergency-services.show', compact('service'));
    }

    private function getGooglePlaceType($selectedType)
    {
        return match ($selectedType) {
            'hospital' => 'hospital',
            'police' => 'police',
            'pharmacy' => 'pharmacy',
            'fire_station' => 'fire_station',
            default => null
        };
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Earth radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }
}
