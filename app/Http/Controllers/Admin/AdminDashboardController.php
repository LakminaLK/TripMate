<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tourist;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function customers()
{
    $customers = Tourist::all(); // adjust if you use soft deletes or statuses
    return view('admin.customers', compact('customers'));
}

    
}


