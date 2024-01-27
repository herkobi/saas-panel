<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg']) }}>
    {{ $slot }}
</button>
