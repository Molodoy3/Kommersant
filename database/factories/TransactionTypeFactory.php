<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionType>
 */
class TransactionTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

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
