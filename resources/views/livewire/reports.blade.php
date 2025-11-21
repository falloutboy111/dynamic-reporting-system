<div>
    <div class="mb-6">
        <flux:heading size="xl">Reports</flux:heading>
        <flux:subheading>View analytics and reports</flux:subheading>
    </div>

    <!-- Report Type Selector -->
    <div class="mb-6 flex gap-4">
        <flux:select wire:model.live="reportType" label="Report Type">
            <option value="logs">Logs Report</option>
            <option value="journal">Journal Lines Report</option>
        </flux:select>

        <flux:select wire:model.live="selectedOrganisation" label="Filter by Organisation">
            <option value="">All Organisations</option>
            @foreach($organisations as $org)
                <option value="{{ $org->id }}">{{ $org->name }}</option>
            @endforeach
        </flux:select>
    </div>

    @if($reportType === 'logs')
        <!-- Logs Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-zinc-50 dark:bg-zinc-700 p-6 rounded-lg border border-zinc-200 dark:border-zinc-600">
                <div class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $logsSummary['total'] ?? 0 }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Total Logs</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-800">
                <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $logsSummary['success'] ?? 0 }}</div>
                <div class="text-sm text-green-600 dark:text-green-400 mt-1">Successful</div>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-lg border border-red-200 dark:border-red-800">
                <div class="text-3xl font-bold text-red-700 dark:text-red-300">{{ $logsSummary['failed'] ?? 0 }}</div>
                <div class="text-sm text-red-600 dark:text-red-400 mt-1">Failed</div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $logsSummary['warning'] ?? 0 }}</div>
                <div class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">Warnings</div>
            </div>
        </div>
    @else
        <!-- Journal Lines Report -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Organisation
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Total Lines
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Total Net Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Total Gross Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Total Tax Amount
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($journalLines as $line)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $line['organisation'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ number_format($line['total_lines']) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    ${{ number_format($line['total_net'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    ${{ number_format($line['total_gross'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    ${{ number_format($line['total_tax'], 2) }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                No journal lines found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
