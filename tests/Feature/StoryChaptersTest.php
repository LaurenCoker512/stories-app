<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\StoryFactory;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\User;

class StoryChaptersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that users that are not signed-in cannot add a chapter to a story.
     *
     * @return void
     */
    /** @test */
    public function guests_cannot_add_chapters_to_stories()
    {
        $story = Story::factory()->create();

        $this->post($story->path() . '/chapters', ['body' => 'Test body'])
            ->assertRedirect('login');
    }

    /**
     * Tests that a user that is not signed in as the creator of the story
     * cannot add chapters to it.
     *
     * @return void
     */
    /** @test */
    public function only_the_owner_of_a_story_may_add_chapters()
    {   
        $this->signIn();

        $story = Story::factory()->create();

        $this->post($story->path() . '/chapters', ['body' => 'Test body'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('chapters', ['body' => 'Test body']);
    }

    /**
     * Tests that a user that is not signed in as the creator of the story
     * cannot update one of its chapters.
     *
     * @return void
     */
    /** @test */
    public function only_the_owner_of_a_story_may_update_a_chapter()
    {   
        $this->signIn();

        $story = StoryFactory::withChapters(1)
                    ->create();

        $this->patch($story->chapters[0]->path(), [
            'body' => 'Test body updated'
        ])->assertStatus(403);

        $this->assertDatabaseMissing('chapters', ['body' => 'Test body updated']);
    }

    /**
     * Tests that the owner of a story can add a chapter to it, and that chapter 
     * will show up when a user visits the story's first chapter.
     *
     * @return void
     */
    /** @test */
    public function a_story_can_have_chapters()
    {
        $story = StoryFactory::create();

        $this->actingAs($story->user)
            ->post($story->path() . '/chapters', ['body' => 'Lorem ipsum']);

        $this->get($story->firstChapterPath())
            ->assertSee('Lorem ipsum');
    }

    /**
     * Tests that the owner of a story can add multiple chapters to a story,
     * and those chapters will be numbered in the order they are created.
     *
     * @return void
     */
    /** @test */
    public function a_story_can_have_multiple_chapters()
    {
        $story = StoryFactory::create();

        $this->actingAs($story->user)
            ->post($story->path() . '/chapters', ['body' => 'Lorem ipsum']);

        $this->actingAs($story->user)
            ->post($story->path() . '/chapters', ['body' => 'Lorem ipsum2']);

        $this->get($story->firstChapterPath())
            ->assertSee('Lorem ipsum');

        $this->get($story->path() . '/chapters/2')
            ->assertSee('Lorem ipsum2');

    }

    /**
     * Tests that the owner of a story can update one of its chapters.
     *
     * @return void
     */
    /** @test */
    public function a_chapter_can_be_updated()
    {
        $story = StoryFactory::withChapters(1)
                    ->create();

        $this->actingAs($story->user)->patch($story->chapters[0]->path(), [
            'body' => 'Changed'
        ]);

        $this->assertDatabaseHas('chapters', [
            'body' => 'Changed'
        ]);
    }

    /**
     * Tests that the owner of a story can delete one of its chapters.
     *
     * @return void
     */
    /** @test */
    public function a_chapter_can_be_deleted()
    {
        $story = StoryFactory::withChapters(1)
                    ->create();

        $this->actingAs($story->user)
            ->delete($story->chapters[0]->path())
            ->assertRedirect('/dashboard/' . $story->user->id);

        $this->assertDatabaseMissing('chapters', [
            'story_id' => $story->id
        ]);
    }

    /**
     * Tests that a chapter must have be submitted with a body, and session
     * errors will appear if it is not.
     *
     * @return void
     */
    /** @test */
    public function a_chapter_requires_a_body()
    {
        $story = StoryFactory::create();

        $attributes = Chapter::factory()->raw(['body' => '']);

        $this->actingAs($story->user)
            ->post($story->path() . '/chapters', $attributes)
            ->assertSessionHasErrors('body');
    }
}
