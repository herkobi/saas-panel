@props(['id', 'title', 'content'])

<div class="hs-accordion" id="{{ $id }}">
    <button
        class="hs-accordion-toggle hs-accordion-active:text-blue-600 py-3 inline-flex items-center gap-x-3 w-full font-semibold text-start text-gray-800 hover:text-gray-500 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:hs-accordion-active:text-blue-500 dark:text-gray-200 dark:hover:text-gray-400 dark:focus:outline-none dark:focus:text-gray-400"
        aria-controls="{{ $id }}-collapse">
        <svg class="hs-accordion-active:hidden block w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M5 12h14" />
            <path d="M12 5v14" />
        </svg>
        <svg class="hs-accordion-active:block hidden w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14" />
        </svg>
        {{ $title }}
    </button>
    <div id="{{ $id }}-collapse"
        class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
        aria-labelledby="{{ $id }}">
        <p class="text-gray-800 dark:text-gray-200">
            {!! $content !!}
        </p>
    </div>
</div>
