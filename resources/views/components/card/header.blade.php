@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'px-6 py-4 border-b border-neutral-200 dark:border-neutral-700 ' . $class]) }}>
    {{ $slot }}
</div>

