<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class TouristController extends Controller
{
    public function home()
    {
        return view('tourist.home');
    }

    
}

