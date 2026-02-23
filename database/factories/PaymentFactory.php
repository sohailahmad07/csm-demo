<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([PaymentStatus::Completed, PaymentStatus::Completed, PaymentStatus::Completed, PaymentStatus::Pending, PaymentStatus::Failed]);
        $paidAt = $status === PaymentStatus::Completed
            ? fake()->dateTimeBetween('-12 months', 'now')
            : null;

        return [
            'amount' => fake()->randomFloat(2, 50, 5000),
            'source' => fake()->randomElement(['ach', 'ach', 'credit_card', 'debit_card', 'echeck']),
            'checkout_type' => fake()->randomElement(['self_service', 'self_service', 'agent', 'payment_plan', 'ivr']),
            'status' => $status,
            'debtor_name' => fake()->name(),
            'reference' => strtoupper(fake()->bothify('TRT-####-????')),
            'paid_at' => $paidAt,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Completed,
            'paid_at' => fake()->dateTimeBetween('-12 months', 'now'),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Failed,
            'paid_at' => null,
        ]);
    }
}
