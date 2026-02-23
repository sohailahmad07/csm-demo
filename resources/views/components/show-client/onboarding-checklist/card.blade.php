@props([
    'steps',
    'group'
])

<div {{ $attributes->class(['rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900']) }}>
    <x-show-client.onboarding-checklist.header :steps="$steps" :group="$group"/>
    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
        @foreach ($steps as $step)
            <x-show-client.onboarding-checklist.item wire:key="{{ $step->id }}" :step="$step"/>
        @endforeach
    </div>
</div>
