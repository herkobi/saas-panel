<x-user-guest-layout>
    <div class="relative bg-gradient-to-bl from-blue-100 via-transparent dark:from-blue-950 dark:via-transparent">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="grid items-start md:grid-cols-2 gap-8 lg:gap-12">
                <div>
                    <img src="{{ asset('herkobi.png') }}" alt="Herkobi" class="h-auto max-w-[35%] mb-32">
                    <div class="mt-4 md:mb-12 max-w-2xl">
                        <h1 class="mb-4 font-semibold text-gray-800 text-3xl lg:text-4xl dark:text-gray-200">
                            {{ __('user/auth/verify.title') }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 font-medium">
                            {{ __('user/auth/verify.text') }}
                        </p>
                        @if (session('status') == 'verification-link-sent')
                            <div id="dismiss-alert"
                                class="hs-removing:translate-x-5 mt-5 hs-removing:opacity-0 transition duration-300 bg-teal-50 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 dark:bg-teal-800/10 dark:border-teal-900 dark:text-teal-500"
                                role="alert">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="flex-shrink-0 h-4 w-4 text-blue-600 mt-1"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                                            <path d="m9 12 2 2 4-4" />
                                        </svg>
                                    </div>
                                    <div class="ms-2">
                                        <div class="text-sm font-medium">
                                            {{ __('user/auth/verify.alert') }}
                                        </div>
                                    </div>
                                    <div class="ps-3 ms-auto">
                                        <div class="-mx-1.5 -my-1.5">
                                            <button type="button"
                                                class="inline-flex bg-teal-50 rounded-lg p-1.5 text-teal-500 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-teal-50 focus:ring-teal-600 dark:bg-transparent dark:hover:bg-teal-800/50 dark:text-teal-600"
                                                data-hs-remove-element="#dismiss-alert">
                                                <span class="sr-only">Dismiss</span>
                                                <svg class="flex-shrink-0 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="mt-2 flex items-center justify-start">
                        <div class="me-3">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div>
                                    <x-submit
                                        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        {{ __('Onay Kodunu Tekrar Gönder') }}
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect width="20" height="16" x="2" y="4" rx="2" />
                                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                        </svg>
                                    </x-submit>
                                </div>
                            </form>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('app.logout') }}">
                                @csrf
                                <x-submit
                                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-red-500 text-red-500 hover:border-red-400 hover:text-red-400 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                    {{ __('Oturumu Kapat') }}
                                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </x-submit>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-user-guest-layout>
