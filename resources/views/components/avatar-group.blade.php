<div x-data="{ openTooltip: null }" class="flex -space-x-2">
    @foreach ($images as $key => $image)
        <div x-data="{ openTooltip: null }" class="hs-tooltip inline-block">
            <img @mouseenter="openTooltip = '{{ $key }}'" @mouseleave="openTooltip = null"
                class="hs-tooltip-toggle relative inline-block h-[2.875rem] w-[2.875rem] rounded-full ring-2 ring-white hover:z-10"
                src="{{ $image['src'] }}" alt="{{ $image['alt'] }}">
            <span x-show="openTooltip === '{{ $key }}'"
                class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg dark:bg-neutral-700"
                role="tooltip">
                {{ $image['name'] }}
            </span>
        </div>
    @endforeach
</div>
