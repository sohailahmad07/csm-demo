@island('clientsList', lazy: true)
@placeholder
<x-placeholder.table :rows="8" :columns="['Client', 'Plan', 'Status', 'Progress', 'Since Signup', '']">
    <colgroup>
        <col class="w-[30%]">
        <col class="w-[15%]">
        <col class="w-[15%]">
        <col class="w-[15%]">
        <col class="w-[15%]">
        <col class="w-[10%]">
    </colgroup>
</x-placeholder.table>
@endplaceholder
{{-- in livewire 4 currently the placeholder is not shown on a island re-render why i have a duplicate placeholder --}}
<div class="hidden group-[&:has([data-loading])]:block">
    <x-placeholder.table :rows="8" :columns="['Client', 'Plan', 'Status', 'Progress', 'Since Signup', '']">
        <colgroup>
            <col class="w-[30%]">
            <col class="w-[15%]">
            <col class="w-[15%]">
            <col class="w-[15%]">
            <col class="w-[15%]">
            <col class="w-[10%]">
        </colgroup>
    </x-placeholder.table>
</div>
<flux:table class="group-[&:has([data-loading])]:hidden border border-zinc-200 dark:border-white/10 rounded" :paginate="$this->clients">
    <colgroup>
        <col class="w-[30%]">
        <col class="w-[15%]">
        <col class="w-[15%]">
        <col class="w-[15%]">
        <col class="w-[15%]">
        <col class="w-[10%]">
    </colgroup>
    <flux:table.columns>
        <flux:table.column class="px-4!">Client</flux:table.column>
        <flux:table.column>Plan</flux:table.column>
        <flux:table.column>Status</flux:table.column>
        <flux:table.column>Progress</flux:table.column>
        <flux:table.column>Since Signup</flux:table.column>
        <flux:table.column></flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @forelse ($this->clients as $client)
            @php
                $progress = $this->onboardingProgress($client);
                $barColor = match(true) {
                    $progress >= 100 => 'bg-green-500',
                    $progress >= 50  => 'bg-blue-500',
                    default          => 'bg-amber-500',
                };
            @endphp
            <flux:table.row wire:key="{{ $client->id }}">
                <flux:table.cell class="px-4!">
                    <div class="font-medium text-zinc-900 dark:text-white">{{ $client->name }}</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $client->contact_name }}</div>
                </flux:table.cell>

                <flux:table.cell>
                    <flux:badge size="sm" :color="$client->plan->color()" inset="top bottom">
                        {{ $client->plan->label() }}
                    </flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    <flux:badge size="sm" :color="$client->status->color()" inset="top bottom">
                        {{ $client->status->label() }}
                    </flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-24 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                            <div class="h-2 rounded-full {{ $barColor }}" style="width: {{ $progress }}%"></div>
                        </div>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $progress }}%</span>
                    </div>
                </flux:table.cell>

                <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                    {{ $client->signed_up_at }}
                </flux:table.cell>

                <flux:table.cell>
                    <flux:button size="sm" variant="ghost" :href="route('clients.show', ['client' => $client->id])" wire:navigate inset="top bottom">
                        View →
                    </flux:button>
                </flux:table.cell>
            </flux:table.row>
        @empty
            <flux:table.row>
                <flux:table.cell colspan="6" class="py-12 text-center text-zinc-400 dark:text-zinc-500">
                    No clients match the selected filter.
                </flux:table.cell>
            </flux:table.row>
        @endforelse
    </flux:table.rows>
</flux:table>
@endisland
