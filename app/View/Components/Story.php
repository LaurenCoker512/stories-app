<?php

namespace App\View\Components;

use Illuminate\View\Component;

use App\Models\Story as StoryModel;
use App\Models\User;

class Story extends Component
{
    public $story;
    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(StoryModel $story, User $user)
    {
        $this->story = $story;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.story');
    }
}
