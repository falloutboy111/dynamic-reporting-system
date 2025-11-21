@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700 shadow-sm']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            {{ $slot }}
        </table>
    </div>
</div>

