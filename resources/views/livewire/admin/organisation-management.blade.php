<div>
    <x-slot name="header">
        <flux:heading size="xl" class="font-semibold">{{ __('Organizations Management') }}</flux:heading>
        <flux:subheading>{{ __('Manage client organizations and their database connections') }}</flux:subheading>
    </x-slot>

    @if (session()->has('message'))
        <x-banner variant="success" class="mb-6">
            {{ session('message') }}
        </x-banner>
    @endif

    <div class="space-y-6">
        <!-- Search and Create -->
        <div class="flex justify-between items-center gap-4">
            <flux:input 
                wire:model.live="search" 
                placeholder="Search organizations..." 
                class="flex-1"
                icon="magnifying-glass"
            />
            
            <flux:button wire:click="create" icon="plus">
                {{ __('Add Organization') }}
            </flux:button>
        </div>

        <!-- Organizations Table -->
        <x-table>
            <x-table.columns>
                <x-table.column>{{ __('Name') }}</x-table.column>
                <x-table.column>{{ __('Database') }}</x-table.column>
                <x-table.column>{{ __('Status') }}</x-table.column>
                <x-table.column>{{ __('Created') }}</x-table.column>
                <x-table.column>{{ __('Actions') }}</x-table.column>
            </x-table.columns>

            <x-table.rows>
                @forelse ($organisations as $organisation)
                    <x-table.row :key="$organisation->id">
                        <x-table.cell>
                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $organisation->name }}
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            @if($organisation->database_host)
                                <div class="text-sm">
                                    <div class="text-gray-900 dark:text-gray-100">{{ $organisation->database_name }}</div>
                                    <div class="text-gray-500 dark:text-gray-400">{{ $organisation->database_host }}:{{ $organisation->database_port }}</div>
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">{{ __('Not configured') }}</span>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            @if($organisation->is_active)
                                <flux:badge color="green" size="sm">{{ __('Active') }}</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">{{ __('Inactive') }}</flux:badge>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            {{ $organisation->created_at->format('M d, Y') }}
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button 
                                    size="sm" 
                                    variant="ghost" 
                                    icon="pencil" 
                                    wire:click="edit('{{ $organisation->id }}')"
                                >
                                    {{ __('Edit') }}
                                </flux:button>
                                
                                <flux:button 
                                    size="sm" 
                                    variant="ghost" 
                                    :icon="$organisation->is_active ? 'x-circle' : 'check-circle'"
                                    wire:click="toggleStatus('{{ $organisation->id }}')"
                                    wire:confirm="Are you sure you want to {{ $organisation->is_active ? 'deactivate' : 'activate' }} this organization?"
                                >
                                    {{ $organisation->is_active ? __('Deactivate') : __('Activate') }}
                                </flux:button>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5">
                            <div class="text-center py-8">
                                <flux:icon.building-office-2 class="mx-auto h-12 w-12 text-gray-400" />
                                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('No organizations') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by creating a new organization.') }}</p>
                                <div class="mt-6">
                                    <flux:button wire:click="create" icon="plus">
                                        {{ __('Add Organization') }}
                                    </flux:button>
                                </div>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.rows>
        </x-table>

        <!-- Pagination -->
        <div>
            {{ $organisations->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <flux:modal name="organisation-form" wire:model="showModal" class="max-w-2xl">
            <form wire:submit.prevent="save">
                
                    <flux:heading size="lg">
                        {{ $editMode ? __('Edit Organization') : __('Create Organization') }}
                    </flux:heading>
                

                
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <flux:subheading>{{ __('Basic Information') }}</flux:subheading>
                            <div class="mt-4 space-y-4">
                                <flux:input 
                                    label="Organization Name" 
                                    wire:model="name"
                                    :error="$errors->first('name')"
                                    required
                                />

                                <flux:checkbox wire:model="is_active" label="Active" />
                            </div>
                        </div>

                        <!-- Database Connection -->
                        <div>
                            <flux:subheading>{{ __('Database Connection') }}</flux:subheading>
                            <div class="mt-4 space-y-4">
                                <flux:input 
                                    label="Database Host" 
                                    wire:model="database_host"
                                    :error="$errors->first('database_host')"
                                    placeholder="localhost or IP address"
                                />

                                <flux:input 
                                    label="Database Name" 
                                    wire:model="database_name"
                                    :error="$errors->first('database_name')"
                                />

                                <div class="grid grid-cols-2 gap-4">
                                    <flux:input 
                                        label="Port" 
                                        type="number" 
                                        wire:model="database_port"
                                        :error="$errors->first('database_port')"
                                    />
                                </div>

                                <flux:input 
                                    label="Database Username" 
                                    wire:model="database_username"
                                    :error="$errors->first('database_username')"
                                    autocomplete="off"
                                />

                                <flux:input 
                                    label="Database Password" 
                                    type="password" 
                                    wire:model="database_password"
                                    :error="$errors->first('database_password')"
                                    autocomplete="new-password"
                                />

                                <flux:button 
                                    type="button" 
                                    variant="outline" 
                                    wire:click="testConnection"
                                    icon="beaker"
                                >
                                    {{ __('Test Connection') }}
                                </flux:button>
                            </div>
                        </div>
                    </div>
                

                
                    <flux:button type="button" variant="ghost" wire:click="closeModal">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ $editMode ? __('Update') : __('Create') }}
                    </flux:button>
                
            </form>
        </flux:modal>
    @endif

    <!-- Test Connection Result Modal -->
    @if($showTestModal)
        <flux:modal name="test-connection" wire:model="showTestModal">
            
                <flux:heading size="lg">{{ __('Connection Test Result') }}</flux:heading>
            

            
                <div class="py-4">
                    @if($testConnectionResult)
                        <div class="flex items-center gap-3 text-green-600 dark:text-green-400">
                            <flux:icon.check-circle class="h-8 w-8" />
                            <div>
                                <div class="font-semibold">{{ __('Connection Successful') }}</div>
                                <div class="text-sm">{{ $testConnectionMessage }}</div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 text-red-600 dark:text-red-400">
                            <flux:icon.x-circle class="h-8 w-8" />
                            <div>
                                <div class="font-semibold">{{ __('Connection Failed') }}</div>
                                <div class="text-sm">{{ $testConnectionMessage }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            

            
                <flux:button wire:click="closeTestModal">{{ __('Close') }}</flux:button>
            
        </flux:modal>
    @endif
</div>
