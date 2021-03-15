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
        // Seed chapters - each will be created with a story, which will be
        // created with a user
        $chapters = \App\Models\Chapter::factory(30)->create();
        $tags = \App\Models\Tag::factory(5)->create();

        foreach ($chapters as $chapter) {
            // Attach tags to chapter stories
            $chapter->story->updateTags($tags);
        }
    }
}
