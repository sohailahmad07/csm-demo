<?php

namespace Database\Factories;

use App\Enums\ClientPlan;
use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([ClientStatus::Onboarding, ClientStatus::Active, ClientStatus::Active, ClientStatus::Active, ClientStatus::AtRisk]);
        $goLiveAt = $status !== ClientStatus::Onboarding
            ? fake()->dateTimeBetween('-18 months', '-1 month')
            : null;

        return [
            'name' => fake()->company().' Collections',
            'contact_name' => fake()->name(),
            'contact_email' => fake()->companyEmail(),
            'plan' => fake()->randomElement([ClientPlan::Starter, ClientPlan::Growth, ClientPlan::Growth, ClientPlan::Enterprise]),
            'status' => $status,
            'monthly_goal' => fake()->randomElement([25000, 50000, 75000, 100000, 150000, 200000]),
            'go_live_at' => $goLiveAt,
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ClientStatus::Active,
            'go_live_at' => fake()->dateTimeBetween('-12 months', '-1 month'),
        ]);
    }

    public function onboarding(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ClientStatus::Onboarding,
            'go_live_at' => null,
        ]);
    }

    public function atRisk(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ClientStatus::AtRisk,
            'go_live_at' => fake()->dateTimeBetween('-18 months', '-6 months'),
        ]);
    }
}
