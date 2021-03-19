<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\StoryCreated;
use App\Events\StoryUpdated;
use App\Listeners\SendStoryUpdatedNotification;
use App\Listeners\SendStoryCreatedNotification;
use App\Listeners\SendWelcomeEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendWelcomeEmail::class,
        ],
        StoryUpdated::class => [
            SendStoryUpdatedNotification::class,
        ],
        StoryCreated::class => [
            SendStoryCreatedNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
