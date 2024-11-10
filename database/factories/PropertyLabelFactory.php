<?php

namespace Database\Factories;

use App\Models\Label;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyLabel>
 */
class PropertyLabelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'property_id' => random_int(1, 20),
            'label_id' => random_int(1, 2),
        ];
    }
}
