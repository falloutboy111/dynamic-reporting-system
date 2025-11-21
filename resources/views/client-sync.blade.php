<x-app-layout>
    <x-slot name="header">
        <flux:heading size="xl" level="1" class="text-zinc-900 dark:text-zinc-100">{{ __('Client Sync') }}</flux:heading>
        <flux:text class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('Manage your connected Xero organisations') }}</flux:text>
    </x-slot>

    <livewire:organisations-table />
</x-app-layout>

