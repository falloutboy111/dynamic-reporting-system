<x-app-layout>
    <x-slot name="header">
        <flux:heading size="xl" level="1" class="text-zinc-900 dark:text-zinc-100">{{ __('Dashboard') }}</flux:heading>
        <flux:text class="mb-6 mt-2 text-base text-zinc-600 dark:text-zinc-400">{{ __('Welcome back, :name', ['name' => auth()->user()->name]) }}</flux:text>
    </x-slot>

    <div class="space-y-6">
        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <flux:icon.building-office-2 class="size-8 text-blue-500" />
                    </div>
                    <div class="ml-4">
                        <flux:heading size="sm" class="text-zinc-500 dark:text-zinc-400">{{ __('Total Clients') }}</flux:heading>
                        <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 mt-1">
                            {{ \App\Models\Organisation::count() }}
                        </flux:heading>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <flux:icon.check-circle class="size-8 text-green-500" />
                    </div>
                    <div class="ml-4">
                        <flux:heading size="sm" class="text-zinc-500 dark:text-zinc-400">{{ __('Active') }}</flux:heading>
                        <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 mt-1">
                            {{ \App\Models\Organisation::where('organisation_status', 'ACTIVE')->count() }}
                        </flux:heading>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <flux:icon.archive-box class="size-8 text-zinc-500" />
                    </div>
                    <div class="ml-4">
                        <flux:heading size="sm" class="text-zinc-500 dark:text-zinc-400">{{ __('Archived') }}</flux:heading>
                        <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 mt-1">
                            {{ \App\Models\Organisation::where('organisation_status', 'ARCHIVED')->count() }}
                        </flux:heading>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <flux:icon.arrow-path class="size-8 text-purple-500" />
                    </div>
                    <div class="ml-4">
                        <flux:heading size="sm" class="text-zinc-500 dark:text-zinc-400">{{ __('Last Sync') }}</flux:heading>
                        <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 mt-1">
                            @php
                                $lastOrg = \App\Models\Organisation::whereNotNull('last_sync')->orderBy('last_sync', 'desc')->first();
                            @endphp
                            {{ $lastOrg?->last_sync?->diffForHumans() ?? 'Never' }}
                        </flux:heading>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Quick Actions') }}</flux:heading>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('client-sync.index') }}" wire:navigate class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <flux:icon.arrow-path class="size-6 text-blue-500 mr-3" />
                    <div>
                        <flux:heading size="sm" class="text-zinc-900 dark:text-zinc-100">{{ __('Manage Client Sync') }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ __('View and sync organisations') }}</flux:text>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" wire:navigate class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <flux:icon.chart-bar class="size-6 text-green-500 mr-3" />
                    <div>
                        <flux:heading size="sm" class="text-zinc-900 dark:text-zinc-100">{{ __('View Reports') }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ __('Generate and download reports') }}</flux:text>
                    </div>
                </a>

                <a href="{{ route('logs.index') }}" wire:navigate class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <flux:icon.document-text class="size-6 text-purple-500 mr-3" />
                    <div>
                        <flux:heading size="sm" class="text-zinc-900 dark:text-zinc-100">{{ __('Activity Logs') }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ __('View system activity') }}</flux:text>
                    </div>
                </a>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Recently Synced Clients') }}</flux:heading>
            
            <div class="space-y-3">
                @forelse(\App\Models\Organisation::whereNotNull('last_sync')->orderBy('last_sync', 'desc')->limit(5)->get() as $org)
                    <div class="flex items-center justify-between p-3 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        <div class="flex items-center">
                            <flux:icon.building-office class="size-5 text-zinc-400 mr-3" />
                            <div>
                                <flux:heading size="sm" class="text-zinc-900 dark:text-zinc-100">{{ $org->name }}</flux:heading>
                                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ $org->legal_name }}</flux:text>
                            </div>
                        </div>
                        <div class="text-right">
                            <flux:badge :color="$org->organisation_status === 'ACTIVE' ? 'green' : 'zinc'">{{ $org->organisation_status }}</flux:badge>
                            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400 mt-1">{{ $org->last_sync->diffForHumans() }}</flux:text>
                        </div>
                    </div>
                @empty
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-center py-8">{{ __('No recent sync activity') }}</flux:text>
                @endforelse
            </div>

            @if(\App\Models\Organisation::whereNotNull('last_sync')->count() > 5)
                <div class="mt-4 text-center">
                    <flux:button href="{{ route('client-sync.index') }}" wire:navigate variant="ghost">
                        {{ __('View All Clients') }}
                    </flux:button>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
