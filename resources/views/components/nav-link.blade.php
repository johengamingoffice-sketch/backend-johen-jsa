@props(['active', 'href'])

@php
$classes = ($active ?? false)
    ? 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-primary-700 dark:text-primary-400 bg-gradient-to-r from-primary-50 to-violet-50 dark:from-primary-950/40 dark:to-violet-950/40 border border-primary-100 dark:border-primary-900/50 transition-all duration-200'
    : 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if (isset($icon))
        {{ $icon }}
    @endif
    {{ $slot }}
</a>


