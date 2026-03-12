<?php

namespace App\Http\Controllers;

use App\Models\City;

class HomeController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->get();

        return view('home', compact('cities'));
    }
}
