<x-app-layout>
    <x-slot name="header">
        <flux:heading size="xl" level="1">{{ __('Xero Sync Logs') }}</flux:heading>
    </x-slot>

    <livewire:logs-table />
</x-app-layout>

