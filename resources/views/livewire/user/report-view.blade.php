<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="font-semibold">{{ $report->name }}</flux:heading>
                @if($report->description)
                    <flux:subheading>{{ $report->description }}</flux:subheading>
                @endif
            </div>
            <flux:button variant="ghost" wire:navigate href="{{ route('user.reports.index') }}" icon="arrow-left">
                {{ __('Back to Reports') }}
            </flux:button>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <x-banner class="mb-6">
            {{ session('message') }}
        </x-banner>
    @endif

    @if (session()->has('error'))
        <x-banner variant="danger" class="mb-6">
            {{ session('error') }}
        </x-banner>
    @endif

    <div class="space-y-6">
        <!-- Actions Bar -->
        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                <flux:button variant="outline" wire:click="refresh" icon="arrow-path" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="refresh">{{ __('Refresh') }}</span>
                    <span wire:loading wire:target="refresh">{{ __('Loading...') }}</span>
                </flux:button>
            </div>

            <div class="flex gap-2">
                <flux:button variant="outline" wire:click="exportCsv" icon="arrow-down-tray">
                    {{ __('Export CSV') }}
                </flux:button>
            </div>
        </div>

        <!-- Loading State -->
        @if($loading)
            <x-card>
                <x-card.content>
                    <div class="text-center py-12">
                        <flux:icon.arrow-path class="h-12 w-12 animate-spin text-gray-400 mx-auto" />
                        <p class="mt-4 text-sm text-gray-500">{{ __('Loading report data...') }}</p>
                    </div>
                </x-card.content>
            </x-card>
        @elseif($error)
            <!-- Error State -->
            <x-banner variant="danger">
                {{ $error }}
            </x-banner>
        @elseif(count($results) > 0)
            <!-- Report Content -->
            @if($report->visualization_type === 'chart' && $report->chart_config)
                <!-- Chart Visualization -->
                <x-card>
                    <x-card.content>
                        <div>
                            <canvas id="reportChart" class="max-h-96"></canvas>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const ctx = document.getElementById('reportChart');
                                if (ctx) {
                                    const config = @json($report->chart_config);
                                    const data = @json($results);
                                    
                                    const labels = data.map(row => row[config.label_column]);
                                    const values = data.map(row => parseFloat(row[config.data_column]) || 0);

                                    new Chart(ctx, {
                                        type: config.type,
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: config.data_column,
                                                data: values,
                                                backgroundColor: [
                                                    'rgba(59, 130, 246, 0.5)',
                                                    'rgba(16, 185, 129, 0.5)',
                                                    'rgba(249, 115, 22, 0.5)',
                                                    'rgba(239, 68, 68, 0.5)',
                                                    'rgba(168, 85, 247, 0.5)',
                                                    'rgba(236, 72, 153, 0.5)',
                                                ],
                                                borderColor: [
                                                    'rgb(59, 130, 246)',
                                                    'rgb(16, 185, 129)',
                                                    'rgb(249, 115, 22)',
                                                    'rgb(239, 68, 68)',
                                                    'rgb(168, 85, 247)',
                                                    'rgb(236, 72, 153)',
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: true,
                                            plugins: {
                                                legend: {
                                                    display: config.type === 'pie' || config.type === 'doughnut',
                                                },
                                                title: {
                                                    display: true,
                                                    text: '{{ $report->name }}'
                                                }
                                            },
                                            scales: config.type !== 'pie' && config.type !== 'doughnut' ? {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            } : {}
                                        }
                                    });
                                }
                            });
                        </script>
                    </x-card.content>
                </x-card>

                <!-- Data Table (collapsed by default for charts) -->
                <details class="mt-6">
                    <summary class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                        {{ __('Show Data Table') }} ({{ count($results) }} {{ __('rows') }})
                    </summary>
                    
                    <x-card class="mt-4">
                        <x-card.content>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-neutral-50 dark:bg-neutral-800">
                                        <tr>
                                            @foreach(array_keys($results[0]) as $column)
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    {{ $column }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($results as $row)
                                            <tr>
                                                @foreach($row as $value)
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $value }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-card.content>
                    </x-card>
                </details>
            @else
                <!-- Table Visualization -->
                <x-card>
                    <x-card.header>
                        <flux:subheading>{{ count($results) }} {{ __('results') }}</flux:subheading>
                    </x-card.header>

                    <x-card.content>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-neutral-50 dark:bg-neutral-800">
                                    <tr>
                                        @foreach(array_keys($results[0]) as $column)
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                {{ $column }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($results as $row)
                                        <tr>
                                            @foreach($row as $value)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $value }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-card.content>
                </x-card>
            @endif
        @else
            <!-- Empty State -->
            <x-card>
                <x-card.content>
                    <div class="text-center py-12">
                        <flux:icon.chart-bar class="h-12 w-12 text-gray-400 mx-auto" />
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('No data') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This report returned no results.') }}</p>
                    </div>
                </x-card.content>
            </x-card>
        @endif
    </div>
</div>
