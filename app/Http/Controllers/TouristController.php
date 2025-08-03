<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TouristController extends Controller
{
    public function home()
    {
        return view('tourist.home');
    }
}

