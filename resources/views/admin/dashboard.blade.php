@php
    use App\Models\Organisation;
    use App\Models\User;
    use App\Models\Report;
    
    $organisationsCount = Organisation::count();
    $usersCount = User::count();
    $reportsCount = Report::count();
    $activeOrgsCount = Organisation::where('is_active', true)->count();
@endphp

<x-admin-layout>
    <x-slot name="header">
        <flux:heading size="xl" class="font-semibold">{{ __('Admin Dashboard') }}</flux:heading>
        <flux:subheading>{{ __('Welcome back, ') }} {{ auth()->user()->name }}</flux:subheading>
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Organizations -->
            <x-card>
                <x-card.content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Organizations') }}</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $organisationsCount }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activeOrgsCount }} {{ __('active') }}</p>
                        </div>
                        <flux:icon.building-office-2 class="h-12 w-12 text-blue-500" />
                    </div>
                </x-card.content>
            </x-card>

            <!-- Users -->
            <x-card>
                <x-card.content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Users') }}</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $usersCount }}</p>
                        </div>
                        <flux:icon.users class="h-12 w-12 text-green-500" />
                    </div>
                </x-card.content>
            </x-card>

            <!-- Reports -->
            <x-card>
                <x-card.content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Reports') }}</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $reportsCount }}</p>
                        </div>
                        <flux:icon.chart-bar class="h-12 w-12 text-purple-500" />
                    </div>
                </x-card.content>
            </x-card>

            <!-- System Status -->
            <x-card>
                <x-card.content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('System Status') }}</p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400 mt-2">{{ __('Operational') }}</p>
                        </div>
                        <flux:icon.check-circle class="h-12 w-12 text-green-500" />
                    </div>
                </x-card.content>
            </x-card>
        </div>

        <!-- Quick Actions -->
        <x-card>
            <x-card.header>
                <flux:heading size="lg">{{ __('Quick Actions') }}</flux:heading>
            </x-card.header>

            <x-card.content>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <flux:button href="{{ route('admin.organisations.index') }}" wire:navigate icon="building-office-2" variant="outline" class="justify-start">
                        <div class="text-left">
                            <div class="font-semibold">{{ __('Manage Organizations') }}</div>
                            <div class="text-xs text-gray-500">{{ __('Add or edit client organizations') }}</div>
                        </div>
                    </flux:button>

                    <flux:button href="{{ route('admin.users.index') }}" wire:navigate icon="users" variant="outline" class="justify-start">
                        <div class="text-left">
                            <div class="font-semibold">{{ __('Manage Users') }}</div>
                            <div class="text-xs text-gray-500">{{ __('Create and manage user accounts') }}</div>
                        </div>
                    </flux:button>

                    <flux:button href="{{ route('admin.reports.index') }}" wire:navigate icon="chart-bar" variant="outline" class="justify-start">
                        <div class="text-left">
                            <div class="font-semibold">{{ __('Build Reports') }}</div>
                            <div class="text-xs text-gray-500">{{ __('Create custom reports for clients') }}</div>
                        </div>
                    </flux:button>
                </div>
            </x-card.content>
        </x-card>

        <!-- Recent Reports -->
        @php
            $recentReports = Report::with(['organisation', 'creator'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        @endphp

        @if($recentReports->count() > 0)
            <x-card>
                <x-card.header>
                    <flux:heading size="lg">{{ __('Recent Reports') }}</flux:heading>
                </x-card.header>

                <x-card.content>
                    <div class="space-y-4">
                        @foreach($recentReports as $report)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $report->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $report->organisation->name }} • {{ $report->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <flux:badge size="sm" :color="$report->visualization_type === 'chart' ? 'blue' : 'zinc'">
                                    {{ ucfirst($report->visualization_type) }}
                                </flux:badge>
                            </div>
                        @endforeach
                    </div>
                </x-card.content>
            </x-card>
        @endif
    </div>
</x-admin-layout>

