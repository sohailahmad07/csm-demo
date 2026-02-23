@php use App\Models\Client; @endphp
@props([
    'client'
])
<div class="mb-6 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">

    <div {{ $attributes->class(['mb-3 flex items-center justify-between']) }}>
        <flux:heading size="sm">Monthly Goal Progress</flux:heading>
        <flux:text class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
            {{ $this->goalProgress }}% &mdash; ${{ number_format($this->thisMonthCollected, 0) }} of
            ${{ number_format($client->monthly_goal, 0) }}
        </flux:text>
    </div>
    <div {{ $attributes->class(['h-3 w-full overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800']) }}>
        @php
            $goalBarColor = match(true) {
                $this->goalProgress >= 100 => 'bg-green-500',
                $this->goalProgress >= 60  => 'bg-blue-500',
                $this->goalProgress >= 30  => 'bg-amber-500',
                default                    => 'bg-red-400',
            };
        @endphp
        <div class="h-3 rounded-full transition-all duration-500 {{ $goalBarColor }}"
             style="width: {{ $this->goalProgress }}%"></div>
    </div>
</div>
