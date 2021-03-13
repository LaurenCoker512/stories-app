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
     * A basic feature test example.
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

    /** @test */
    public function only_the_owner_of_a_story_may_add_chapters()
    {   
        $this->signIn();

        $story = Story::factory()->create();

        $this->post($story->path() . '/chapters', ['body' => 'Test body'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('chapters', ['body' => 'Test body']);
    }

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

    /** @test */
    public function a_story_can_have_chapters()
    {
        $story = StoryFactory::create();

        $this->actingAs($story->user)
            ->post($story->path() . '/chapters', ['body' => 'Lorem ipsum']);

        $this->get($story->firstChapterPath())
            ->assertSee('Lorem ipsum');
    }

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
