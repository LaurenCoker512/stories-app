<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Story;

class ManageStoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $story = Story::factory()->create();

        $this->assertEquals('/stories/' . $story->id, $story->path());
    }

    /** @test */
    public function it_has_a_path_to_first_chapter()
    {
        $story = Story::factory()->create();

        $this->assertEquals('/stories/' . $story->id . '/chapters/1', $story->firstChapterPath());
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $story = Story::factory()->create();

        $this->assertInstanceOf('App\Models\User', $story->user);
    }

    /** @test */
    public function it_can_add_a_chapter()
    {
        $story = Story::factory()->create();

        $chapter = $story->addChapter('Test body');

        $this->assertCount(1, $story->chapters);
        $this->assertTrue($story->chapters->contains($chapter));
    }
}
