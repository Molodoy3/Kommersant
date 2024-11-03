<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6, true), // Генерация случайного заголовка
            'description' => $this->faker->paragraph(3, true), // Генерация случайного описания
            'image' => 'image.jpeg', // Генерация случайного URL изображения
        ];
    }
}
