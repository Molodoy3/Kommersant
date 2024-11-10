<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TypeProperty>
 */
class TypePropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'name'
        ];
    }
    public function customLabels()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Аренда',
            ];
        });
    }

    public function customPriceLabel()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Продажа',
            ];
        });
    }
}
