@props(['step'])

<div class="mb-8 flex items-center gap-3">
    <div @class([
        'flex items-center gap-2 text-sm font-semibold',
        'text-zinc-900 dark:text-white' => $step === 1,
        'text-zinc-400 dark:text-zinc-500' => $step !== 1,
    ])>
        <span @class([
            'flex size-6 items-center justify-center rounded-full text-xs font-bold',
            'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' => $step === 1,
            'bg-green-500 text-white' => $step > 1,
            'bg-zinc-200 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' => $step < 1,
        ])>
            @if ($step > 1)
                <flux:icon.check class="size-3"/>
            @else
                1
            @endif
        </span>
        General Info
    </div>

    <div class="h-px w-8 bg-zinc-200 dark:bg-zinc-700"></div>

    <div @class([
        'flex items-center gap-2 text-sm font-semibold',
        'text-zinc-900 dark:text-white' => $step === 2,
        'text-zinc-400 dark:text-zinc-500' => $step !== 2,
    ])>
        <span @class([
            'flex size-6 items-center justify-center rounded-full text-xs font-bold',
            'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' => $step === 2,
            'bg-zinc-200 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' => $step < 2,
        ])>2</span>
        Onboarding Steps
    </div>
</div>
