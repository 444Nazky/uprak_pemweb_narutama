@props(['name' => 'dot'])

<svg {{ $attributes->merge(['class' => 'icon']) }} aria-hidden="true" focusable="false">
    <use href="{{ asset('vendor/icons/bootstrap-icons.svg') }}#{{ $name }}"></use>
</svg>
