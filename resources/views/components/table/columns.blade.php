@props(['class' => ''])

<thead {{ $attributes->merge(['class' => 'bg-neutral-50 dark:bg-neutral-800']) }}>
    <tr>
        {{ $slot }}
    </tr>
</thead>

