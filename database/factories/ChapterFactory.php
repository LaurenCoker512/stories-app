<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->paragraph,
            'story_id' => function() {
                return Story::factory()->create()->id;
            }
        ];
    }
}
