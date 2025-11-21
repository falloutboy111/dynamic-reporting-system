<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Organisations</flux:heading>
            <flux:subheading>Manage your connected Xero organisations</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus">
            Connect Organisation
        </flux:button>
    </div>

    @if($organisations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Legal Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Last Sync
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($organisations as $organisation)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $organisation->name }}
                                </div>
                                @if($organisation->short_code)
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $organisation->short_code }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $organisation->legal_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge :color="$organisation->organisation_status === 'ACTIVE' ? 'green' : 'zinc'" size="sm">
                                    {{ $organisation->organisation_status ?? 'N/A' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($organisation->last_sync)
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $organisation->last_sync->format('d M Y') }}
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $organisation->last_sync->format('h:i A') }}
                                    </div>
                                @else
                                    <span class="text-sm text-zinc-400 dark:text-zinc-500">Never</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="primary" size="sm">
                                        Sync
                                    </flux:button>
                                    <flux:button variant="ghost" size="sm">
                                        View
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $organisations->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
            <svg class="mx-auto size-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <div class="mt-4">
                <flux:heading size="lg">No organisations connected</flux:heading>
                <flux:subheading class="mt-2">Connect your first Xero organisation to get started</flux:subheading>
            </div>
            <flux:button variant="primary" class="mt-6" icon="plus">
                Connect Your First Organisation
            </flux:button>
        </div>
    @endif
</div>
