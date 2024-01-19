@props([
    'textColor' => 'text-gray-800',
    'hoverColor' => 'hover:text-blue-600',
    'disabledOpacity' => 'disabled:opacity-50',
    'disabledPointerEvents' => 'disabled:pointer-events-none',
    'darkTextColor' => 'dark:text-white',
    'darkHoverColor' => 'dark:hover:text-white/70',
    'darkFocusOutline' => 'dark:focus:outline-none',
    'darkFocusRing' => 'dark:focus:ring-1',
    'darkFocusRingColor' => 'dark:focus:ring-gray-600',
])

<button type="button"
    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg {{ $textColor }} {{ $hoverColor }} {{ $disabledOpacity }} {{ $disabledPointerEvents }} {{ $darkTextColor }} {{ $darkHoverColor }} {{ $darkFocusOutline }} {{ $darkFocusRing }} {{ $darkFocusRingColor }}">
    {{ $slot }}
</button>
