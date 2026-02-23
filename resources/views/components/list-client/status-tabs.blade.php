<div class="mb-4">
    <flux:input wire:model.live.debounce.200ms="search" wire:island="clientsList" placeholder="Search clients..." icon="magnifying-glass"/>
</div>

<div class="mb-6 flex gap-1 border-b border-zinc-200 dark:border-zinc-700">
    @foreach (['all' => 'All', 'onboarding' => 'Onboarding', 'active' => 'Active', 'at_risk' => 'At Risk'] as $value => $label)
        <button wire:click="setStatusFilter('{{ $value }}')" wire:island="clientsList"
                id="tab-button"
                class="flex items-center gap-x-2 justify-center"
                :class="{
                'border-b-2 border-zinc-900 px-4 py-2 text-sm font-semibold text-zinc-900 dark:border-white dark:text-white' : $wire.statusFilter === '{{ $value }}',
                'px-4 py-2 text-sm font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' : $wire.statusFilter !== '{{ $value }}'
                 }"
        >
            {{ $label }}
            <flux:icon.loading variant="micro" class="dark:text-white text-zinc-900 not-in-data-loading:hidden"/>
        </button>
    @endforeach
</div>
