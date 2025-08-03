<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourist;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = Tourist::all()->map(function ($tourist) {
    return (object)[
        'id' => $tourist->id,
        'customer_id' => 'C' . str_pad($tourist->id, 3, '0', STR_PAD_LEFT),
        'name' => $tourist->name,
        'email' => $tourist->email,
        'mobile' => $tourist->mobile ?? 'N/A', // ✅ add mobile
        'location' => $tourist->location ?: 'Unknown', // ✅ already exists but ensure fallback
        'bookings_count' => '--',
        'total_spent' => 'Rs. 0.00'
    ];
});


        return view('admin.customers', compact('customers'));
    }
}
