<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\StoryFactory;
use Tests\TestCase;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\Tag;
use App\Models\User;

class StoryTagsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_a_list_of_all_tags()
    {
        $tags = Tag::factory()->count(5)->create();

        $this->get('/tags')
            ->assertSee($tags[0]->name)
            ->assertSee($tags[1]->name)
            ->assertSee($tags[2]->name)
            ->assertSee($tags[3]->name)
            ->assertSee($tags[4]->name);
    }

    /** @test */
    public function a_user_can_view_a_list_of_all_stories_with_a_tag()
    {
        $tag = Tag::factory()->create();

        $stories = Story::factory()->count(5)->create();
        // Loop over each story and add the tag to it

        $this->get('/tags/' . $tag->id)
            ->assertSee($stories[0]->title)
            ->assertSee($stories[1]->title)
            ->assertSee($stories[2]->title)
            ->assertSee($stories[3]->title)
            ->assertSee($stories[4]->title);
    }

    /** @test */
    public function the_owner_of_a_story_can_add_tags()
    {
        $tags = Tag::factory()->count(3)->create();

        $story = StoryFactory::create();
        
        $this->signIn($story->user);

        $story->updateTags($tags);

        $this->get('/stories/' . $story->id . '/chapters/1')
            ->assertSee($tags[0]->name)
            ->assertSee($tags[1]->name)
            ->assertSee($tags[2]->name);
    }

    /** @test */
    public function the_owner_of_a_story_can_remove_tags()
    {
        $tags = Tag::factory()->count(3)->create();

        $story = StoryFactory::create();
        
        $this->signIn($story->user);

        $story->updateTags($tags);

        $story->updateTags([$tags[2]->id]);

        $this->get('/stories/' . $story->id . '/chapters/1')
            ->assertSee($tags[2]->name);
    }

}
