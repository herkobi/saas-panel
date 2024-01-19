@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm mb-2 dark:text-white']) }}>
    {{ $value ?? $slot }}
</label>
