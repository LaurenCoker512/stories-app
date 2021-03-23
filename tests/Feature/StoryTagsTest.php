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

    /**
     * Tests that a user can view a list of all of the tags in the database.
     *
     * @return void
     */
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

    /**
     * Tests that a user can view a list of all of the stories associated with
     * a particular tag.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_view_a_list_of_all_stories_with_a_tag()
    {
        $tag = Tag::factory()->create();

        $stories = Story::factory()->count(5)->create();

        foreach ($stories as $story) {
            $story->updateTags([$tag->id]);
        }

        $this->get('/tags/' . $tag->id)
            ->assertSee($stories[0]->title)
            ->assertSee($stories[1]->title)
            ->assertSee($stories[2]->title)
            ->assertSee($stories[3]->title)
            ->assertSee($stories[4]->title);
    }

    /**
     * Tests that the owner of a story can add tags to that story, and those
     * tags will show up on the story's chapter pages.
     *
     * @return void
     */
    /** @test */
    public function the_owner_of_a_story_can_add_tags()
    {
        $tags = Tag::factory()->count(3)->create();

        $story = StoryFactory::create();
        
        $this->signIn($story->author);

        $story->updateTags($tags);

        $this->get('/stories/' . $story->id . '/chapters/1')
            ->assertSee($tags[0]->name)
            ->assertSee($tags[1]->name)
            ->assertSee($tags[2]->name);
    }

    /**
     * Tests that the owner of a story can remove tags from that story.
     *
     * @return void
     */
    /** @test */
    public function the_owner_of_a_story_can_remove_tags()
    {
        $tags = Tag::factory()->count(3)->create();

        $story = StoryFactory::create();
        
        $this->signIn($story->author);

        $story->updateTags($tags);

        $story->updateTags([$tags[2]->id]);

        $this->get('/stories/' . $story->id . '/chapters/1')
            ->assertSee($tags[2]->name);
    }

}
