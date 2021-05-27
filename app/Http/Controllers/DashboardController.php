<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

/**
 * This class is a controller for the user dashboard and user notifications.
 */
class DashboardController extends Controller
{
    /**
     * This method gets all the data for a user dashboard and loads the
     * dashboard.
     * 
     * @param User $user An instance of the User model
     * 
     * @return redirect
     */
    public function index(User $user)
    {
        $stories = $user->stories()->paginate(20);

        // If this is the logged-in user's dashboard
        if ($user == auth()->user()) {
            // Get all of author's subscriptions
            // First, subscriptions to stories
            $storySubscriptions = $user->subscriptions() // all the user's subscriptions
                                ->where('subscribable_type', 'App\Models\Story') // only the subscriptions that are type Story
                                ->get();

            $subbedStories = $storySubscriptions->map(function ($item) {
                return $item->subscribable;
            });

            // Then subscriptions to authors
            $userSubscriptions = $user->subscriptions() // all the user's subscriptions
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

            // Then merge both collections
            $authorSubs = $subbedStories->merge($userStories)
                                    ->unique()
                                    ->sortByDesc('updated_at')
                                    ->take(5);

            $userIsSubscribed = false;

        } else {
            $authorSubs = collect();
            
            $userIsSubscribed = (bool) auth()->user() && auth()->user()->subscriptions->first(function ($item, $key) use ($user) {
                return $item->subscribable_type === 'App\Models\User' && $item->subscribable_id === $user->id;
            });
        }

        return view('dashboard.index', compact('stories', 'user', 'userIsSubscribed', 'authorSubs'));
    }

    /**
     * This method loads the view to edit a user's information.
     * 
     * @param User $user An instance of the User model
     * 
     * @return redirect
     */
    public function edit(User $user)
    {
        if ($user != auth()->user()) {
            abort(401);
        }
        return view('dashboard.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        if ($user != auth()->user()) {
            abort(401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'old_password' => ['nullable', 'required_with:new_password', 'string', 'min:8'],
            'new_password' => ['nullable', 'string', 'min:8'],
            'password-confirm' => ['required_with:new_password', 'string', 'min:8', 'same:new_password'],
            'phone' => ['numeric', 'nullable'],
            'website' => ['regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', 'nullable']
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'website' => $validated['website'],
        ]);

        if ($validated['new_password'] && \Hash::check($validated['old_password'], $user->password)) {
            $user->password = \Hash::make($validated['new_password']);
            $user->save();
        }

        $request->session()->flash('status', 'User info was updated!');

        return redirect('/dashboard/' . auth()->id() . '/edit');
    }

    /**
     * This method gets all the unread notifications for the logged-in user.
     * 
     * @return json
     */
    public function getNotifications()
    {
        $notifications = auth()->user()->unreadNotifications;

        return response()->json($notifications);
    }

    /**
     * This methods marks all the unread notifications for the logged-in user
     * as read.
     * 
     * @return boolean
     */
    public function readNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead;

        return true;
    }
}
