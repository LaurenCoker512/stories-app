<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\StoryUpdated;
use App\Notifications\StoryUpdatedNotification;

class SendStoryUpdatedNotification implements ShouldQueue
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
    public function handle(StoryUpdated $event)
    {
        $subscribers = $event->story->subscribers;
        $authorSubscribers = $event->story->author->subscribers;

        foreach ($subscribers as $subscriber) {
            $subscriber->user->notify(new StoryUpdatedNotification($event->story, $event->chapterName, $event->chapterNum, $subscriber->user));
        }

        foreach ($authorSubscribers as $subscriber) {
            $subscriber->user->notify(new StoryUpdatedNotification($event->story, $event->chapterName, $event->chapterNum, $subscriber->user));
        }
    }
}
