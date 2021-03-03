<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoriesTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_create_a_story()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'user_id' => 1
        ];

        $this->post('/stories', $attributes)->assertRedirect('/stories');

        $this->assertDatabaseHas('stories', $attributes);

        $this->get('/stories')->assertSee($attributes['title']);
    }

    // A logged-out user cannot create a story

    // A user can view a story

    // The creator of a story can edit it

    // The creator of a story can delete it

    // A user that did not create a story cannot edit or delete it
}
