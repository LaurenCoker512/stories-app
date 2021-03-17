<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\StoryFactory;

use App\Models\Story;
use App\Models\User;

use Tests\TestCase;

class StoriesTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Tests that a user that is signed-out cannot create a story.
     *
     * @return void
     */
    /** @test */
    public function guests_cannot_create_a_story()
    {
        $attributes = Story::factory()->raw();

        $this->get('/stories/create')->assertRedirect('login');
        $this->post('/stories', $attributes)->assertRedirect('login');
    }

    /**
     * Tests that a user that is signed-out cannot edit a story.
     *
     * @return void
     */
    /** @test */
    public function guests_cannot_edit_a_story()
    {
        $story = Story::factory()->create();
        
        $this->get("/stories/{$story->id}/edit")->assertRedirect('login');
    }

    /**
     * Tests that a user that is signed-in can create a story.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_create_a_story()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->signIn($user);

        $this->get('/stories/create')->assertStatus(200);

        $attributes = Story::factory()->raw(['user_id' => $user->id]);

        $response = $this->post('/stories', [
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'user_id' => $attributes['user_id'],
            'first-chapter' => "A test first chapter body"
        ]);

        $response->assertRedirect(Story::where($attributes)->first()->firstChapterPath());

        $this->assertDatabaseHas('stories', $attributes);

        $this->get('/stories')->assertSee($attributes['title']);
    }

    /**
     * Tests that a user that is signed-in as the creator of a story can edit
     * that story.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_update_a_story()
    {
        $story = StoryFactory::create();

        $this->actingAs($story->user)
            ->patch($story->path(), $attributes = [
                'title' => $story->title,
                'description' => 'Changed'
            ])->assertRedirect($story->firstChapterPath());

        $this->get($story->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('stories', $attributes);
    }

    /**
     * Tests that a user that is not signed in as the creator of a story
     * cannot delete that story.
     *
     * @return void
     */
    /** @test */
    public function unauthorized_users_cannot_delete_stories()
    {
        $story = StoryFactory::create();

        $this->delete($story->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($story->path())
            ->assertStatus(403);
    }

    /**
     * Tests that a user that is signed-in as the creator of a story can delete
     * that story.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_delete_a_story()
    {
        $story = StoryFactory::create();

        $this->actingAs($story->user)
            ->delete($story->path())
            ->assertRedirect('/dashboard/' . $story->user->id);

        $this->assertDatabaseMissing('stories', $story->only('id'));
    }

    /**
     * Tests that a user that is not signed-in can view a story.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_view_a_story()
    {
        $story = Story::factory()->create();

        $this->get($story->firstChapterPath())
            ->assertSee($story->title)
            ->assertSee($story->description);
    }

    /**
     * Tests that a user that is signed in cannot update a story created by
     * another user.
     *
     * @return void
     */
    /** @test */
    public function an_authenticated_user_cannot_update_the_stories_of_others()
    {
        $this->signIn();

        $story = Story::factory()->create();

        $this->get("{$story->path()}/edit")->assertStatus(403);

        $this->patch($story->path(), [])->assertStatus(403);
    }

    /**
     * Tests that session will have errors and store method will fail if story
     * is submitted without a title.
     *
     * @return void
     */
    /** @test */
    public function a_story_requires_a_title()
    {
        $this->signIn();

        $attributes = Story::factory()->raw(['title' => '']);

        $this->post('/stories', $attributes)->assertSessionHasErrors('title');
    }

    /**
     * Tests that session will have errors and store method will fail if story
     * is submitted without a description.
     *
     * @return void
     */
    /** @test */
    public function a_story_requires_a_description()
    {
        $this->signIn();

        $attributes = Story::factory()->raw(['description' => '']);

        $this->post('/stories', $attributes)->assertSessionHasErrors('description');
    }

    /**
     * Tests that a user that is not signed in as the owner of a dashboard
     * cannot see links to edit and delete stories on that dashboard.
     *
     * @return void
     */
    /** @test */
    public function unauthenticated_users_cannot_see_crud_links_on_dashboard()
    {
        $user = User::factory()->create();

        $stories = Story::factory()->count(5)->create(['user_id' => $user->id]);

        $this->get('/dashboard/' . $user->id)
            ->assertDontSee('Welcome')
            ->assertDontSee('Edit')
            ->assertDontSee('Delete');

        $this->signIn();

        $this->get('/dashboard/' . $user->id)
            ->assertSee('Welcome')
            ->assertDontSee('Edit')
            ->assertDontSee('Delete');

        $this->actingAs($user)
            ->get('/dashboard/' . $user->id)
            ->assertSee('Welcome')
            ->assertSee('Edit')
            ->assertSee('Delete');
    }

    /**
     * Tests that a user can search for a word, and any stories with that word
     * in the title or description will show up.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_search_for_a_story()
    {
        $story1 = Story::factory()->create(['title' => 'Hello there']);
        $story2 = Story::factory()->create(['description' => 'Well hello again.']);
        $story3 = Story::factory()->create();

        $this->get('/search?search=hello')
            ->assertSee($story1->description)
            ->assertSee($story2->title)
            ->assertDontSee($story3->title);
    }
}
