<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Label;
use App\Models\PropertyLabel;
use App\Models\Property;
use App\Models\Service;
use App\Models\TypeProperty;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
        //Article::factory()->count(100)->create();
        //Category::factory()->count(3)->create();
        //Service::factory()->count(20)->create();

        //все для недвижимости
        TypeProperty::factory()->customLabels()->create();
        TypeProperty::factory()->customPriceLabel()->create();
        Property::factory()->count(20)->create();
        Label::factory()->customLabels()->create(); // Создает лейбл "Торг"
        Label::factory()->customPriceLabel()->create(); // Создает лейбл "Своя цена"
        PropertyLabel::factory()->count(5)->create();
    }
}
