<div>
    <x-slot name="header">
        <flux:heading size="xl" class="font-semibold">{{ __('Reports') }}</flux:heading>
        <flux:subheading>{{ __('View and export reports for your organization') }}</flux:subheading>
    </x-slot>

    @if(!auth()->user()->organisation_id)
        <flux:banner variant="warning">
            {{ __('You are not assigned to an organization. Please contact your administrator.') }}
        </flux:banner>
    @else
        <div class="space-y-6">
            <!-- Search -->
            <flux:input 
                wire:model.live="search" 
                placeholder="Search reports..." 
                icon="magnifying-glass"
            />

            <!-- Reports Grid -->
            @if($reports->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($reports as $report)
                        <x-card class="hover:shadow-lg transition-shadow duration-200 cursor-pointer" wire:click="$navigate('{{ route('user.reports.view', $report->id) }}')">
                            <x-card.header>
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <flux:heading size="lg">{{ $report->name }}</flux:heading>
                                    </div>
                                    <flux:badge size="sm" :color="$report->visualization_type === 'chart' ? 'blue' : 'zinc'">
                                        {{ ucfirst($report->visualization_type) }}
                                    </flux:badge>
                                </div>
                            </x-card.header>

                            <x-card.content>
                                @if($report->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ Str::limit($report->description, 120) }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">
                                        {{ __('No description') }}
                                    </p>
                                @endif

                                <div class="mt-4 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <flux:icon.calendar class="h-4 w-4 mr-1" />
                                    {{ __('Updated') }} {{ $report->updated_at->diffForHumans() }}
                                </div>
                            </x-card.content>

                            <x-card.footer>
                                <flux:button variant="primary" size="sm" wire:navigate href="{{ route('user.reports.view', $report->id) }}">
                                    {{ __('View Report') }} →
                                </flux:button>
                            </x-card.footer>
                        </x-card>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <flux:icon.chart-bar class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('No reports available') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $search ? __('No reports match your search.') : __('There are no reports available for your organization yet.') }}
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>
