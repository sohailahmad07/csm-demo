@props(['steps', 'group'])

@php
    $groupComplete = $steps->every(fn ($s) => $s->isCompleted());
    $groupDone = $steps->filter(fn ($s) => $s->isCompleted())->count();
    $groupCount = $steps->count();
    $groupOverdue = $steps->filter(fn ($s) => ! $s->isCompleted() && $s->due_at?->isPast())->count();
@endphp

<div {{ $attributes->class(['flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800']) }}>
    <div class="flex items-center gap-2"
         {{ $groupComplete ? 'data-completed' : null }} data-onboarding-group>
        <div class="flex not-in-data-completed:hidden flux-menu:[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current= size-5 items-center justify-center rounded-full bg-green-500">
            <svg class="size-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="size-5 in-data-completed:hidden rounded-full border-2 border-zinc-300 dark:border-zinc-600"></div>
        <flux:heading size="sm">{{ $this->groupLabel($group) }}</flux:heading>
        @if ($groupOverdue > 0)
            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    {{ $groupOverdue }} overdue
                                </span>
        @endif
    </div>
    <flux:text class="text-sm text-zinc-500">{{ $groupDone }}/{{ $groupCount }}</flux:text>
</div>
