<?php

use App\Enums\ClientPlan;
use App\Enums\ClientStatus;
use App\Livewire\Component;
use App\Models\Client;
use App\Models\OnboardingStep;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new #[Title('New Client')]
class extends Component
{
    public int $step = 1;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $contactName = '';

    #[Validate('required|email|max:255')]
    public string $contactEmail = '';

    #[Validate('required|in:starter,growth,enterprise')]
    public string $plan = 'starter';

    #[Validate('required|numeric|min:0')]
    public string $monthlyGoal = '';

    #[Validate('required|date|after:today')]
    public string $goLiveAt = '';

    #[Validate([
        'steps' => 'required|array|min:1',
        'steps.*.*.name' => 'required|string|max:255',
        'steps.*.*.group' => 'required|string|max:255',
        'steps.*.*.due_at' => 'required|after:today|date',
    ], attribute: [
        'steps.*.*.name' => 'step name',
        'steps.*.*.due_at' => 'due date',
    ])]
    public array $steps = [];

    public array $groupOrder = [];

    public string $groupName = '';

    public function mount(): void
    {
        $this->steps = collect(OnboardingStep::TEMPLATES)
            ->map(fn ($template, $index) => [
                'id' => (string) str()->uuid(),
                'name' => $template['name'],
                'group' => $template['group'],
                'due_at' => now()->addDays($index + 1)->format('Y-m-d'),
            ])
            ->groupBy('group')
            ->toArray();

        $this->groupOrder = array_keys($this->steps);
    }

    public function nextStep(): void
    {
        $this->validateField(['name', 'contactName', 'contactEmail', 'plan', 'monthlyGoal', 'goLiveAt']);
        $this->step = 2;
    }

    public function save(): void
    {
        $this->validate();
        $client = Client::create([
            'name' => $this->name,
            'contact_name' => $this->contactName,
            'contact_email' => $this->contactEmail,
            'plan' => ClientPlan::from($this->plan),
            'status' => ClientStatus::Onboarding,
            'monthly_goal' => (float) $this->monthlyGoal,
            'go_live_at' => $this->goLiveAt ?: null,
        ]);

        $itemPosition = 1;
        $groupPosition = 1;
        foreach ($this->groupOrder as $group) {
            foreach ($this->steps[$group] ?? [] as $row) {
                OnboardingStep::create([
                    'client_id' => $client->id,
                    'name' => $row['name'],
                    'group' => $row['group'],
                    'item_position' => $itemPosition++,
                    'group_position' => $groupPosition,
                    'help_url' => '#',
                    'due_at' => $row['due_at'] ?: null,
                ]);
            }
            $groupPosition++;
        }

        $this->redirect(route('clients.show', $client), navigate: true);
    }
};
