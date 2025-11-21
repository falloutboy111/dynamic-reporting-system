@props(['class' => ''])

<tr {{ $attributes->merge(['class' => 'hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors']) }}>
    {{ $slot }}
</tr>

