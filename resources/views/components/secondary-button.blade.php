@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'btn-secondary']) !!}>
    {{ $slot }}
</button>
