<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(User $user)
    {
        $stories = $user->stories;

        $userIsSubscribed = (bool) auth()->user() && auth()->user()->subscriptions->first(function ($item, $key) use ($user) {
            return $item->subscribable_type === 'App\Models\User' && $item->subscribable_id === $user->id;
        });

        return view('dashboard.index', compact('stories', 'user', 'userIsSubscribed'));
    }
}
