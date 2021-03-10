<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stories = auth()->user()->stories;

        return view('dashboard.index', compact('stories'));
    }
}
