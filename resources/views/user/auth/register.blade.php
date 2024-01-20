<x-user-guest-layout>
    <div class="relative bg-gradient-to-bl from-blue-100 via-transparent dark:from-blue-950 dark:via-transparent">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="grid items-start md:grid-cols-2 gap-8 lg:gap-12">
                <div>
                    <img src="{{ asset('herkobi.png') }}" alt="Herkobi" class="h-auto max-w-[40%] mb-32">
                    <div class="mt-4 md:mb-12 max-w-2xl">
                        <h1 class="mb-4 font-semibold text-gray-800 text-3xl lg:text-4xl dark:text-gray-200">
                            {{ __('user/auth/register.page.title') }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('user/auth/register.page.text') }}
                        </p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('app.register') }}" autocomplete="off">
                        @csrf
                        <div class="lg:max-w-lg lg:mx-auto lg:me-0 ms-auto">
                            <div class="p-4 sm:p-7 flex flex-col bg-white rounded-2xl shadow-lg dark:bg-slate-900">
                                <div class="text-center">
                                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                                        {{ __('user/auth/register.form.title') }}</h1>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('user/auth/register.login.text') }}
                                        <a class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                            href="{{ route('app.login') }}">
                                            {{ __('user/auth/register.login.link') }}
                                        </a>
                                    </p>
                                </div>
                                <div
                                    class="py-3 mt-5 flex items-center text-xs text-gray-400 uppercase before:flex-[1_1_0%] before:border-t before:border-gray-200 before:me-6 after:flex-[1_1_0%] after:border-t after:border-gray-200 after:ms-6 dark:text-gray-500 dark:before:border-gray-700 dark:after:border-gray-700">
                                    {{ __('user/auth/register.form.register') }}</div>
                                <div class="mt-5">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="relative">
                                                <input type="text" id="hs-hero-signup-form-floating-input-first-name"
                                                    name="name"
                                                    class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
                        focus:pt-6
                        focus:pb-2
                        [&:not(:placeholder-shown)]:pt-6
                        [&:not(:placeholder-shown)]:pb-2
                        autofill:pt-6
                        autofill:pb-2"
                                                    placeholder="{{ __('user/auth/register.form.name') }}"
                                                    value="{{ old('name') }}" required>
                                                <label for="hs-hero-signup-form-floating-input-first-name"
                                                    class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none
                            peer-focus:text-xs
                            peer-focus:-translate-y-1.5
                            peer-focus:text-gray-500
                            peer-[:not(:placeholder-shown)]:text-xs
                            peer-[:not(:placeholder-shown)]:-translate-y-1.5
                            peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.name') }}</label>
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="relative">
                                                <input type="text" id="hs-hero-signup-form-floating-input-last-name"
                                                    name="surname"
                                                    class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
                        focus:pt-6
                        focus:pb-2
                        [&:not(:placeholder-shown)]:pt-6
                        [&:not(:placeholder-shown)]:pb-2
                        autofill:pt-6
                        autofill:pb-2"
                                                    placeholder="{{ __('user/auth/register.form.surname') }}"
                                                    value="{{ old('surname') }}" required>
                                                <label for="hs-hero-signup-form-floating-input-last-name"
                                                    class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none
                            peer-focus:text-xs
                            peer-focus:-translate-y-1.5
                            peer-focus:text-gray-500
                            peer-[:not(:placeholder-shown)]:text-xs
                            peer-[:not(:placeholder-shown)]:-translate-y-1.5
                            peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.surname') }}</label>
                                                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="relative col-span-full">
                                            <input type="email" id="hs-hero-signup-form-floating-input-email"
                                                name="email"
                                                class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
                        focus:pt-6
                        focus:pb-2
                        [&:not(:placeholder-shown)]:pt-6
                        [&:not(:placeholder-shown)]:pb-2
                        autofill:pt-6
                        autofill:pb-2"
                                                placeholder="{{ __('user/auth/register.form.email') }}"
                                                value="{{ old('email') }}" required>
                                            <label for="hs-hero-signup-form-floating-input-email"
                                                class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none
                            peer-focus:text-xs
                            peer-focus:-translate-y-1.5
                            peer-focus:text-gray-500
                            peer-[:not(:placeholder-shown)]:text-xs
                            peer-[:not(:placeholder-shown)]:-translate-y-1.5
                            peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.email') }}</label>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        <div class="relative col-span-full">
                                            <div class="relative">
                                                <input type="password" name="password"
                                                    id="hs-hero-signup-form-floating-input-new-password"
                                                    class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
                        focus:pt-6
                        focus:pb-2
                        [&:not(:placeholder-shown)]:pt-6
                        [&:not(:placeholder-shown)]:pb-2
                        autofill:pt-6
                        autofill:pb-2"
                                                    placeholder="********">
                                                <button type="button"
                                                    data-hs-toggle-password='{
                                                        "target": "#hs-hero-signup-form-floating-input-new-password"
                                                      }'
                                                    class="absolute top-0 end-0 p-3.5 rounded-e-md dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="flex-shrink-0 w-3.5 h-3.5 text-gray-400 dark:text-gray-600"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path class="hs-password-active:hidden"
                                                            d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                                        <path class="hs-password-active:hidden"
                                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                                        <path class="hs-password-active:hidden"
                                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                                        <line class="hs-password-active:hidden" x1="2"
                                                            x2="22" y1="2" y2="22" />
                                                        <path class="hidden hs-password-active:block"
                                                            d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                        <circle class="hidden hs-password-active:block" cx="12"
                                                            cy="12" r="3" />
                                                    </svg>
                                                </button>
                                                <label for="hs-hero-signup-form-floating-input-new-password"
                                                    class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none
                            peer-focus:text-xs
                            peer-focus:-translate-y-1.5
                            peer-focus:text-gray-500
                            peer-[:not(:placeholder-shown)]:text-xs
                            peer-[:not(:placeholder-shown)]:-translate-y-1.5
                            peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.password') }}</label>
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>
                                            <div id="hs-strong-password-popover"
                                                class="hidden absolute z-10 w-full bg-blue-50 rounded-lg p-4 dark:bg-blue-950">
                                                <div id="hs-strong-password-in-popover"
                                                    data-hs-strong-password='{
                            "target": "#hs-hero-signup-form-floating-input-new-password",
                            "hints": "#hs-strong-password-popover",
                            "stripClasses": "hs-strong-password:opacity-100 hs-strong-password-accepted:bg-teal-500 h-2 flex-auto rounded-full bg-blue-500 opacity-50 mx-1",
                            "mode": "popover"
                            }'
                                                    class="flex mt-2 -mx-1">
                                                </div>
                                                <h4
                                                    class="mt-3 text-sm font-semibold text-gray-800 dark:text-white mb-3">
                                                    {{ __('user/auth/register.form.password.text') }}
                                                </h4>
                                                <ul class="space-y-1 text-sm text-gray-500">
                                                    <li data-hs-strong-password-hints-rule-text="min-length"
                                                        class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                                        <span class="hidden" data-check>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12" />
                                                            </svg>
                                                        </span>
                                                        <span data-uncheck>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </span>
                                                        {{ __('user/auth/register.form.password.min:8') }}
                                                    </li>
                                                    <li data-hs-strong-password-hints-rule-text="lowercase"
                                                        class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                                        <span class="hidden" data-check>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12" />
                                                            </svg>
                                                        </span>
                                                        <span data-uncheck>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </span>
                                                        {{ __('user/auth/register.form.password.lowercase') }}
                                                    </li>
                                                    <li data-hs-strong-password-hints-rule-text="uppercase"
                                                        class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                                        <span class="hidden" data-check>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12" />
                                                            </svg>
                                                        </span>
                                                        <span data-uncheck>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </span>
                                                        {{ __('user/auth/register.form.password.uppercase') }}
                                                    </li>
                                                    <li data-hs-strong-password-hints-rule-text="numbers"
                                                        class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                                        <span class="hidden" data-check>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12" />
                                                            </svg>
                                                        </span>
                                                        <span data-uncheck>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </span>
                                                        {{ __('user/auth/register.form.password.numbers') }}
                                                    </li>
                                                    <li data-hs-strong-password-hints-rule-text="special-characters"
                                                        class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                                        <span class="hidden" data-check>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12" />
                                                            </svg>
                                                        </span>
                                                        <span data-uncheck>
                                                            <svg class="flex-shrink-0 w-4 h-4"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </span>
                                                        {{ __('user/auth/register.form.password.character') }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-span-full">
                                            <div class="relative">
                                                <input type="password" name="password_confirmation"
                                                    id="hs-hero-signup-form-floating-input-current-password"
                                                    class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
                        focus:pt-6
                        focus:pb-2
                        [&:not(:placeholder-shown)]:pt-6
                        [&:not(:placeholder-shown)]:pb-2
                        autofill:pt-6
                        autofill:pb-2"
                                                    placeholder="********">
                                                <button type="button"
                                                    data-hs-toggle-password='{
                                                        "target": "#hs-hero-signup-form-floating-input-current-password"
                                                      }'
                                                    class="absolute top-0 end-0 p-3.5 rounded-e-md dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="flex-shrink-0 w-3.5 h-3.5 text-gray-400 dark:text-gray-600"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path class="hs-password-active:hidden"
                                                            d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                                        <path class="hs-password-active:hidden"
                                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                                        <path class="hs-password-active:hidden"
                                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                                        <line class="hs-password-active:hidden" x1="2"
                                                            x2="22" y1="2" y2="22" />
                                                        <path class="hidden hs-password-active:block"
                                                            d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                        <circle class="hidden hs-password-active:block" cx="12"
                                                            cy="12" r="3" />
                                                    </svg>
                                                </button>
                                                <label for="hs-hero-signup-form-floating-input-current-password"
                                                    class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none
                            peer-focus:text-xs
                            peer-focus:-translate-y-1.5
                            peer-focus:text-gray-500
                            peer-[:not(:placeholder-shown)]:text-xs
                            peer-[:not(:placeholder-shown)]:-translate-y-1.5
                            peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.password.again') }}</label>
                                                <x-input-error :messages="$errors->get('confirm_password')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 flex items-center">
                                        <div class="flex">
                                            <input id="remember-me" name="remember-me" type="checkbox"
                                                class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                        </div>
                                        <div class="ms-3">
                                            <label for="remember-me" class="text-sm dark:text-white">
                                                <a class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                    href="#">{{ __('user/auth/register.form.terms.link') }}</a>
                                                {{ __('user/auth/register.form.terms') }}</label>
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <button type="submit"
                                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">{{ __('user/auth/register.form.submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-user-guest-layout>
