<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'nom'       => fake()->name(),
            'email'     => fake()->safeEmail(),
            'telephone' => '6' . fake()->numerify('########'),
            'sujet'     => fake()->sentence(4),
            'contenu'   => fake()->paragraph(3),
            'lu'        => false,
            'lu_le'     => null,
        ];
    }

    public function lu(): static
    {
        return $this->state(fn () => [
            'lu'    => true,
            'lu_le' => now(),
        ]);
    }
}