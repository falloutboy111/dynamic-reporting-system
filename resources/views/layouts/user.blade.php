<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
                    href="{{ route('user.dashboard') }}"
                    name="{{ config('app.name', 'Laravel') }}"
                    logo="https://www.enclivix.com/images/logos/logo.svg"
                />

                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.search placeholder="Search..." />

            <flux:sidebar.nav>
                <flux:sidebar.item 
                    icon="home" 
                    href="{{ route('user.dashboard') }}" 
                    :current="request()->routeIs('user.dashboard')"
                    wire:navigate
                >
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.item 
                    icon="chart-bar" 
                    href="{{ route('user.reports.index') }}" 
                    :current="request()->routeIs('user.reports.*')"
                    wire:navigate
                >
                    {{ __('Reports') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            {{-- Theme Switcher --}}
            <div class="px-2 py-2 border-t border-zinc-200 dark:border-zinc-700">
                <flux:dropdown align="end">
                    <flux:button variant="ghost" size="sm" class="w-full justify-start" x-data>
                        <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini" class="size-5" />
                        <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini" class="size-5" />
                        <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" class="size-5" />
                        <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" class="size-5" />
                        <span>{{ __('Theme') }}</span>
                    </flux:button>
                    
                    <flux:menu>
                        <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">
                            {{ __('Light') }}
                        </flux:menu.item>
                        <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">
                            {{ __('Dark') }}
                        </flux:menu.item>
                        <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">
                            {{ __('System') }}
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>

            {{-- Organization Info --}}
            @if(auth()->user()->organisation)
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    {{ __('Organization') }}
                </div>
                <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mt-1">
                    {{ auth()->user()->organisation->name }}
                </div>
            </div>
            @endif

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

