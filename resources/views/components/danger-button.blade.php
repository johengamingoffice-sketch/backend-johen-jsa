@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'btn-danger']) !!}>
    {{ $slot }}
</button>
