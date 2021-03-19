<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\StoryCreated;
use App\Notifications\StoryCreatedNotification;

class SendStoryCreatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(StoryCreated $event)
    {
        $authorSubscribers = $event->story->user->subscribers;

        foreach ($authorSubscribers as $subscriber) {
            $subscriber->user->notify(new StoryCreatedNotification($event->story, $subscriber->user));
        }
    }
}
