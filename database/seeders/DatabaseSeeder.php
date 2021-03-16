<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed comment - each will be created with a chapter, which will be
        // created with a story, which will be created with a user

        $comments = \App\Models\Comment::factory(30)->create();
        $tags = \App\Models\Tag::factory(5)->create();

        foreach ($comments as $comment) {
            // Attach tags to stories
            $comment->chapter->story->updateTags($tags);
        }
    }
}
