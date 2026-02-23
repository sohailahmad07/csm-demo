<?php

use App\Enums\ClientStatus;
use App\Models\Client;
use Illuminate\Contracts\Pagination\Paginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Agency Onboarding')] class extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';

    public string $search = '';

    /**@return Paginator<int, Client> */
    #[Computed]
    public function clients(): Paginator
    {
        return Client::query()
            ->with(['onboardingSteps'])
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', ClientStatus::from($this->statusFilter)))
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(5);
    }

    public function setStatusFilter(string $filter): void
    {
        $this->statusFilter = $filter;
    }

    public function onboardingProgress(Client $client): int
    {
        $steps = $client->onboardingSteps;

        if ($steps->isEmpty()) {
            return 0;
        }

        return (int) round($steps->filter(fn ($s) => $s->isCompleted())->count() / $steps->count() * 100);
    }
};
