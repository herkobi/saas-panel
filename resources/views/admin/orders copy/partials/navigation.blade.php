<div class="max-w-xs flex flex-col">
    <a class="inline-flex items-center gap-x-3.5 py-3 px-4 text-sm font-medium border border-gray-200 {{ request()->routeIs(['panel.users', 'panel.user.edit.*']) ? 'text-blue-600' : 'text-gray-800' }} -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:border-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        href="{{ route('panel.orders') }}">
        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="lucide lucide-wallet">
            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
            <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
            <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
        </svg>
        {{ __('admin/orders/navigation.orders') }}
    </a>
</div>
