<div>
    @if($viewMode === 'list')
        {{-- LIST VIEW --}}
        <x-slot name="header">
            <flux:heading size="xl" class="font-semibold">{{ __('Reports') }}</flux:heading>
            <flux:subheading>{{ __('Manage and build custom reports for organizations') }}</flux:subheading>
        </x-slot>

        @if (session()->has('message'))
            <x-banner variant="success" class="mb-6">
                {{ session('message') }}
            </x-banner>
        @endif

        <div class="space-y-6">
            <!-- Search and Filters -->
            <div class="flex justify-between items-center gap-4">
                <div class="flex-1 flex gap-4">
                    <flux:input 
                        wire:model.live="search" 
                        placeholder="Search reports..." 
                        class="flex-1"
                        icon="magnifying-glass"
                    />
                    
                    <flux:select wire:model.live="filterOrganisation" placeholder="All Organizations" class="w-64">
                        <option value="">{{ __('All Organizations') }}</option>
                        @foreach($organisations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
                
                <flux:button wire:click="showBuilder" icon="plus">
                    {{ __('Build New Report') }}
                </flux:button>
            </div>

            <!-- Reports Table -->
            <x-table>
                <flux:columns>
                    <flux:column>{{ __('Name') }}</flux:column>
                    <flux:column>{{ __('Organization') }}</flux:column>
                    <flux:column>{{ __('Type') }}</flux:column>
                    <flux:column>{{ __('Status') }}</flux:column>
                    <flux:column>{{ __('Created By') }}</flux:column>
                    <flux:column>{{ __('Actions') }}</flux:column>
                </flux:columns>

                <flux:rows>
                    @forelse ($reports as $report)
                        <flux:row :key="$report->id">
                            <flux:cell>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $report->name }}</div>
                                    @if($report->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($report->description, 50) }}</div>
                                    @endif
                                </div>
                            </flux:cell>
                            <flux:cell>
                                {{ $report->organisation->name }}
                            </flux:cell>
                            <flux:cell>
                                <flux:badge size="sm" :color="$report->visualization_type === 'chart' ? 'blue' : 'zinc'">
                                    {{ ucfirst($report->visualization_type) }}
                                </flux:badge>
                            </flux:cell>
                            <flux:cell>
                                @if($report->is_active)
                                    <flux:badge color="green" size="sm">{{ __('Active') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">{{ __('Inactive') }}</flux:badge>
                                @endif
                            </flux:cell>
                            <flux:cell>
                                {{ $report->creator->name }}
                            </flux:cell>
                            <flux:cell>
                                <div class="flex items-center gap-2">
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost" 
                                        icon="pencil" 
                                        wire:click="editReport('{{ $report->id }}')"
                                    >
                                        {{ __('Edit') }}
                                    </flux:button>
                                    
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost" 
                                        :icon="$report->is_active ? 'x-circle' : 'check-circle'"
                                        wire:click="toggleReportStatus('{{ $report->id }}')"
                                    >
                                        {{ $report->is_active ? __('Deactivate') : __('Activate') }}
                                    </flux:button>
                                </div>
                            </flux:cell>
                        </flux:row>
                    @empty
                        <flux:row>
                            <flux:cell colspan="6">
                                <div class="text-center py-8">
                                    <flux:icon.chart-bar class="mx-auto h-12 w-12 text-gray-400" />
                                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('No reports') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by building your first report.') }}</p>
                                    <div class="mt-6">
                                        <flux:button wire:click="showBuilder" icon="plus">
                                            {{ __('Build New Report') }}
                                        </flux:button>
                                    </div>
                                </div>
                            </flux:cell>
                        </flux:row>
                    @endforelse
                </flux:rows>
            </x-table>

            <!-- Pagination -->
            <div>
                {{ $reports->links() }}
            </div>
        </div>
    @else
        {{-- BUILDER VIEW --}}
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" class="font-semibold">
                        {{ $editMode ? __('Edit Report') : __('Build New Report') }}
                    </flux:heading>
                    <flux:subheading>{{ __('Create custom SQL queries and visualizations') }}</flux:subheading>
                </div>
                <flux:button variant="ghost" wire:click="showList" icon="arrow-left">
                    {{ __('Back to List') }}
                </flux:button>
            </div>
        </x-slot>

        @if (session()->has('message'))
            <x-banner variant="success" class="mb-6">
                {{ session('message') }}
            </x-banner>
        @endif

        @if (session()->has('error'))
            <x-banner variant="danger" class="mb-6">
                {{ session('error') }}
            </x-banner>
        @endif

        <form wire:submit.prevent="saveReport">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Sidebar - Schema Tree -->
                <div class="lg:col-span-1">
                    <x-card>
                        <x-card.header>
                            <flux:heading size="lg">{{ __('Database Schema') }}</flux:heading>
                        </x-card.header>

                        <x-card.content>
                            <flux:select 
                                label="Organization" 
                                wire:model.live="selectedOrganisation"
                                :error="$errors->first('selectedOrganisation')"
                                placeholder="Select an organization"
                                required
                            >
                                <option value="">{{ __('Select an organization') }}</option>
                                @foreach($organisations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </flux:select>

                            @if($selectedOrganisation)
                                <div class="mt-4">
                                    <flux:button 
                                        type="button" 
                                        variant="outline" 
                                        size="sm"
                                        wire:click="refreshSchema"
                                        icon="arrow-path"
                                    >
                                        {{ __('Refresh Schema') }}
                                    </flux:button>
                                </div>

                                @if($schemaLoading)
                                    <div class="mt-4 text-center">
                                        <flux:icon.arrow-path class="h-8 w-8 animate-spin text-gray-400 mx-auto" />
                                        <p class="mt-2 text-sm text-gray-500">{{ __('Loading schema...') }}</p>
                                    </div>
                                @elseif(count($schema) > 0)
                                    <div class="mt-4 space-y-2 max-h-96 overflow-y-auto">
                                        @foreach($schema as $table)
                                            <details class="border border-gray-200 dark:border-gray-700 rounded-lg">
                                                <summary class="px-3 py-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 font-medium text-sm">
                                                    {{ $table['name'] }}
                                                </summary>
                                                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-800 text-xs space-y-1">
                                                    @foreach($table['columns'] as $column)
                                                        <div class="flex justify-between items-center py-1">
                                                            <span class="font-mono">{{ $column['name'] }}</span>
                                                            <span class="text-gray-500">{{ $column['type'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </details>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-4 text-center text-sm text-gray-500">
                                        {{ __('No tables found or unable to connect.') }}
                                    </div>
                                @endif
                            @endif
                        </x-card.content>
                    </x-card>
                </div>

                <!-- Main Content - Query Builder -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Report Details -->
                    <x-card>
                        <x-card.header>
                            <flux:heading size="lg">{{ __('Report Details') }}</flux:heading>
                        </x-card.header>

                        <x-card.content>
                            <div class="space-y-4">
                                <flux:input 
                                    label="Report Name" 
                                    wire:model="name"
                                    :error="$errors->first('name')"
                                    required
                                />

                                <flux:textarea 
                                    label="Description" 
                                    wire:model="description"
                                    :error="$errors->first('description')"
                                    rows="3"
                                />
                            </div>
                        </x-card.content>
                    </x-card>

                    <!-- SQL Query -->
                    <x-card>
                        <x-card.header>
                            <flux:heading size="lg">{{ __('SQL Query') }}</flux:heading>
                        </x-card.header>

                        <x-card.content>
                            <flux:textarea 
                                label="SQL Query (SELECT only)" 
                                wire:model="sql_query"
                                :error="$errors->first('sql_query')"
                                rows="10"
                                class="font-mono text-sm"
                                required
                            />

                            <div class="mt-4">
                                <flux:button 
                                    type="button" 
                                    variant="outline" 
                                    wire:click="validateAndPreview"
                                    icon="beaker"
                                >
                                    {{ __('Validate & Preview') }}
                                </flux:button>
                            </div>
                        </x-card.content>
                    </x-card>

                    <!-- Visualization -->
                    <x-card>
                        <x-card.header>
                            <flux:heading size="lg">{{ __('Visualization') }}</flux:heading>
                        </x-card.header>

                        <x-card.content>
                            <div class="space-y-4">
                                <flux:radio.group 
                                    label="Visualization Type" 
                                    wire:model.live="visualization_type"
                                >
                                    <flux:radio value="table" label="Table" />
                                    <flux:radio value="chart" label="Chart" />
                                </flux:radio.group>

                                @if($visualization_type === 'chart')
                                    <div class="space-y-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <flux:select 
                                            label="Chart Type" 
                                            wire:model="chart_type"
                                            :error="$errors->first('chart_type')"
                                            required
                                        >
                                            <option value="line">{{ __('Line Chart') }}</option>
                                            <option value="bar">{{ __('Bar Chart') }}</option>
                                            <option value="pie">{{ __('Pie Chart') }}</option>
                                            <option value="doughnut">{{ __('Doughnut Chart') }}</option>
                                        </flux:select>

                                        <flux:input 
                                            label="Label Column (X-axis or labels)" 
                                            wire:model="chart_label_column"
                                            :error="$errors->first('chart_label_column')"
                                            placeholder="e.g., month, category"
                                            required
                                        />

                                        <flux:input 
                                            label="Data Column (Y-axis or values)" 
                                            wire:model="chart_data_column"
                                            :error="$errors->first('chart_data_column')"
                                            placeholder="e.g., total, count"
                                            required
                                        />
                                    </div>
                                @endif
                            </div>
                        </x-card.content>
                    </x-card>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4">
                        <flux:button type="button" variant="ghost" wire:click="showList">
                            {{ __('Cancel') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            {{ $editMode ? __('Update Report') : __('Save Report') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        </form>
    @endif

    <!-- Preview Modal -->
    @if($showPreview)
        <flux:modal name="preview" wire:model="showPreview" class="max-w-6xl">
            <flux:modal.header>
                <flux:heading size="lg">{{ __('Query Preview') }}</flux:heading>
            </flux:modal.header>

            <flux:modal.content>
                @if($previewError)
                    <x-banner variant="danger">
                        <strong>{{ __('Validation Error:') }}</strong> {{ $previewError }}
                    </x-banner>
                @elseif(count($previewResults) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    @foreach(array_keys($previewResults[0]) as $column)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ $column }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($previewResults as $row)
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
                    <p class="mt-4 text-sm text-gray-500">{{ __('Showing first 10 results') }}</p>
                @else
                    <p class="text-gray-500">{{ __('No results returned.') }}</p>
                @endif
            </flux:modal.content>

            <flux:modal.footer>
                <flux:button wire:click="closePreview">{{ __('Close') }}</flux:button>
            </flux:modal.footer>
        </flux:modal>
    @endif
</div>
