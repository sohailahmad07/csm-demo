<div class="mb-6 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:heading size="sm" class="mb-4">Monthly Collections — Last 6 Months</flux:heading>
    <div
        wire:key="chart-{{ collect($this->monthlyChartData)->pluck('total')->implode('-') }}"
        x-data="{
            chart: null,
            init() {
                const data = @js($this->monthlyChartData);
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
                const labelColor = isDark ? '#a1a1aa' : '#71717a';

                this.chart = new Chart(this.$refs.canvas, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.month),
                        datasets: [{
                            label: 'Collected ($)',
                            data: data.map(d => d.total),
                            backgroundColor: isDark ? 'rgba(99,102,241,0.7)' : 'rgba(99,102,241,0.8)',
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => '$' + ctx.parsed.y.toLocaleString()
                                }
                            }
                        },
                        scales: {
                            x: { grid: { color: gridColor }, ticks: { color: labelColor } },
                            y: {
                                grid: { color: gridColor },
                                ticks: {
                                    color: labelColor,
                                    callback: v => '$' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                                }
                            }
                        }
                    }
                });
            }
        }"
    >
        <canvas x-ref="canvas" height="90"></canvas>
    </div>
</div>
