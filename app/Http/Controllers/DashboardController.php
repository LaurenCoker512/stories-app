<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(User $user)
    {
        $stories = $user->stories;

        return view('dashboard.index', compact('stories', 'user'));
    }
}
