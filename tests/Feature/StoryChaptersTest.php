<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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

        $story = Story::factory()->create();

        $chapter = $story->addChapter('Test body');

        $this->patch($story->path() . '/chapters/' . $chapter->id, [
            'body' => 'Test body updated'
        ])->assertStatus(403);

        $this->assertDatabaseMissing('chapters', ['body' => 'Test body updated']);
    }

    /** @test */
    public function a_story_can_have_chapters()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $story = Story::factory()->create(['user_id' => auth()->id()]);

        $this->post($story->path() . '/chapters', ['body' => 'Lorem ipsum']);

        $this->get($story->path())
            ->assertSee('Lorem ipsum');
    }

    /** @test */
    public function a_chapter_can_be_updated()
    {
        $this->signIn();

        $story = Story::factory()->create(['user_id' => auth()->id()]);
        $chapter = $story->addChapter('Test chapter');

        $this->patch($chapter->path(), [
            'body' => 'Changed'
        ]);

        $this->assertDatabaseHas('chapters', [
            'body' => 'Changed'
        ]);
    }

    /** @test */
    public function a_chapter_requires_a_body()
    {
        $this->signIn();

        $story = Story::factory()->create(['user_id' => auth()->id()]);

        $attributes = Chapter::factory()->raw(['body' => '']);

        $this->post($story->path() . '/chapters', $attributes)->assertSessionHasErrors('body');
    }
}
