<x-app-layout>
    <x-slot name="header">
        <flux:heading size="xl" level="1" class="text-zinc-900 dark:text-zinc-100">{{ __('Profile') }}</flux:heading>
        <flux:text class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('Manage your account settings and preferences') }}</flux:text>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-zinc-800 shadow sm:rounded-lg p-6">
            <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">{{ __('Profile Information') }}</flux:heading>
            <flux:subheading class="text-zinc-600 dark:text-zinc-400">{{ __("Your account information") }}</flux:subheading>
            
            <div class="mt-6 space-y-4">
                <div>
                    <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Name') }}</flux:text>
                    <flux:text class="mt-1 text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</flux:text>
                </div>
                
                <div>
                    <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Email') }}</flux:text>
                    <flux:text class="mt-1 text-zinc-900 dark:text-zinc-100">{{ auth()->user()->email }}</flux:text>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
