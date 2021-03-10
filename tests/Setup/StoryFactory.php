<?php

namespace Tests\Setup;

use App\Models\Story;
use App\Models\Chapter;
use App\Models\User;

class StoryFactory
{
    protected $chaptersCount = 0;
    protected $user;

    public function withChapters($count)
    {
        $this->chaptersCount = $count;

        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }

    public function create()
    {
        $story = Story::factory()->create([
            'user_id' => $this->user ?? User::factory()->create()->id
        ]);

        $chapter = Chapter::factory($this->chaptersCount)
            ->create([
                'story_id' => $story->id
            ]);

        return $story;
    }
}