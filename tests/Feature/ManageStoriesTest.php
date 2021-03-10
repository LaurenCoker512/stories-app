<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Story;
use App\Models\User;

use Tests\TestCase;

class StoriesTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_create_a_story()
    {
        $attributes = Story::factory()->raw();

        $this->get('/stories/create')->assertRedirect('login');
        $this->post('/stories', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_edit_a_story()
    {
        $story = Story::factory()->create();
        
        $this->get("/stories/{$story->id}/edit")->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_story()
    {
        $user = User::factory()->create();

        $this->signIn($user);

        $this->get('/stories/create')->assertStatus(200);

        $attributes = Story::factory()->raw(['user_id' => $user->id]);

        $response = $this->post('/stories', $attributes);

        $response->assertRedirect(Story::where($attributes)->first()->path());

        $this->assertDatabaseHas('stories', $attributes);

        $this->get('/stories')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_update_a_story()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $story = Story::factory()->create(['user_id' => auth()->id()]);

        $this->patch($story->path(), [
            'title' => $story->title,
            'description' => 'Changed'
        ])->assertRedirect($story->path());

        $this->assertDatabaseHas('stories', ['description' => 'Changed']);
    }

    /** @test */
    public function a_user_can_view_a_story()
    {
        $this->withoutExceptionHandling();

        $story = Story::factory()->create();

        $this->get($story->path())
            ->assertSee($story->title)
            ->assertSee($story->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_stories_of_others()
    {
        $this->signIn();

        $story = Story::factory()->create();

        $this->get("{$story->path()}/edit")->assertStatus(403);

        $this->patch($story->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_story_requires_a_title()
    {
        $this->signIn();

        $attributes = Story::factory()->raw(['title' => '']);

        $this->post('/stories', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_story_requires_a_description()
    {
        $this->signIn();

        $attributes = Story::factory()->raw(['description' => '']);

        $this->post('/stories', $attributes)->assertSessionHasErrors('description');
    }

    // The creator of a story can edit it

    // The creator of a story can delete it

    // A user that did not create a story cannot edit or delete it
}
