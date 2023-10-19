<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacancy>
 */
class VacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'salary' => fake()->numberBetween(10000, 100000),
            'location' => fake()->city(),
            'company' => fake()->company(),
            'description' => fake()->paragraph(rand(1, 20)),
        ];
    }
}
