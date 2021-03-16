<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\StoryFactory;

use App\Models\Comment;
use App\Models\Story;

use Tests\TestCase;

class ChapterCommentsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function a_chapter_can_have_comments()
    {
        $story = Story::factory()->create();

        $chapter = $story->addChapter('This is a chapter');

        $this->signIn();

        $this->post("{$chapter->path()}/comments", [
            'body' => 'Lorem ipsum'
        ]);

        $this->get($chapter->path())
            ->assertSee(auth()->user()->name)
            ->assertSee('Lorem ipsum');

    }

    /** @test */
    public function guests_can_leave_comments()
    {
        $story = StoryFactory::withChapters(1)
                    ->create();

        $this->post("{$story->chapters[0]->path()}/comments", [
            'body' => 'Lorem ipsum'
        ]);

        $this->get($story->chapters[0]->path())
            ->assertSee('Guest')
            ->assertSee('Lorem ipsum');
    }

    /** @test */
    public function an_authenticated_user_can_edit_their_comment()
    {
        $story = StoryFactory::withChapters(1)
                    ->create();

        $this->signIn();

        $this->post("{$story->chapters[0]->path()}/comments", [
            'body' => 'Lorem ipsum'
        ]);

        $this->patch("{$story->chapters[0]->path()}/comments/{$story->chapters[0]->comments[0]->id}", [
            'body' => 'Lorem ipsum edited'
        ]);

        $this->get($story->chapters[0]->path())
            ->assertSee(auth()->user()->name)
            ->assertSee('Lorem ipsum edited');
    }

    /** @test */
    public function unauthorized_users_cannot_edit_comments()
    {
        $comment = Comment::factory()->create();

        $this->patch("{$comment->chapter->path()}/comments/{$comment->id}", [
            'body' => 'Lorem ipsum edited'
        ])->assertRedirect('login');

        $this->signIn();

        $this->patch("{$comment->chapter->path()}/comments/{$comment->id}", [
            'body' => 'Lorem ipsum edited'
        ])->assertStatus(403);

        $this->assertDatabaseMissing('comments', ['body' => 'Lorem ipsum edited']);
    }

    /** @test */
    public function an_authenticated_user_can_delete_their_comment()
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->user)
            ->delete("{$comment->chapter->path()}/comments/{$comment->id}");

        $this->assertDatabaseMissing('comments', $comment->only('id'));
    }

    /** @test */
    public function the_owner_of_a_chapter_can_delete_a_comment()
    { 
        $comment = Comment::factory()->create();

        $this->actingAs($comment->chapter->story->user)
            ->delete("{$comment->chapter->path()}/comments/{$comment->id}");

        $this->assertDatabaseMissing('comments', $comment->only('id'));
    }

    /** @test */
    public function unauthorized_users_cannot_delete_comments()
    {
        $comment = Comment::factory()->create();

        $this->delete("{$comment->chapter->path()}/comments/{$comment->id}")
            ->assertRedirect('login');

        $this->signIn();

        $this->delete("{$comment->chapter->path()}/comments/{$comment->id}")
            ->assertStatus(403);
    }
}
