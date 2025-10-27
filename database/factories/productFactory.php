<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\products>
 */
class productFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(maxNbChars:10),
            'code' => $this->faker->numberBetween(111111111,99999999),
            'model' => $this->faker->numberBetween(2005, 2020),
            'brand' => $this->faker->realText(maxNbChars:5),
            'pprice' => $this->faker->numberBetween(500, 5000),
            'price' => $this->faker->numberBetween(500, 5000),
            'alert' => $this->faker->numberBetween(10, 100),
        ];
    }
}
