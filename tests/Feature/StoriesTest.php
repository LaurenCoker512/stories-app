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
    public function only_authenticated_users_can_create_a_story()
    {
        $attributes = Story::factory()->raw();

        $this->post('/stories', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_story()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user);

        $attributes = Story::factory()->raw(['user_id' => $user->id]);

        $this->post('/stories', $attributes)->assertRedirect('/stories');

        $this->assertDatabaseHas('stories', $attributes);

        $this->get('/stories')->assertSee($attributes['title']);
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
    public function a_story_requires_a_title()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Story::factory()->raw(['title' => '']);

        $this->post('/stories', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_story_requires_a_description()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Story::factory()->raw(['description' => '']);

        $this->post('/stories', $attributes)->assertSessionHasErrors('description');
    }

    // A logged-out user cannot create a story

    // A user can view a story

    // The creator of a story can edit it

    // The creator of a story can delete it

    // A user that did not create a story cannot edit or delete it
}
