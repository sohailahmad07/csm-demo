<?php

namespace Database\Seeders;

use App\Enums\ClientPlan;
use App\Enums\ClientStatus;
use App\Enums\PaymentStatus;
use App\Models\Client;
use App\Models\OnboardingStep;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /** @var array<int, array{name: string, contact: string, email: string, plan: ClientPlan, status: ClientStatus, goal: int, stepsCompleted: int, goLiveDays: int|null, notes: string}> */
    private array $agencies = [
        [
            'name' => 'Apex Recovery Group',
            'contact' => 'Sarah Chen',
            'email' => 'schen@apexrecovery.com',
            'plan' => ClientPlan::Enterprise,
            'status' => ClientStatus::Active,
            'goal' => 200000,
            'stepsCompleted' => 10,
            'goLiveDays' => 180,
            'notes' => 'Flagship enterprise client. Fully live and performing above goal. Quarterly business review scheduled for next month.',
        ],
        [
            'name' => 'Midwest Collections LLC',
            'contact' => 'James Porter',
            'email' => 'jporter@midwestcoll.com',
            'plan' => ClientPlan::Growth,
            'status' => ClientStatus::Active,
            'goal' => 75000,
            'stepsCompleted' => 10,
            'goLiveDays' => 90,
            'notes' => 'Solid mid-market client. Consistently hitting 85-90% of monthly goal.',
        ],
        [
            'name' => 'FirstCall Debt Solutions',
            'contact' => 'Maria Santos',
            'email' => 'msantos@firstcall.io',
            'plan' => ClientPlan::Growth,
            'status' => ClientStatus::Active,
            'goal' => 100000,
            'stepsCompleted' => 8,
            'goLiveDays' => 45,
            'notes' => 'Recently went live. Still needs to complete campaign setup. Follow up on import accounts step.',
        ],
        [
            'name' => 'Coastal Credit Services',
            'contact' => 'Derek Williams',
            'email' => 'dwilliams@coastalcredit.com',
            'plan' => ClientPlan::Starter,
            'status' => ClientStatus::AtRisk,
            'goal' => 25000,
            'stepsCompleted' => 6,
            'goLiveDays' => 120,
            'notes' => 'Went live but engagement has stalled. Very few payments coming through. Schedule a check-in call this week.',
        ],
        [
            'name' => 'BluePeak Debt Agency',
            'contact' => 'Amanda Rivera',
            'email' => 'arivera@bluepeak.com',
            'plan' => ClientPlan::Starter,
            'status' => ClientStatus::Onboarding,
            'goal' => 30000,
            'stepsCompleted' => 4,
            'goLiveDays' => null,
            'notes' => 'Stuck on payment configuration. Amanda mentioned they are waiting on bank approval for ACH.',
        ],
        [
            'name' => 'Harbor Point Recovery',
            'contact' => 'Tom Hayes',
            'email' => 'thayes@harborpoint.com',
            'plan' => ClientPlan::Growth,
            'status' => ClientStatus::Onboarding,
            'goal' => 60000,
            'stepsCompleted' => 2,
            'goLiveDays' => null,
            'notes' => 'Just signed up. Initial kickoff call completed. Tom is working on email verification.',
        ],
    ];

    public function run(): void
    {
        foreach ($this->agencies as $agency) {
            $goLiveAt = $agency['goLiveDays'] !== null
                ? now()->subDays($agency['goLiveDays'])
                : now()->addDays(rand(30, 90));

            $client = Client::create([
                'name' => $agency['name'],
                'contact_name' => $agency['contact'],
                'contact_email' => $agency['email'],
                'plan' => $agency['plan'],
                'status' => $agency['status'],
                'monthly_goal' => $agency['goal'],
                'go_live_at' => $goLiveAt,
                'notes' => $agency['notes'],
            ]);

            $this->seedOnboardingSteps($client, $agency['stepsCompleted']);
            $this->seedPayments($client, $agency['status'], $agency['goLiveDays']);
        }
    }

    private function seedOnboardingSteps(Client $client, int $stepsCompleted): void
    {
        foreach (OnboardingStep::TEMPLATES as $index => $template) {
            $done = ($index + 1) <= $stepsCompleted;

            OnboardingStep::create([
                'client_id' => $client->id,
                'slug' => str($template['name'])->slug()->value(),
                'name' => $template['name'],
                'group' => $template['group'],
                'group_position' => $template['group_position'],
                'item_position' => $template['position'],
                'help_url' => '#',
                'completed_at' => $done ? now()->subDays(rand(5, 60)) : null,
                'due_at' => now()->addDays(rand(3, 14)),
            ]);
        }
    }

    private function seedPayments(Client $client, ClientStatus $status, ?int $goLiveDays): void
    {
        if ($status === ClientStatus::Onboarding) {
            return;
        }

        $months = min(12, (int) round(($goLiveDays ?? 30) / 30));
        $paymentsPerMonth = $status === ClientStatus::AtRisk ? [5, 15] : [30, 80];

        for ($month = $months - 1; $month >= 0; $month--) {
            $start = now()->subMonths($month)->startOfMonth()->toDateTimeString();
            $end = now()->subMonths($month)->endOfMonth()->toDateTimeString();
            $count = rand($paymentsPerMonth[0], $paymentsPerMonth[1]);

            for ($i = 0; $i < $count; $i++) {
                $this->createPayment($client, $start, $end);
            }
        }
    }

    private function createPayment(Client $client, string $start, string $end): void
    {
        $paymentStatus = $this->weightedRandom([
            PaymentStatus::Completed->value => 80,
            PaymentStatus::Pending->value => 12,
            PaymentStatus::Failed->value => 8,
        ]);
        $paidAt = $paymentStatus === PaymentStatus::Completed->value
            ? fake()->dateTimeBetween($start, $end)
            : null;

        Payment::create([
            'client_id' => $client->id,
            'amount' => $this->realisticAmount(),
            'source' => $this->weightedRandom(['ach' => 45, 'credit_card' => 30, 'debit_card' => 15, 'echeck' => 10]),
            'checkout_type' => $this->weightedRandom([
                'self_service' => 50,
                'agent' => 25,
                'payment_plan' => 15,
                'ivr' => 10,
            ]),
            'status' => $paymentStatus,
            'debtor_name' => fake()->name(),
            'reference' => strtoupper(fake()->bothify('TRT-####-????')),
            'paid_at' => $paidAt,
            'created_at' => fake()->dateTimeBetween($start, $end),
        ]);
    }

    private function realisticAmount(): float
    {
        $tier = rand(1, 100);

        return match (true) {
            $tier <= 40 => round(fake()->randomFloat(2, 50, 500), 2),
            $tier <= 70 => round(fake()->randomFloat(2, 500, 2000), 2),
            $tier <= 90 => round(fake()->randomFloat(2, 2000, 4000), 2),
            default => round(fake()->randomFloat(2, 4000, 5000), 2),
        };
    }

    /** @param  array<string, int>  $weights */
    private function weightedRandom(array $weights): string
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $cumulative = 0;

        foreach ($weights as $value => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $value;
            }
        }

        return array_key_first($weights);
    }
}
