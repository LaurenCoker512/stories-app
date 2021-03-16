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
     * Tests that a comment can be added to a chapter, and a user other than
     * the creator can view the comment.
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

    /**
     * Tests that a user that is not signed in can leave a comment on a 
     * chapter.
     *
     * @return void
     */
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

    /**
     * Tests that a user that created a comment and is signed-in can edit that
     * comment.
     *
     * @return void
     */
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

    /**
     * Tests that a user that is either signed out or not the creator of a
     * comment cannot edit it.
     *
     * @return void
     */
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

    /**
     * Tests that a user that created a comment and is signed-in can delete that
     * comment.
     *
     * @return void
     */
    /** @test */
    public function an_authenticated_user_can_delete_their_comment()
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->user)
            ->delete("{$comment->chapter->path()}/comments/{$comment->id}");

        $this->assertDatabaseMissing('comments', $comment->only('id'));
    }

    /**
     * Tests that a user that created the story associated with a comment can
     * delete that comment.
     *
     * @return void
     */
    /** @test */
    public function the_owner_of_a_chapter_can_delete_a_comment()
    { 
        $comment = Comment::factory()->create();

        $this->actingAs($comment->chapter->story->user)
            ->delete("{$comment->chapter->path()}/comments/{$comment->id}");

        $this->assertDatabaseMissing('comments', $comment->only('id'));
    }

    /**
     * Tests that a user that is either signed out or not the creator of a
     * comment cannot delete it.
     *
     * @return void
     */
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
