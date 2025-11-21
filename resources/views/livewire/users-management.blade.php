<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl">Users Management</flux:heading>
                <flux:subheading>Manage system users and their roles</flux:subheading>
            </div>
            <flux:button variant="primary" wire:click="openModal" icon="plus">
                Add New User
            </flux:button>
        </div>
    </div>

    @if(session('message'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Role
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Created
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach($users as $user)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $user->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($user->roles as $role)
                                <flux:badge :color="$role->name === 'admin' ? 'purple' : 'blue'" size="sm">
                                    {{ ucfirst($role->name) }}
                                </flux:badge>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $user->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <flux:button variant="ghost" size="sm" wire:click="openModal('{{ $user->id }}')">
                                    Edit
                                </flux:button>
                                @if($user->id !== auth()->id())
                                    <flux:button variant="ghost" size="sm" wire:click="delete('{{ $user->id }}')" wire:confirm="Are you sure you want to delete this user?">
                                        Delete
                                    </flux:button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Modal -->
    <x-modal wire:model="showModal" class="min-w-[600px]">
        <form wire:submit="save">
            <flux:heading size="lg">{{ $editingUserId ? 'Edit User' : 'Add New User' }}</flux:heading>

            <div class="mt-6 space-y-6">
                <flux:input 
                    label="Name" 
                    type="text" 
                    wire:model="name"
                    :error="$errors->first('name')"
                    required />

                <flux:input 
                    label="Email" 
                    type="email" 
                    wire:model="email"
                    :error="$errors->first('email')"
                    required />

                <flux:input 
                    label="Password" 
                    type="password" 
                    wire:model="password"
                    :error="$errors->first('password')"
                    :required="!$editingUserId" />

                <flux:input 
                    label="Confirm Password" 
                    type="password" 
                    wire:model="password_confirmation"
                    :error="$errors->first('password_confirmation')"
                    :required="!$editingUserId" />

                <flux:select label="Role" wire:model="role" :error="$errors->first('role')" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </flux:select>
            </div>

            <div class="mt-8 flex gap-2 justify-end">
                <flux:button type="button" variant="ghost" wire:click="closeModal">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </x-modal>
</div>
