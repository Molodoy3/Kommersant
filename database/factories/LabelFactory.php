<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Label>
 */
class LabelFactory extends Factory
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
        ];
    }
    public function customLabels()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Торг',
                'color' => 'cca81d',
            ];
        });
    }

    public function customPriceLabel()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Низкая цена',
                'color' => '52AF35',
            ];
        });
    }
}
