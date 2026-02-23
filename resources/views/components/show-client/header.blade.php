@php use App\Enums\ClientStatus;use App\Models\Client; @endphp
@props([
    'client'
])

<div {{ $attributes->class(['mb-6']) }}>
    <div class="mb-2 flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
        <a href="{{ route('clients.index') }}" wire:navigate
           class="hover:text-zinc-900 dark:hover:text-white">Clients</a>
        <span>/</span>
        <span class="text-zinc-900 dark:text-white">{{ $client->name }}</span>
    </div>

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <flux:heading size="xl">{{ $client->name }}</flux:heading>
                <flux:badge :color="$client->plan->color()">
                    {{ $client->plan->label() }}
                </flux:badge>
            </div>
            <flux:text class="mt-1 text-zinc-500 dark:text-zinc-400">
                {{ $client->contact_name }} &middot; {{ $client->contact_email }}
            </flux:text>
        </div>

        <div class="flex items-center gap-2" x-data="{status: '{{ $client->status }}'}">
            <flux:radio.group x-model="status" @input="$wire.updateStatus(status)" label="Status" variant="pills">
                @foreach(ClientStatus::cases() as $clientStatus)
                    <flux:radio value="{{ $clientStatus->value }}" label="{{ $clientStatus->label() }}"/>
                @endforeach
            </flux:radio.group>
        </div>
    </div>
</div>
