<header
    class="flex flex-wrap sm:justify-start sm:flex-nowrap z-50 w-full bg-gray-900 border-b border-gray-700 text-sm py-2.5 sm:py-4">
    <nav class="max-w-7xl flex basis-full items-center w-full mx-auto px-4 sm:px-6 lg:px-8" aria-label="Global">
        <div class="me-5 md:me-8">
            <a class="flex-none text-xl font-semibold text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                href="{{ route('panel.dashboard') }}" aria-label="Herkobi">Herkobi</a>
        </div>
        <div class="w-full flex items-center justify-end ms-auto sm:justify-between sm:gap-x-3 sm:order-3">
            <div class="sm:hidden">
                <button type="button"
                    class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-white hover:bg-white/20 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-1 focus:ring-gray-600">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
            </div>
            <div class="hidden mx-auto sm:block">
                <label for="icon" class="sr-only">{{ __('admin/dashboard/dashboard.search') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                        <svg class="flex-shrink-0 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <input type="text" id="icon" name="icon"
                        class="py-2 px-4 ps-11 pe-20 block w-92 md:w-96 bg-transparent border-gray-700 shadow-sm rounded-lg text-sm text-gray-300 focus:z-10 focus:border-gray-900 focus:ring-gray-600 placeholder:text-gray-500"
                        placeholder="{{ __('admin/dashboard/dashboard.search') }}">
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                        <span class="text-gray-500">Ctrl + /</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-row items-center justify-end gap-2">
                <div class="hs-dropdown relative inline-flex" data-hs-dropdown-placement="bottom-right">
                    <button id="hs-dropdown-with-header" type="button"
                        class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-white hover:bg-white/20 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-1 focus:ring-gray-600">
                        <img class="inline-block h-[2.375rem] w-[2.375rem] rounded-full"
                            src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                            alt="Image Description">
                    </button>
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[15rem] z-20 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700"
                        aria-labelledby="hs-dropdown-with-header">
                        <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('admin/dashboard/dashboard.logged') }}</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-300">
                                {{ auth()->user()->name . ' ' . auth()->user()->surname }}
                            </p>
                        </div>
                        <div class="mt-2 py-2 first:pt-0 last:pb-0">
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                href="{{ route('panel.profile.edit') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-user-check">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <polyline points="16 11 18 13 22 9" />
                                </svg>
                                {{ __('admin/dashboard/dashboard.profile') }}
                            </a>
                            <form method="POST" action="{{ route('panel.logout') }}">
                                @csrf
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                    href="{{ route('panel.logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-log-out">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        <polyline points="16 17 21 12 16 7" />
                                        <line x1="21" x2="9" y1="12" y2="12" />
                                    </svg>
                                    {{ __('admin/dashboard/dashboard.logout') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
