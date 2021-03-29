<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Subscription;
use App\Models\User;

/**
 * This class is a controller for subscriptions to stories or authors.
 */
class SubscriptionsController extends Controller
{
    /**
     * This method gets all of the subscribed stories for the logged-in user.
     * 
     * @return view
     */
    public function index()
    {
        $storySubscriptions = auth()->user()
                            ->subscriptions() // all the user's subscriptions
                            ->where('subscribable_type', 'App\Models\Story') // only the subscriptions that are type Story
                            ->get();

        $stories = $storySubscriptions->map(function ($item) {
            return $item->subscribable;
        });

        $userSubscriptions = auth()->user()->subscriptions() // all the user's subscriptions
                            ->where('subscribable_type', 'App\Models\User') // only the subscriptions that are type User
                            ->get();

        if ($userSubscriptions->count() > 0) {
            $userStories = $userSubscriptions[0]->subscribable->stories;
            if ($userSubscriptions->count() > 1) {
                $userSubscriptions->each(function ($item) {
                    $userStories->merge($item->subscribable->stories);
                });
            }
        } else {
            $userStories = collect();
        }

        $subscriptions = $stories->merge($userStories)
                                ->unique()
                                ->sortByDesc('updated_at');

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * This method creates a new subscription to a story.
     * 
     * @param Story $story An instance of the Story model
     * 
     * @return redirect
     */
    public function createStorySub(Story $story)
    {
        if (!$story->subscribers()->where('user_id', auth()->id())->first()) {
            $story->subscribers()->create([
                'user_id' => auth()->id()
            ]);
        }

        return back();
    }

    /**
     * This method creates a new subscription to an author.
     * 
     * @param User $user An instance of the User model
     * 
     * @return redirect
     */
    public function createUserSub(User $user)
    {
        if (!$user->subscribers()->where('user_id', auth()->id())->first()) {
            $user->subscribers()->create([
                'user_id' => auth()->id()
            ]);
        }

        return back();
    }

    /**
     * This method removes a subscription to a given story for the logged-in
     * user.
     * 
     * @param Story $story An instance of the Story model
     * 
     * @return redirect
     */
    public function deleteStorySub(Story $story)
    {
        $subscription = $story->subscribers()
                            ->where('user_id', auth()->id())
                            ->first();
        
        $subscription->delete();

        return back();
    }

    /**
     * This method removes a subscription to a given author for the logged-in
     * user.
     * 
     * @param User $user An instance of the User model
     * 
     * @return redirect
     */
    public function deleteUserSub(User $user)
    {
        $subscription = $user->subscribers()
                            ->where('user_id', auth()->id())
                            ->first();
        
        $subscription->delete();

        return back();
    }
}
