<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class AvatarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that an image can be uploaded and set to a user avatar as a file.
     *
     * @return void
     */
    /** @test */
    public function it_can_be_uploaded_as_a_file()
    {
        $this->withoutExceptionHandling();

        Storage::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/dashboard/{$user->id}/image", [
            'image' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertDatabaseHas('avatars', [
            'user_id' => auth()->id(),
            'image_type' => "upload"
        ]);
    }

    /**
     * Tests that an image can be uploaded and set to a user avatar as a URL.
     *
     * @return void
     */
    /** @test */
    public function it_can_be_uploaded_as_a_url()
    {
        Storage::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/dashboard/{$user->id}/image", [
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/fb/Kalmia_Latifolia.jpg'
        ]);

        $this->assertDatabaseHas('avatars', [
            'user_id' => auth()->id()
        ]);
    }
}
