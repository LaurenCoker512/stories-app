<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Facades\StoryChapterFacade;
use App\Models\Chapter;
use App\Models\Story;

class ChapterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that a chapter belongs to a story.
     *
     * @return void
     */
    /** @test */
    public function it_belongs_to_a_story()
    {
        $chapter = Chapter::factory()->create();

        $this->assertInstanceOf(Story::class, $chapter->story);
    }

    /**
     * Tests that the $chapter->path() method will lead to the chapter's path.
     *
     * @return void
     */
    /** @test */
    public function it_has_a_path()
    {
        $chapter = Chapter::factory()->create();

        $this->assertEquals(
            '/stories/' . $chapter->story->id . '/chapters/' . StoryChapterFacade::getChapterNumFromId($chapter->id), 
            $chapter->path());
    }
}
