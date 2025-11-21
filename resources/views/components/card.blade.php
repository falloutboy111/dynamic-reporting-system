@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-neutral-800 shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden ' . $class]) }}>
    {{ $slot }}
</div>

