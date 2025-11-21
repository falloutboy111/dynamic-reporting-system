@props(['class' => ''])

<tbody {{ $attributes->merge(['class' => 'bg-white dark:bg-neutral-900 divide-y divide-neutral-200 dark:divide-neutral-700']) }}>
    {{ $slot }}
</tbody>

