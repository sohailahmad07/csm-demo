@props(['columns', 'rows' => 5])
<flux:table {{ $attributes->class(['border border-zinc-200 dark:border-white/10 rounded w-full']) }}>
    {{ $slot }}
    <flux:table.columns>
        @foreach($columns as $column)
            <flux:table.column class="px-4!">{{ $column }}</flux:table.column>
        @endforeach
    </flux:table.columns>
    <flux:table.rows>
        @for($i = 1; $i <= $rows; $i++)
            <flux:table.row>
            @foreach($columns as $column)
                <flux:table.cell class="first:px-4! last:px-4!">
                    <flux:skeleton.line animate="shimmer" size="lg"/>
                </flux:table.cell>
            @endforeach
            </flux:table.row>
        @endfor
    </flux:table.rows>
</flux:table>
