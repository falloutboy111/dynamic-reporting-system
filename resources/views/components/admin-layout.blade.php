<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
        {{-- Desktop and Mobile Sidebar --}}
        <flux:sidebar sticky collapsible="mobile" class="bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="{{ route('admin.dashboard') }}"
                    name="{{ config('app.name', 'Laravel') }}"
                    logo="https://www.enclivix.com/images/logos/logo.svg"
                />

                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.search placeholder="Search..." />

            <flux:sidebar.nav>
                <flux:sidebar.item 
                    icon="home" 
                    href="{{ route('admin.dashboard') }}" 
                    :current="request()->routeIs('admin.dashboard')"
                    wire:navigate
                >
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.item 
                    icon="building-office-2" 
                    href="{{ route('admin.organisations.index') }}" 
                    :current="request()->routeIs('admin.organisations.*')"
                    wire:navigate
                >
                    {{ __('Organizations') }}
                </flux:sidebar.item>

                <flux:sidebar.item 
                    icon="users" 
                    href="{{ route('admin.users.index') }}" 
                    :current="request()->routeIs('admin.users.*')"
                    wire:navigate
                >
                    {{ __('Users') }}
                </flux:sidebar.item>

                <flux:sidebar.item 
                    icon="chart-bar" 
                    href="{{ route('admin.reports.index') }}" 
                    :current="request()->routeIs('admin.reports.*')"
                    wire:navigate
                >
                    {{ __('Reports') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            {{-- Theme Toggle --}}
            <div class="px-4 py-2 border-t border-neutral-200 dark:border-neutral-700">
                <x-theme-toggle />
            </div>

            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:sidebar.profile 
                    name="{{ auth()->user()->name }}" 
                    email="{{ auth()->user()->email }}"
                />

                <flux:menu>
                    <flux:menu.item icon="user" href="{{ route('profile') }}" wire:navigate>
                        {{ __('Profile') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <livewire:logout-button />
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        {{-- Mobile Header --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="start">
                <flux:profile 
                    name="{{ auth()->user()->name }}" 
                    email="{{ auth()->user()->email }}"
                />

                <flux:menu>
                    <flux:menu.item icon="user" href="{{ route('profile') }}" wire:navigate>
                        {{ __('Profile') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <livewire:logout-button />
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{-- Main Content Area --}}
        <flux:main class="bg-zinc-50 dark:bg-zinc-900">
            @if (isset($header))
                {{ $header }}
                <flux:separator variant="subtle" class="my-6" />
            @endif

            {{ $slot }}
        </flux:main>
        
        @fluxScripts
    </body>
</html>
