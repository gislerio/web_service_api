<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id'   => Category::inRandomOrder()->first(['id'])->id,
            'name'          => $this->faker->unique()->word,
            'description'   => $this->faker->sentence,
        ];
    }
}
