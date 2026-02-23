<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnboardingStep>
 */
class OnboardingStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'name' => fake()->sentence(3),
            'group' => fake()->randomElement(['account_setup', 'payment_configuration', 'campaign_setup', 'go_live']),
            'item_position' => fake()->numberBetween(1, 10),
            'group_position' => fake()->numberBetween(1, 4),
            'help_url' => fake()->url(),
            'completed_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-3 months', 'now') : null,
            'due_at' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => null,
        ]);
    }
}
