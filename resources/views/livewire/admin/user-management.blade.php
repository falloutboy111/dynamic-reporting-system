<div>
    <x-slot name="header">
        <flux:heading size="xl" class="font-semibold">{{ __('Users Management') }}</flux:heading>
        <flux:subheading>{{ __('Manage users and their organization assignments') }}</flux:subheading>
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
                    placeholder="Search users..." 
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
            
            <flux:button wire:click="create" icon="plus">
                {{ __('Add User') }}
            </flux:button>
        </div>

        <!-- Users Table -->
        <x-table>
            <x-table.columns>
                <x-table.column>{{ __('Name') }}</x-table.column>
                <x-table.column>{{ __('Email') }}</x-table.column>
                <x-table.column>{{ __('Role') }}</x-table.column>
                <x-table.column>{{ __('Organization') }}</x-table.column>
                <x-table.column>{{ __('Created') }}</x-table.column>
                <x-table.column>{{ __('Actions') }}</x-table.column>
            </x-table.columns>

            <x-table.rows>
                @forelse ($users as $user)
                    <x-table.row :key="$user->id">
                        <x-table.cell>
                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $user->name }}
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->email }}
                        </x-table.cell>
                        <x-table.cell>
                            @if($user->hasRole('admin'))
                                <flux:badge color="purple" size="sm">{{ __('Admin') }}</flux:badge>
                            @else
                                <flux:badge color="blue" size="sm">{{ __('User') }}</flux:badge>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            @if($user->organisation)
                                {{ $user->organisation->name }}
                            @else
                                <span class="text-gray-400 dark:text-gray-500">{{ __('N/A') }}</span>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->created_at->format('M d, Y') }}
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button 
                                    size="sm" 
                                    variant="ghost" 
                                    icon="pencil" 
                                    wire:click="edit('{{ $user->id }}')"
                                >
                                    {{ __('Edit') }}
                                </flux:button>
                                
                                <flux:button 
                                    size="sm" 
                                    variant="ghost" 
                                    icon="trash"
                                    wire:click="delete('{{ $user->id }}')"
                                    wire:confirm="Are you sure you want to delete this user?"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="6">
                            <div class="text-center py-8">
                                <flux:icon.users class="mx-auto h-12 w-12 text-gray-400" />
                                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('No users') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by creating a new user.') }}</p>
                                <div class="mt-6">
                                    <flux:button wire:click="create" icon="plus">
                                        {{ __('Add User') }}
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
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <flux:modal name="user-form" wire:model="showModal" class="max-w-2xl">
            <form wire:submit.prevent="save">
                
                    <flux:heading size="lg">
                        {{ $editMode ? __('Edit User') : __('Create User') }}
                    </flux:heading>
                

                
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <flux:input 
                                label="Name" 
                                wire:model="name"
                                :error="$errors->first('name')"
                                required
                            />

                            <flux:input 
                                label="Email" 
                                type="email"
                                wire:model="email"
                                :error="$errors->first('email')"
                                required
                            />

                            <flux:select 
                                label="Role" 
                                wire:model.live="role"
                                :error="$errors->first('role')"
                                required
                            >
                                <option value="user">{{ __('User') }}</option>
                                <option value="admin">{{ __('Admin') }}</option>
                            </flux:select>

                            @if($role === 'user')
                                <flux:select 
                                    label="Organization" 
                                    wire:model="organisation_id"
                                    :error="$errors->first('organisation_id')"
                                    placeholder="Select an organization"
                                    required
                                >
                                    <option value="">{{ __('Select an organization') }}</option>
                                    @foreach($organisations as $org)
                                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                                    @endforeach
                                </flux:select>
                            @else
                                <x-banner>
                                    {{ __('Admin users are not assigned to any organization.') }}
                                </x-banner>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="space-y-4">
                            <flux:subheading>
                                {{ $editMode ? __('Change Password (optional)') : __('Password') }}
                            </flux:subheading>

                            <flux:input 
                                label="Password" 
                                type="password"
                                wire:model="password"
                                :error="$errors->first('password')"
                                :required="!$editMode"
                                autocomplete="new-password"
                            />

                            <flux:input 
                                label="Confirm Password" 
                                type="password"
                                wire:model="password_confirmation"
                                :error="$errors->first('password_confirmation')"
                                :required="!$editMode"
                                autocomplete="new-password"
                            />
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
</div>
