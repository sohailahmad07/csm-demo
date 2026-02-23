<?php

use App\Enums\ClientStatus;
use App\Enums\PaymentStatus;
use App\Models\Client;
use App\Models\OnboardingStep;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Async;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Agency Detail')]
class extends Component
{
    public Client $client;

    public string $notes = '';

    public function mount(): void
    {
        $this->notes = $this->client->notes ?? '';
    }

    #[Computed]
    public function onboardingProgress(): int
    {
        $steps = $this->client->onboardingSteps;

        if ($steps->isEmpty()) {
            return 0;
        }

        return (int) round($steps->filter(fn ($s) => $s->isCompleted())->count() / $steps->count() * 100);
    }

    #[Computed]
    public function totalCollected(): float
    {
        return (float) $this->client->payments()->where('status', PaymentStatus::Completed)->sum('amount');
    }

    #[Computed]
    public function thisMonthCollected(): float
    {
        return (float) $this->client->payments()
            ->where('status', PaymentStatus::Completed)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');
    }

    #[Computed]
    public function paymentCount(): int
    {
        return $this->client->payments()->count();
    }

    #[Computed]
    public function goalProgress(): int
    {
        $goal = (float) $this->client->monthly_goal;

        if ($goal <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->thisMonthCollected / $goal) * 100));
    }

    /** @return \Illuminate\Database\Eloquent\Collection<OnboardingStep> */
    #[Computed]
    public function groupedSteps()
    {
        return $this->client->onboardingSteps()
            ->orderBy('group_position')
            ->orderBy('item_position')
            ->get()
            ->groupBy('group');
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Payment> */
    #[Computed]
    public function recentPayments()
    {
        return $this->client->payments()->latest('paid_at')->limit(5)->get();
    }

    /** @return Collection<int, array{date: Carbon, type: string, label: string}> */
    #[Computed]
    public function timeline(): Collection
    {
        $events = collect();

        foreach ($this->client->onboardingSteps->filter(fn ($s) => $s->isCompleted()) as $step) {
            $events->push([
                'date' => $step->completed_at,
                'type' => 'step',
                'label' => $step->name,
            ]);
        }

        if ($this->client->notes && $this->client->updated_at) {
            $events->push([
                'date' => $this->client->updated_at,
                'type' => 'note',
                'label' => 'CSM note updated',
            ]);
        }

        return $events->sortByDesc('date')->values()->take(8);
    }

    public function saveNotes(): void
    {
        $this->client->update(['notes' => $this->notes]);
        $this->renderIsland('activityTimeline');
    }

    #[Async]
    public function updateStatus(string $status): void
    {
        $clientStatus = ClientStatus::tryFrom($status);
        abort_unless($clientStatus !== null, 422);
        $this->client->update(['status' => $clientStatus]);
    }

    public function toggleStep(int $id): void
    {
        $step = OnboardingStep::findOrFail($id);
        if (! $step->isCompleted()) {
            $step->update(['completed_at' => now()]);
        } else {
            $step->update(['completed_at' => null]);
        }

        $this->renderIsland('step-status');
        $this->renderIsland('activityTimeline');

    }

    private function groupLabel(string $group): string
    {
        return match ($group) {
            'account_setup' => 'Account Setup',
            'payment_configuration' => 'Payment Config',
            'campaign_setup' => 'Campaign Setup',
            'go_live' => 'Go Live',
            default => ucwords(str_replace('_', ' ', $group)),
        };
    }
};
