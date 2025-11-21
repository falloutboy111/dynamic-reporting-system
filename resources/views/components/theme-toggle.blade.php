{{-- Flux-powered theme toggle button --}}
<flux:button 
    x-data 
    x-on:click="$flux.dark = ! $flux.dark" 
    variant="ghost"
    icon="moon" 
    aria-label="Toggle dark mode"
    class="flex items-center gap-2"
>
    <flux:icon.sun x-show="! $flux.dark" class="size-5" />
    <flux:icon.moon x-show="$flux.dark" class="size-5" />
    <span x-text="$flux.dark ? '{{ __('Light Mode') }}' : '{{ __('Dark Mode') }}'"></span>
</flux:button>
