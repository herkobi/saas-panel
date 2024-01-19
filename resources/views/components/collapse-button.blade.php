@props(['id' => '', 'toggleClasses' => '', 'collapseClasses' => '', 'content' => '', 'collapseText' => 'Collapse'])

<button type="button" class="{{ $toggleClasses }}" id="{{ $id }}-toggle"
    data-hs-collapse="#{{ $id }}-content">
    {{ $collapseText }}
    <svg class="hs-collapse-open:rotate-180 flex-shrink-0 w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg"
        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="m6 9 6 6 6-6" />
    </svg>
</button>

<div id="{{ $id }}-content" class="{{ $collapseClasses }}" aria-labelledby="{{ $id }}-toggle">
    {{ $content }}
</div>
