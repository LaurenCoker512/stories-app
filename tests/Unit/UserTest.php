<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Story;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that the $user->stories method returns an instance of Collection.
     *
     * @return void
     */
    /** @test */
    public function it_has_stories()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->stories);
    }

    /**
     * Tests that the url /dashboard/{user_id} will lead to a user's dashboard.
     *
     * @return void
     */
    /** @test */
    public function it_has_a_dashboard()
    {
        $this->signIn();

        $this->get('/dashboard/' . auth()->id())
            ->assertStatus(200)->assertSee(auth()->user()->name);
    }

}
