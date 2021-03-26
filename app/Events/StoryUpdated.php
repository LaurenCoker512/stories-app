<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Story;

class StoryUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $story;
    public $chapterName;
    public $chapterNum;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Story $story, $chapterName, int $chapterNum)
    {
        $this->story = $story;
        $this->chapterName = $chapterName;
        $this->chapterNum = $chapterNum;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
