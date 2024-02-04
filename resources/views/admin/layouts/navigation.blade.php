<nav class="sticky -top-px bg-white font-medium text-black ring-1 ring-gray-900 ring-opacity-5 shadow-sm shadow-gray-100 pt-3 pb-3 -mt-px dark:bg-slate-900 dark:border-gray-800 dark:shadow-slate-700/[.7] z-10"
    aria-label="Jump links">
    <div
        class="max-w-7xl snap-x w-full flex items-center overflow-x-auto px-4 sm:px-6 lg:px-8 pb-4 md:pb-0 mx-auto [&::-webkit-scrollbar]:h-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-slate-900">
        <div class="snap-center shrink-0">
            <div class="relative">
                <a class="flex items-center gap-x-2 py-1.5 px-2.5 text-slate-700 font-medium me-1 hover:bg-gray-100 hover:rounded-lg {{ request()->routeIs('panel.dashboard') ? 'bg-gray-100 rounded-lg' : '' }}"
                    href="{{ route('panel.dashboard') }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-gauge">
                        <path d="m12 14 4-4" />
                        <path d="M3.34 19a10 10 0 1 1 17.32 0" />
                    </svg>
                    {{ __('admin/dashboard/navigation.dashboard') }}
                </a>
            </div>
        </div>
        <div class="snap-center shrink-0">
            <div class="relative">
                <a class="flex items-center gap-x-2 py-1.5 px-2.5 text-slate-700 font-medium me-1 hover:bg-gray-100 hover:rounded-lg {{ request()->routeIs(['panel.accounts', 'panel.account.*']) ? 'bg-gray-100 rounded-lg' : '' }}"
                    href="{{ route('panel.accounts') }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-users">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    {{ __('admin/dashboard/navigation.account') }}
                </a>
            </div>
        </div>
        <div class="snap-center shrink-0">
            <div class="relative">
                <a class="flex items-center gap-x-2 py-1.5 px-2.5 text-slate-700 font-medium me-1 hover:bg-gray-100 hover:rounded-lg"
                    href="#">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-package-check">
                        <path d="m16 16 2 2 4-4" />
                        <path
                            d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                        <path d="m7.5 4.27 9 5.15" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <line x1="12" x2="12" y1="22" y2="12" />
                    </svg>
                    {{ __('admin/dashboard/navigation.plan') }}
                </a>
            </div>
        </div>
        <div class="snap-center shrink-0">
            <div class="relative">
                <a class="flex items-center gap-x-2 py-1.5 px-2.5 text-slate-700 font-medium me-1 hover:bg-gray-100 hover:rounded-lg {{ request()->routeIs(['panel.orders', 'panel.order.*']) ? 'bg-gray-100 rounded-lg' : '' }}"
                    href="{{ route('panel.orders') }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-wallet">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                        <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                    </svg>
                    {{ __('admin/dashboard/navigation.order') }}
                </a>
            </div>
        </div>
        <div class="snap-center shrink-0">
            <div class="relative">
                <a class="flex items-center gap-x-2 py-1.5 px-2.5 text-slate-700 font-medium me-1 hover:bg-gray-100 hover:rounded-lg {{ request()->routeIs(['panel.users', 'panel.user.*']) ? 'bg-gray-100 rounded-lg' : '' }}"
                    href="{{ route('panel.users') }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-user-round-check">
                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="m16 19 2 2 4-4" />
                    </svg>
                    {{ __('admin/dashboard/navigation.user') }}
                </a>
            </div>
        </div>
        <div class="snap-center shrink-0">
            <div class="relative">
                <a class="flex items-center gap-x-2 py-1.5 px-2.5 text-slate-700 font-medium hover:bg-gray-100 hover:rounded-lg {{ request()->routeIs('panel.settings.*') ? 'bg-gray-100 rounded-lg' : '' }}"
                    href="{{ route('panel.settings.general') }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-settings">
                        <path
                            d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    {{ __('admin/dashboard/navigation.setting') }}
                </a>
            </div>
        </div>
    </div>
</nav>
