<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\HotelCredentialsMail;
use App\Models\Hotel;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status'); // 'all'|'active'|'inactive'
        $search = $request->get('q');

        // ⬇ Removed withCount/withSum('bookings') since Booking model isn't created yet
        $query = Hotel::query()->with('location');

        if ($status && in_array(strtolower($status), ['active', 'inactive'])) {
            $query->where('status', ucfirst($status));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%");
            });
        }

        $hotels    = $query->orderBy('id')->paginate(10)->withQueryString();
        $locations = Location::orderBy('name')->get();

        return view('admin.hotels', compact('hotels', 'locations'));
    }

    private function generateUniqueUsername() {
        do {
            // Generate a random username with format 'hotel_xxxxx' where x is alphanumeric
            $username = 'hotel_' . strtolower(Str::random(5));
        } while (Hotel::where('username', $username)->exists());
        
        return $username;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:hotels,email',
            'location_id' => 'required|exists:locations,id',
            'status'      => 'required|in:Active,Inactive',
        ]);

        // Generate random username and password
        $username = $this->generateUniqueUsername();
        $password = Str::password(12, true, true, true, false); // 12 chars with letters, numbers, symbols

        $base     = Str::slug($data['name']);
        $username = $this->uniqueUsername($base);
        $plain    = Str::random(10);

        $hotel = Hotel::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'location_id' => $data['location_id'],
            'status'      => $data['status'] ?? 'Active',
            'username'    => $username,
            'password'    => Hash::make($plain),
        ]);

        Mail::to($hotel->email)->send(new HotelCredentialsMail($hotel, $plain));

        return back()->with('success', 'Hotel created and credentials emailed.');
    }

    public function update(Request $request, Hotel $hotel)
    {
        // Do NOT validate 'email' here; we are not allowing email edits.
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'status'      => 'required|in:Active,Inactive',
        ]);

        // Force email to remain unchanged (even if someone tries to inject it)
        $data['email'] = $hotel->email;

        $hotel->update($data);

        return back()->with('success', 'Hotel updated successfully.');
    }


    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return back()->with('success', 'Hotel deleted.');
    }

    public function resetCredentials(Hotel $hotel)
    {
        $plain = Str::random(10);
        $hotel->password = Hash::make($plain);
        $hotel->save();

        Mail::to($hotel->email)->send(new HotelCredentialsMail($hotel, $plain));

        return back()->with('success', 'Credentials reset and emailed.');
    }

    private function uniqueUsername(string $base): string
    {
        $base = $base ?: 'hotel';
        $try  = $base;
        $i    = 1;
        while (Hotel::where('username', $try)->exists()) {
            $i++;
            $try = $base . $i;
        }
        return $try;
    }
}
