@props([
    'step'
])

<div {{ $attributes->class(['flex items-center gap-4 px-5 py-4 data-completed:bg-green-50/50 data-completed:dark:bg-green-950/10 data-overdue:bg-red-50/50 data-overdue:dark:bg-red-950/10']) }}
     data-onboarding-step {{ $step->isCompleted() ? 'data-completed': '' }} {{ $step->isOverdue() ? 'data-overdue' : '' }}>
    <button wire:click="$js.toggleStep($event , {{ $step->id }})">
        <div class="not-in-data-completed:hidden flex size-6 shrink-0 items-center justify-center rounded-full border-2 transition-colors border-green-500 bg-green-500 hover:bg-green-600 hover:border-green-600">
            <svg class="size-3 text-white" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="in-data-completed:hidden flex size-6 shrink-0 items-center justify-center rounded-full border-2 transition-colors border-zinc-300 hover:border-green-400 dark:border-zinc-600"></div>
    </button>

    <div class="flex-1">
        <flux:text
                class="font-medium in-data-completed:line-through in-data-completed:text-zinc-400 in-data-completed:dark:text-zinc-500 text-zinc-900 dark:text-white">{{ $step->name }}</flux:text>
        <flux:text
                class="text-xs text-green-600 dark:text-green-400 not-in-data-completed:hidden">
            Completed {{ $step->completed_at?->format('j M, Y') }}
        </flux:text>
        <flux:text class="text-xs text-red-500 not-in-data-overdue:hidden">Due
            {{ $step->due_at?->format('j M, Y') }} &mdash; overdue
        </flux:text>
        <flux:text
                class="text-xs text-zinc-400 dark:text-zinc-500 in-data-completed:hidden in-data-overdue:hidden">
            Due {{ $step->due_at?->format('j M, Y') }}</flux:text>
    </div>
    <span class="not-in-data-completed:hidden shrink-0 items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">Done</span>
    <span class="not-in-data-overdue:hidden shrink-0 items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">Overdue</span>
    <span class="in-data-completed:hidden in-data-overdue:hidden inline-flex shrink-0 items-center rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">Pending</span>
</div>
