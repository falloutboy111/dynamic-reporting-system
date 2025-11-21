@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'px-6 py-4 bg-neutral-50 dark:bg-neutral-900 border-t border-neutral-200 dark:border-neutral-700 ' . $class]) }}>
    {{ $slot }}
</div>

