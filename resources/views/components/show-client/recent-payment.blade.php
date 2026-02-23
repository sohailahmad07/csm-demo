<div {{ $attributes->class(['rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900']) }}>
    <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
        <flux:heading size="sm">Recent Payments</flux:heading>
    </div>
    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
        @forelse ($this->recentPayments as $payment)
            <div wire:key="{{ $payment->id }}" class="px-5 py-3">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-zinc-900 dark:text-white">
                            ${{ number_format($payment->amount, 2) }}</div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $payment->debtor_name }}</div>
                        <div class="text-xs text-zinc-400 dark:text-zinc-500">{{ $payment->paid_at ? $payment->paid_at->format('j M, Y') : '—' }}</div>
                    </div>
                    <flux:badge size="sm" :color="$payment->status->color()" inset="top bottom">
                        {{ $payment->status->label() }}
                    </flux:badge>
                </div>
            </div>
        @empty
            <div class="px-5 py-8 text-center text-sm text-zinc-400 dark:text-zinc-500">
                No payments yet
            </div>
        @endforelse
    </div>
</div>
