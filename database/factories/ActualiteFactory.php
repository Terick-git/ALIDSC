<?php

namespace Database\Factories;

use App\Models\Actualite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActualiteFactory extends Factory
{
    protected $model = Actualite::class;

    public function definition(): array
    {
        return [
            'titre' => fake()->sentence(5),
            'slug' => fake()->unique()->slug(),
            'extrait' => fake()->paragraph(2),
            'contenu' => fake()->paragraphs(4, true),
            'publie' => true,
            'interne' => false,
            'publie_le' => now(),
            'user_id' => User::factory(),
        ];
    }
}
