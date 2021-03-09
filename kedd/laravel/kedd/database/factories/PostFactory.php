<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => Str::ucfirst($this->faker->words($this->faker->numberBetween(2,6), true)),
            //'title' => trim($faker->sentence, '.'),
            'text' => $this->faker->paragraphs($this->faker->numberBetween(1,4), true),
        ];
    }
}
