<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <flux:heading size="lg">{{ __('Delete Account') }}</flux:heading>
        <flux:subheading class="text-red-600 dark:text-red-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </flux:subheading>
    </header>

    <flux:button 
        variant="danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</flux:button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">
            <flux:heading size="lg" class="dark:text-zinc-100">
                {{ __('Are you sure you want to delete your account?') }}
            </flux:heading>

            <flux:text class="mt-4 text-zinc-600 dark:text-zinc-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </flux:text>

            <div class="mt-6">
                <flux:input
                    wire:model="password"
                    type="password"
                    placeholder="{{ __('Password') }}"
                    class="w-full"
                />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <flux:button variant="ghost" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="submit" variant="danger">
                    {{ __('Delete Account') }}
                </flux:button>
            </div>
        </form>
    </x-modal>
</section>
