<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livro>
 */
class LivroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Titulo' => fake()->sentence(3),
            'Editora' => fake()->company(),
            'Edicao' => fake()->numberBetween(1, 10),
            'AnoPublicacao' => fake()->year(),
            'Valor' => fake()->randomFloat(2, 10, 200),
        ];
    }
}
