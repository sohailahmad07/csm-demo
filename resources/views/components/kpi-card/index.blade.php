@props([
    'title' => null,
    'value' => null,
    'description' => null,
    'valueClass' => 'text-zinc-900 dark:text-white',
    'descriptionClass' => 'text-zinc-500 dark:text-zinc-400',
])

<div {{ $attributes->class(['rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900']) }}>
    @if($title)
        <flux:text class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $title }}</flux:text>
    @endif

    @if($value)
        <div class="mt-1 text-2xl font-bold {{ $valueClass }}">{{ $value }}</div>
    @endif

    @if($description)
        <flux:text class="mt-1 text-xs {{ $descriptionClass }}">{{ $description }}</flux:text>
    @endif
</div>
