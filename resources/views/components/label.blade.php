@props(['for', 'value'])

<label {{ $attributes->merge(['class' => 'block text-sm mb-2 dark:text-white']) }} for="{{ $for }}">
    {{ $value ?? $slot }}
</label>
