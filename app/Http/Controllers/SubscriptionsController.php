<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionsController extends Controller
{
    public function index()
    {
        $storySubscriptions = auth()->user()
                            ->subscriptions() // all the user's subscriptions
                            ->where('subscribable_type', 'App\Models\Story') // only the subscriptions that are type Story
                            ->get();

        $stories = $storySubscriptions->map(function ($item) {
            return $item->subscribable;
        });

        $userSubscriptions = auth()->user()
                            ->subscriptions() // all the user's subscriptions
                            ->where('subscribable_type', 'App\Models\User') // only the subscriptions that are type User
                            ->get();

        $userStories = collect();

        $userSubscriptions->each(function ($item) {
            $userStories->merge($item->subscribable->stories);
        });

        $subscriptions = $stories->merge($userStories)
                                ->unique()
                                ->sortByDesc('updated_at');

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function createStorySub(Story $story)
    {
        if (!$story->subscribers()->where('user_id', auth()->id())->first()) {
            $story->subscribers()->create([
                'user_id' => auth()->id()
            ]);
        }

        return back();
    }

    public function createUserSub(User $user)
    {
        if (!$user->subscribers()->where('user_id', auth()->id())->first()) {
            $user->subscribers()->create([
                'user_id' => auth()->id()
            ]);
        }

        return back();
    }

    public function deleteStorySub(Story $story)
    {
        $subscription = $story->subscribers()
                            ->where('user_id', auth()->id())
                            ->first();
        
        $subscription->delete();

        return back();
    }

    public function deleteUserSub(User $user)
    {
        $subscription = $user->subscribers()
                            ->where('user_id', auth()->id())
                            ->first();
        
        $subscription->delete();

        return back();
    }
}
