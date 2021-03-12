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

    /** @test */
    public function it_has_stories()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->stories);
    }

    /** @test */
    public function it_has_a_dashboard()
    {
        $this->signIn();

        $this->get('/dashboard/' . auth()->id())->assertStatus(200)->assertSee(auth()->user()->name);
    }

}
