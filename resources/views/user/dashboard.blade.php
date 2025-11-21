@php
    use App\Models\Report;
    
    $user = auth()->user();
    $reportsCount = $user->organisation_id 
        ? Report::where('organisation_id', $user->organisation_id)->where('is_active', true)->count() 
        : 0;
@endphp

<x-user-layout>
    <x-slot name="header">
        <flux:heading size="xl" class="font-semibold">{{ __('Dashboard') }}</flux:heading>
        <flux:subheading>{{ __('Welcome back, ') }} {{ auth()->user()->name }}</flux:subheading>
    </x-slot>

    <div class="space-y-6">
        <!-- Organization Info -->
        @if($user->organisation)
            <x-card>
                <x-card.header>
                    <flux:heading size="lg">{{ __('Your Organization') }}</flux:heading>
                </x-card.header>

                <x-card.content>
                    <div class="flex items-center gap-4">
                        <flux:icon.building-office-2 class="h-12 w-12 text-blue-500" />
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->organisation->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $reportsCount }} {{ __('reports available') }}</p>
                        </div>
                    </div>
                </x-card.content>
            </x-card>
        @else
            <x-banner variant="warning">
                {{ __('You are not assigned to an organization. Please contact your administrator.') }}
            </x-banner>
        @endif

        <!-- Quick Access -->
        @if($user->organisation_id)
            <x-card>
                <x-card.header>
                    <flux:heading size="lg">{{ __('Quick Access') }}</flux:heading>
                </x-card.header>

                <x-card.content>
                    <flux:button href="{{ route('user.reports.index') }}" wire:navigate icon="chart-bar">
                        {{ __('View Reports') }} →
                    </flux:button>
                </x-card.content>
            </x-card>

            <!-- Recent Reports -->
            @php
                $recentReports = Report::where('organisation_id', $user->organisation_id)
                    ->where('is_active', true)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();
            @endphp

            @if($recentReports->count() > 0)
                <x-card>
                    <x-card.header>
                        <div class="flex items-center justify-between">
                            <flux:heading size="lg">{{ __('Recent Reports') }}</flux:heading>
                            <flux:button href="{{ route('user.reports.index') }}" wire:navigate variant="ghost" size="sm">
                                {{ __('View All') }} →
                            </flux:button>
                        </div>
                    </x-card.header>

                    <x-card.content>
                        <div class="space-y-4">
                            @foreach($recentReports as $report)
                                <a href="{{ route('user.reports.view', $report->id) }}" wire:navigate class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0 hover:bg-neutral-50 dark:hover:bg-neutral-800 rounded-lg px-4 -mx-4 transition-colors">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $report->name }}</div>
                                        @if($report->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($report->description, 80) }}
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            {{ __('Updated') }} {{ $report->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <flux:badge size="sm" :color="$report->visualization_type === 'chart' ? 'blue' : 'zinc'">
                                            {{ ucfirst($report->visualization_type) }}
                                        </flux:badge>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </x-card.content>
                </x-card>
            @endif
        @endif
    </div>
</x-user-layout>

