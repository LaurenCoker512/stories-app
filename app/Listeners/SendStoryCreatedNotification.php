<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\StoryCreated;
use App\Notifications\StoryCreatedNotification;

class SendStoryCreatedNotification implements ShouldQueue
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
        $authorSubscribers = $event->story->author->subscribers;

        foreach ($authorSubscribers as $subscriber) {
            $subscriber->user->notify(new StoryCreatedNotification($event->story, $subscriber->user));
        }
    }
}
