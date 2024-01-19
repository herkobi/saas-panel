@props([
    'bgColor' => 'gray-800',
    'textColor' => 'text-white',
    'darkBgColor' => 'white',
    'darkTextColor' => 'text-gray-800',
])

<span
    class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-{{ $bgColor }} {{ $textColor }} dark:bg-{{ $darkBgColor }} dark:{{ $darkTextColor }}">{{ $slot }}</span>
