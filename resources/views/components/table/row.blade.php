@props(['class' => ''])

<tr {{ $attributes->merge(['class' => 'hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors']) }}>
    {{ $slot }}
</tr>

