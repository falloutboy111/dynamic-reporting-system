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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
        {{-- Desktop and Mobile Sidebar --}}
        <flux:sidebar sticky collapsible="mobile" class="bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="{{ route('dashboard') }}"
                    name="{{ config('app.name', 'Laravel') }}"
                    logo="https://www.enclivix.com/images/logos/logo.svg"
                />

                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.search placeholder="Search..." />

            <flux:sidebar.nav>
                <flux:sidebar.item 
                    icon="home" 
                    href="{{ route('dashboard') }}" 
                    :current="request()->routeIs('dashboard')"
                    wire:navigate
                >
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.item 
                    icon="arrow-path" 
                    href="{{ route('client-sync.index') }}" 
                    :current="request()->routeIs('client-sync.*')"
                    wire:navigate
                >
                    {{ __('Client Sync') }}
                </flux:sidebar.item>

                @if(auth()->user()->hasRole('admin'))
                <flux:sidebar.item 
                    icon="users" 
                    href="{{ route('users.index') }}" 
                    :current="request()->routeIs('users.*')"
                    wire:navigate
                >
                    {{ __('Users') }}
                </flux:sidebar.item>
                @endif

                <flux:sidebar.item 
                    icon="document-text" 
                    href="{{ route('logs.index') }}" 
                    :current="request()->routeIs('logs.*')"
                    wire:navigate
                >
                    {{ __('Logs') }}
                </flux:sidebar.item>

                <flux:sidebar.item 
                    icon="chart-bar" 
                    href="{{ route('reports.index') }}" 
                    :current="request()->routeIs('reports.*')"
                    wire:navigate
                >
                    {{ __('Reports') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            {{-- Theme Switcher --}}
            <div class="px-2 py-2 border-t border-zinc-200 dark:border-zinc-700">
                <!-- <button 
                    type="button"
                    x-data="{ 
                        toggleTheme() {
                            const isDark = localStorage.getItem('flux_theme') === 'dark';
                            if (isDark) {
                                localStorage.setItem('flux_theme', 'light');
                                document.documentElement.classList.remove('dark');
                            } else {
                                localStorage.setItem('flux_theme', 'dark');
                                document.documentElement.classList.add('dark');
                            }
                        }
                    }"
                    @click="toggleTheme()"
                    class="flex items-center gap-3 w-full px-1 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors"
                >
                    <flux:icon.moon class="size-5 hidden dark:block" />
                    <flux:icon.sun class="size-5 dark:hidden" />
                    <span class="dark:hidden">{{ __('Dark Mode') }}</span>
                    <span class="hidden dark:inline">{{ __('Light Mode') }}</span>
                </button> -->
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
