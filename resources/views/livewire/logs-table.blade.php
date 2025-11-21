<div>
    <div class="mb-6">
        <flux:heading size="xl">Xero Sync Logs</flux:heading>
        <flux:subheading>View and filter synchronization logs</flux:subheading>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex gap-4">
        <flux:select wire:model.live="filterType" placeholder="Filter by Type">
            <option value="">All Types</option>
            <option value="sync">Sync</option>
            <option value="error">Error</option>
            <option value="info">Info</option>
        </flux:select>

        <flux:select wire:model.live="filterStatus" placeholder="Filter by Status">
            <option value="">All Statuses</option>
            <option value="success">Success</option>
            <option value="failed">Failed</option>
            <option value="warning">Warning</option>
        </flux:select>

        <flux:button variant="ghost" wire:click="clearFilters" icon="x-mark">Clear Filters</flux:button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Organisation
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Message
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Sync Time
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Created
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach($logs as $log)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $log->organisation?->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <flux:badge color="zinc" size="sm">{{ ucfirst($log->type) }}</flux:badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <flux:badge 
                                :color="$log->status === 'success' ? 'green' : ($log->status === 'failed' ? 'red' : 'yellow')" 
                                size="sm">
                                {{ ucfirst($log->status) }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 max-w-md truncate" title="{{ $log->message }}">
                                {{ $log->message }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $log->sync_time ? $log->sync_time->format('d M Y h:i A') : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $log->created_at->format('d M Y h:i A') }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
