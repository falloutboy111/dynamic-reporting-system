@props(['class' => ''])

<td {{ $attributes->merge(['class' => 'px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100']) }}>
    {{ $slot }}
</td>

