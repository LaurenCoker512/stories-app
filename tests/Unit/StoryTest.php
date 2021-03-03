<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Story;

class StoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $story = Story::factory()->create();

        $this->assertEquals('/stories/' . $story->id, $story->path());
    }
}
