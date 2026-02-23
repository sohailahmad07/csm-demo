@php use App\Models\Client; @endphp
@props([
    'client'
])

@island('notes', lazy: true)
@placeholder
<div {{ $attributes->class(['rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900']) }}>
    <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
        <flux:heading size="sm">CSM Notes</flux:heading>
    </div>
    <div class="p-5">
        <flux:skeleton.line animate="shimmer" class="h-48"/>
    </div>
</div>
@endplaceholder
<div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
    <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
        <flux:heading size="sm">CSM Notes</flux:heading>
    </div>
    <div class="p-5">
        <flux:textarea
                wire:model="notes"
                rows="5"
                placeholder="Add notes about this client's onboarding progress..."
                class="w-full"
        />
        <div class="mt-3 flex items-center justify-between">
            @if ($client->updated_at)
                <flux:text class="text-xs text-zinc-400 dark:text-zinc-500">
                    Updated {{ $client->updated_at->diffForHumans() }}
                </flux:text>
            @else
                <span></span>
            @endif
            <flux:button wire:click="saveNotes" size="sm" variant="primary">Save Note</flux:button>
        </div>
    </div>
</div>
@endisland
