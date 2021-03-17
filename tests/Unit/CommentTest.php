<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that the $comment->path() method will lead to the comment's path.
     *
     * @return void
     */
    /** @test */
    public function it_has_a_path()
    {
        $comment = Comment::factory()->create();

        $this->assertEquals(
            '/stories/' . $comment->chapter->story->id . '/chapters/' . $comment->chapter->getNumber() . '/comments/' . $comment->id, 
            $comment->path());
    }
}
