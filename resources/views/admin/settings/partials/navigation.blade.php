<div class="max-w-xs flex flex-col">
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium border border-gray-200 {{ request()->routeIs('panel.settings.general') ? 'text-blue-600' : '' }} -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:border-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.settings.general') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-file-cog">
            <path d="M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v2" />
            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
            <circle cx="6" cy="14" r="3" />
            <path d="M6 10v1" />
            <path d="M6 17v1" />
            <path d="M10 14H9" />
            <path d="M3 14H2" />
            <path d="m9 11-.88.88" />
            <path d="M3.88 16.12 3 17" />
            <path d="m9 17-.88-.88" />
            <path d="M3.88 11.88 3 11" />
        </svg>
        {{ __('admin/settings/navigation.menu.general') }}
    </a>
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium bg-white border border-gray-200 text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg hover:text-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:text-blue-600 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.settings.general') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-credit-card">
            <rect width="20" height="14" x="2" y="5" rx="2" />
            <line x1="2" x2="22" y1="10" y2="10" />
        </svg>
        {{ __('admin/settings/navigation.menu.payments') }}
    </a>
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium bg-white border border-gray-200 text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg hover:text-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:text-blue-600 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.settings.general') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-map-pinned">
            <path d="M18 8c0 4.5-6 9-6 9s-6-4.5-6-9a6 6 0 0 1 12 0" />
            <circle cx="12" cy="8" r="2" />
            <path
                d="M8.835 14H5a1 1 0 0 0-.9.7l-2 6c-.1.1-.1.2-.1.3 0 .6.4 1 1 1h18c.6 0 1-.4 1-1 0-.1 0-.2-.1-.3l-2-6a1 1 0 0 0-.9-.7h-3.835" />
        </svg>
        {{ __('admin/settings/navigation.menu.locations') }}
    </a>
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium bg-white border border-gray-200 text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg hover:text-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:text-blue-600 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.settings.general') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-bitcoin">
            <path
                d="M11.767 19.089c4.924.868 6.14-6.025 1.216-6.894m-1.216 6.894L5.86 18.047m5.908 1.042-.347 1.97m1.563-8.864c4.924.869 6.14-6.025 1.215-6.893m-1.215 6.893-3.94-.694m5.155-6.2L8.29 4.26m5.908 1.042.348-1.97M7.48 20.364l3.126-17.727" />
        </svg>
        {{ __('admin/settings/navigation.menu.currencies') }}
    </a>
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium bg-white border border-gray-200 text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg hover:text-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:text-blue-600 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.settings.general') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-notebook-text">
            <path d="M2 6h4" />
            <path d="M2 10h4" />
            <path d="M2 14h4" />
            <path d="M2 18h4" />
            <rect width="16" height="20" x="4" y="2" rx="2" />
            <path d="M9.5 8h5" />
            <path d="M9.5 12H16" />
            <path d="M9.5 16H14" />
        </svg>
        {{ __('admin/settings/navigation.menu.pages') }}
    </a>
</div>
