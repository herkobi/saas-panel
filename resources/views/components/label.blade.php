@props(['for', 'value'])

<label {{ $attributes->merge(['class' => 'block text-sm dark:text-white']) }} for="{{ $for }}">
    {{ $value ?? $slot }}
</label>
