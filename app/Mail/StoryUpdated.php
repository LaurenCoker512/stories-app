<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Story;
use App\Models\User;

class StoryUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $story;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Story $story, User $user)
    {
        $this->story = $story;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('laurenekcoker@gmail.com')
            ->subject($this->story->title . ' has been updated!')
            ->view('emails.story-updated')
            ->text('emails.story-updated_plain');
    }
}
