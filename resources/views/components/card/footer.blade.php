@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 ' . $class]) }}>
    {{ $slot }}
</div>

