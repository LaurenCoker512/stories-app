<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(User $user)
    {
        $stories = $user->stories()->paginate(20);

        // Get all of author subscriptions

        $storySubscriptions = $user->subscriptions() // all the user's subscriptions
                            ->where('subscribable_type', 'App\Models\Story') // only the subscriptions that are type Story
                            ->get();

        $subbedStories = $storySubscriptions->map(function ($item) {
            return $item->subscribable;
        });

        $userSubscriptions = $user->subscriptions() // all the user's subscriptions
                            ->where('subscribable_type', 'App\Models\User') // only the subscriptions that are type User
                            ->get();

        $userStories = collect();

        $userSubscriptions->each(function ($item) {
            $userStories->merge($item->subscribable->stories);
        });

        $authorSubs = $subbedStories->merge($userStories)
                                ->unique()
                                ->sortByDesc('updated_at')
                                ->take(5);

        $userIsSubscribed = (bool) auth()->user() && auth()->user()->subscriptions->first(function ($item, $key) use ($user) {
            return $item->subscribable_type === 'App\Models\User' && $item->subscribable_id === $user->id;
        });

        return view('dashboard.index', compact('stories', 'user', 'userIsSubscribed', 'authorSubs'));
    }
}
