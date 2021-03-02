<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $possible_styles = ['primary','secondary','success','danger','warning','info','dark','light'];

        return [
            'name' => $this->faker->word,
            'style' => $this->faker->randomElement($possible_styles),
        ];
    }
}
