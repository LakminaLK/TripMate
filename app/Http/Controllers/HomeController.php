<?php

namespace App\Http\Controllers;

use App\Models\Activity;

class HomeController extends Controller
{
    public function index()
    {
        $homeActivities = Activity::with('locations')->latest('id')->take(6)->get();
        return view('tourist.landing', compact('homeActivities'));
    }
}
