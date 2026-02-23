@props(['client'])
<div class="mb-6 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
    <div class="mb-3 flex items-center justify-between">
        <flux:heading size="sm">Onboarding Progress</flux:heading>
        <flux:text class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
            {{ $client->onboardingSteps->filter(fn ($s) => $s->isCompleted())->count() }}
            of {{ $client->onboardingSteps->count() }} steps complete
            &mdash; {{ $this->onboardingProgress }}%
        </flux:text>
    </div>
    <div class="h-3 w-full overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
        <div class="h-3 rounded-full bg-green-500 transition-all duration-500"
             style="width: {{ $this->onboardingProgress }}%"></div>
    </div>
</div>
