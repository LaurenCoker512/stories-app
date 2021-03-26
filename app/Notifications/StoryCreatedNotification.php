<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Mail\StoryCreated as Mailable;

use App\Models\Story;
use App\Models\User;

class StoryCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $story;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Story $story, User $user)
    {
        $this->story = $story;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new Mailable($this->story, $this->user))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'story_id' => $this->story->id,
            'user_id' => $this->user->id,
        ];
    }
}
