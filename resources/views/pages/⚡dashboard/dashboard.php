<?php

use App\Enums\ClientStatus;
use App\Enums\PaymentStatus;
use App\Models\Client;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')] class extends Component
{
    #[Computed]
    public function totalClients(): int
    {
        return Client::query()->count();
    }

    #[Computed]
    public function activeClients(): int
    {
        return Client::query()->where('status', ClientStatus::Active)->count();
    }

    #[Computed]
    public function atRiskCount(): int
    {
        return Client::query()->where('status', ClientStatus::AtRisk)->count();
    }

    #[Computed]
    public function onboardingCount(): int
    {
        return Client::query()->where('status', ClientStatus::Onboarding)->count();
    }

    #[Computed]
    public function totalCollected(): float
    {
        return (float) Payment::query()->where('status', PaymentStatus::Completed)->sum('amount');
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Client> */
    #[Computed]
    public function atRiskClients()
    {
        return Client::query()
            ->with('onboardingSteps')
            ->where('status', ClientStatus::AtRisk)
            ->orderBy('name')
            ->get();
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Client> */
    #[Computed]
    public function onboardingClients()
    {
        return Client::query()
            ->with('onboardingSteps')
            ->where('status', ClientStatus::Onboarding)
            ->orderBy('name')
            ->get();
    }

    /** @return array<int, array{month: string, total: float}> */
    #[Computed]
    public function monthlyChartData(): array
    {
        $months = collect(range(5, 0))->map(function (int $i) {
            $date = now()->subMonths($i);

            $total = Payment::query()
                ->where('status', PaymentStatus::Completed)
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('amount');

            return [
                'month' => $date->format('M Y'),
                'total' => round((float) $total, 2),
            ];
        });

        return $months->values()->all();
    }
};
