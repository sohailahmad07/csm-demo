@island('activityTimeline')
<div {{ $attributes->class(['rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900']) }}>
    <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
        <flux:heading size="sm">Activity</flux:heading>
    </div>
    <div class="px-5 py-4">
        @forelse ($this->timeline as $event)
            <div wire:key="tl-{{ $loop->index }}"
                 class="relative flex gap-3 {{ $loop->last ? '' : 'pb-4' }}">
                @if (! $loop->last)
                    <div class="absolute left-3 top-6 bottom-0 w-px bg-zinc-100 dark:bg-zinc-800"></div>
                @endif
                @if ($event['type'] === 'step')
                    <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <svg class="size-3 text-green-600 dark:text-green-400" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                @else
                    <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                        <svg class="size-3 text-blue-600 dark:text-blue-400" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                @endif
                <div class="min-w-0 flex-1 pt-0.5">
                    <flux:text
                            class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $event['label'] }}</flux:text>
                    <flux:text
                            class="text-xs text-zinc-400 dark:text-zinc-500">{{ $event['date']->diffForHumans() }}</flux:text>
                </div>
            </div>
        @empty
            <flux:text class="text-sm text-zinc-400 dark:text-zinc-500">No activity yet.</flux:text>
        @endforelse
    </div>
</div>
@endisland
