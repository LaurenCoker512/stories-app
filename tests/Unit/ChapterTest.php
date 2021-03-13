<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Chapter;
use App\Models\Story;

class ChapterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function it_belongs_to_a_story()
    {
        $chapter = Chapter::factory()->create();

        $this->assertInstanceOf(Story::class, $chapter->story);
    }

    /** @test */
    public function it_has_a_path()
    {
        $chapter = Chapter::factory()->create();

        $this->assertEquals(
            '/stories/' . $chapter->story->id . '/chapters/' . $chapter->getNumber(), 
            $chapter->path());
    }
}
