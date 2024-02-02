<div class="max-w-xs flex flex-col">
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium border border-gray-200 {{ request()->routeIs(['panel.users', 'panel.user.edit.*']) ? 'text-blue-600' : 'text-gray-800' }} -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:border-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.users') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-users">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        {{ __('admin/users/navigation.users') }}
    </a>
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium bg-white border border-gray-200 {{ request()->routeIs(['panel.settings.payments', 'panel.settings.payment.*', 'panel.gateways.*']) ? 'text-blue-600' : 'text-gray-800' }} -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg hover:text-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:text-blue-600 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.settings.payments') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-user-plus">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <line x1="19" x2="19" y1="8" y2="14" />
            <line x1="22" x2="16" y1="11" y2="11" />
        </svg>
        {{ __('admin/users/navigation.add.user') }}
    </a>
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium bg-white border border-gray-200 {{ request()->routeIs(['panel.profile.edit']) ? 'text-blue-600' : 'text-gray-800' }} -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg hover:text-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:text-blue-600 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.profile.edit') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-circle-user">
            <circle cx="12" cy="12" r="10" />
            <circle cx="12" cy="10" r="3" />
            <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" />
        </svg>
        {{ __('admin/users/navigation.profile') }}
    </a>
</div>
