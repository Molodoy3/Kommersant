<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text(2000),
            'prise' => $this->faker->numberBetween(1000, 100000),
            'address' => $this->faker->address,
            'square' => $this->faker->randomFloat(2, 0, 500),
            'type_property_id' => rand(1, 2),
            'transaction_type_id' => rand(1, 2),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'link' => $this->faker->url,
        ];
    }
}
