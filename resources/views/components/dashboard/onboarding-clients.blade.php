<div class="rounded-xl border border-amber-200 bg-white dark:border-amber-900/40 dark:bg-zinc-900">
    <div class="flex items-center gap-2 border-b border-amber-100 px-5 py-4 dark:border-amber-900/30">
        <div class="size-2 rounded-full bg-amber-500"></div>
        <flux:heading size="sm">In Onboarding</flux:heading>
        <span class="ml-auto inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">{{ $this->onboardingCount }}</span>
    </div>
    @forelse ($this->onboardingClients as $client)
        @php $progress = $client->onboardingProgressPercent(); @endphp
        <div wire:key="onb-{{ $client->id }}" class="flex items-center gap-4 border-b border-zinc-100 px-5 py-3 last:border-0 dark:border-zinc-800">
            <div class="min-w-0 flex-1">
                <div class="truncate font-medium text-zinc-900 dark:text-white">{{ $client->name }}</div>
                <div class="mt-1 flex items-center gap-2">
                    <div class="h-1.5 w-20 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                        <div class="h-1.5 rounded-full bg-amber-400" style="width: {{ $progress }}%"></div>
                    </div>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $progress }}% &middot; {{ $client->signed_up_at }}</span>
                </div>
            </div>
            <flux:button size="sm" variant="ghost" :href="route('clients.show', $client->id)" wire:navigate>View →</flux:button>
        </div>
    @empty
        <div class="px-5 py-8 text-center text-sm text-zinc-400 dark:text-zinc-500">No clients onboarding</div>
    @endforelse
</div>
