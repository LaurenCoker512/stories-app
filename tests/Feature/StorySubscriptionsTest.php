<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

use Facades\Tests\Setup\StoryFactory;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\User;
use App\Notifications\StoryCreatedNotification;
use App\Notifications\StoryUpdatedNotification;

class StorySubscriptionsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tests that a user can submit a request to subscribe to a story and that
     * subscription will appear in the database.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_subscribe_to_a_story()
    {
        $story = Story::factory()->create();

        $this->signIn();
        $this->post('/subscriptions/story/' . $story->id);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => auth()->id(),
            'subscribable_id' => $story->id
        ]);
    }

    /**
     * Tests that a user can unsubscribe from a story.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_unsubscribe_from_a_story()
    {
        $story = Story::factory()->create();

        $this->signIn();
        $this->post('/subscriptions/story/' . $story->id);
        $this->delete('/subscriptions/story/' . $story->id);

        $this->assertDatabaseMissing('subscriptions', [
            'user_id' => auth()->id(),
            'subscribable_id' => $story->id
        ]);
    }

    /**
     * Tests that a user can submit a request to subscribe to a user and that
     * subscription will appear in the database.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_subscribe_to_another_user()
    {
        $user = User::factory()->create();

        $this->signIn();
        $this->post('/subscriptions/user/' . $user->id);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => auth()->id(),
            'subscribable_id' => $user->id
        ]);
    }

    /**
     * Tests that a user can unsubscribe from another user.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_unsubscribe_from_another_user()
    {
        $user = User::factory()->create();

        $this->signIn();
        $this->post('/subscriptions/user/' . $user->id);
        $this->delete('/subscriptions/user/' . $user->id);

        $this->assertDatabaseMissing('subscriptions', [
            'user_id' => auth()->id(),
            'subscribable_id' => $user->id
        ]);
    }

    /**
     * Tests that a user can see a list of all their story and user
     * subscriptions.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_see_all_their_subscriptions()
    {
        // Subscriptions to stories
        $stories = Story::factory()->count(5)->create();

        $this->signIn();

        foreach ($stories as $story) {
            $this->post('/subscriptions/story/' . $story->id);
        }

        // Subscriptions to users
        $users = User::factory()->count(2)->create();
        $story1 = Story::factory()->create(['user_id' => $users[0]->id]);
        $story2 = Story::factory()->create(['user_id' => $users[1]->id]);

        foreach ($users as $user) {
            $this->post('/subscriptions/user/' . $user->id);
        }

        $this->get('/subscriptions')
            ->assertSee($stories[0]->title)
            ->assertSee($stories[1]->title)
            ->assertSee($stories[2]->title)
            ->assertSee($stories[3]->title)
            ->assertSee($stories[4]->title)
            ->assertSee($story1->title)
            ->assertSee($story2->title);
    }

    /**
     * Tests that a user is notified when a story they are subscribed to
     * adds a new chapter.
     *
     * @return void
     */
    /** @test */
    public function a_user_is_notified_when_a_subscribed_story_updates()
    {
        Notification::fake();

        $story = StoryFactory::withChapters(1)
                    ->create();

        $this->signIn();
        $this->post('/subscriptions/story/' . $story->id);

        $story->addChapter('Chapter added');

        Notification::assertSentTo(
            [auth()->user()], 
            StoryUpdatedNotification::class,
            function ($notification, $channels) {
                return $notification->user->id === auth()->id();
            }
        );
    }

    /**
     * Tests that a user is notified when a user they are subscribed to adds
     * a new story or updates an existing one.
     *
     * @return void
     */
    /** @test */
    public function a_user_is_notified_when_a_subscribed_user_posts_or_updates()
    {
        Notification::fake();

        $story = StoryFactory::withChapters(1)
                    ->create();

        $user = User::factory()->create();

        $this->signIn($user);
        $this->post('/subscriptions/user/' . $story->user->id);

        $story->addChapter('Chapter added');

        Notification::assertSentTo(
            [auth()->user()], 
            StoryUpdatedNotification::class,
            function ($notification, $channels) {
                return $notification->user->id === auth()->id();
            }
        );

        $story2 = Story::factory()->raw(['user_id' => $story->user->id]);

        $this->actingAs($story->user)->post('/stories', [
            'title' => $story2['title'],
            'description' => $story2['description'],
            'type' => $story2['type'],
            'user_id' => $story2['user_id'],
            'first-chapter' => "A test first chapter body"
        ]);

        $this->signIn($user);

        Notification::assertSentTo(
            [auth()->user()], 
            StoryCreatedNotification::class,
            function ($notification, $channels) {
                return $notification->user->id === auth()->id();
            }
        );

    }
}
