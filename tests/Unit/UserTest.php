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
    public function a_user_has_stories()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->stories);
    }
}
