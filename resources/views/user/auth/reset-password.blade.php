<x-user-guest-layout>
    <div class="relative h-[100vh] overflow-hidden">
        <div class="mx-auto max-w-screen-md py-12 px-4 sm:px-6 md:max-w-screen-xl md:px-8">
            <div class="md:pe-8 md:w-1/2 xl:pe-0 xl:w-5/12">
                <img src="{{ asset('herkobi.png') }}" alt="Herkobi" class="h-auto max-w-[45%] mb-12">
                <h1
                    class="text-xl text-gray-800 font-bold md:text-4xl md:leading-tight lg:text-4xl lg:leading-tight dark:text-gray-200">
                    {!! __('user/auth/password.reset.title') !!}</h1>
                <p class="mt-3 text-base text-gray-500 mb-6">
                    {{ __('user/auth/password.reset.text') }}
                </p>
                <form method="POST" action="{{ route('app.password.reset.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <div class="relative">
                        <x-input id="email"
                            class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                            type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="email" />
                        <label for="hs-hero-signup-form-floating-input-email"
                            class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.email') }}</label>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="relative mt-4">
                        <div class="relative">
                            <input type="password" name="password" id="hs-hero-signup-form-floating-input-new-password"
                                class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                                placeholder="********">
                            <button type="button"
                                data-hs-toggle-password='{
                                    "target": "#hs-hero-signup-form-floating-input-new-password"
                                  }'
                                class="absolute top-0 end-0 p-3.5 rounded-e-md dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                <svg class="flex-shrink-0 w-3.5 h-3.5 text-gray-400 dark:text-gray-600" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path class="hs-password-active:hidden"
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path class="hs-password-active:hidden"
                                        d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2"
                                        y2="22" />
                                    <path class="hidden hs-password-active:block"
                                        d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle class="hidden hs-password-active:block" cx="12" cy="12"
                                        r="3" />
                                </svg>
                            </button>
                            <label for="hs-hero-signup-form-floating-input-new-password"
                                class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.password') }}</label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div id="hs-strong-password-popover"
                            class="hidden absolute z-10 w-full bg-blue-50 rounded-lg p-4 dark:bg-blue-950">
                            <div id="hs-strong-password-in-popover"
                                data-hs-strong-password='{ "target": "#hs-hero-signup-form-floating-input-new-password", "hints": "#hs-strong-password-popover", "stripClasses": "hs-strong-password:opacity-100 hs-strong-password-accepted:bg-teal-500 h-2 flex-auto rounded-full bg-blue-500 opacity-50 mx-1", "mode": "popover"}'
                                class="flex mt-2 -mx-1">
                            </div>
                            <h4 class="mt-3 text-sm font-semibold text-gray-800 dark:text-white mb-3">
                                {{ __('user/auth/register.form.password.text') }}
                            </h4>
                            <ul class="space-y-1 text-sm text-gray-500">
                                <li data-hs-strong-password-hints-rule-text="min-length"
                                    class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                    <span class="hidden" data-check>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                    <span data-uncheck>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M18 6 6 18" />
                                            <path d="m6 6 12 12" />
                                        </svg>
                                    </span>
                                    {{ __('user/auth/register.form.password.min:8') }}
                                </li>
                                <li data-hs-strong-password-hints-rule-text="lowercase"
                                    class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                    <span class="hidden" data-check>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                    <span data-uncheck>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M18 6 6 18" />
                                            <path d="m6 6 12 12" />
                                        </svg>
                                    </span>
                                    {{ __('user/auth/register.form.password.lowercase') }}
                                </li>
                                <li data-hs-strong-password-hints-rule-text="uppercase"
                                    class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                    <span class="hidden" data-check>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                    <span data-uncheck>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M18 6 6 18" />
                                            <path d="m6 6 12 12" />
                                        </svg>
                                    </span>
                                    {{ __('user/auth/register.form.password.uppercase') }}
                                </li>
                                <li data-hs-strong-password-hints-rule-text="numbers"
                                    class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                    <span class="hidden" data-check>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                    <span data-uncheck>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M18 6 6 18" />
                                            <path d="m6 6 12 12" />
                                        </svg>
                                    </span>
                                    {{ __('user/auth/register.form.password.numbers') }}
                                </li>
                                <li data-hs-strong-password-hints-rule-text="special-characters"
                                    class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                    <span class="hidden" data-check>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                    <span data-uncheck>
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M18 6 6 18" />
                                            <path d="m6 6 12 12" />
                                        </svg>
                                    </span>
                                    {{ __('user/auth/register.form.password.character') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="password" name="password_confirmation"
                                    id="hs-hero-signup-form-floating-input-current-password"
                                    class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                                    placeholder="********">
                                <button type="button"
                                    data-hs-toggle-password='{
                                        "target": "#hs-hero-signup-form-floating-input-current-password"
                                      }'
                                    class="absolute top-0 end-0 p-3.5 rounded-e-md dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                    <svg class="flex-shrink-0 w-3.5 h-3.5 text-gray-400 dark:text-gray-600"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                        <path class="hs-password-active:hidden"
                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                        <path class="hs-password-active:hidden"
                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                        <line class="hs-password-active:hidden" x1="2" x2="22"
                                            y1="2" y2="22" />
                                        <path class="hidden hs-password-active:block"
                                            d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle class="hidden hs-password-active:block" cx="12" cy="12"
                                            r="3" />
                                    </svg>
                                </button>
                                <label for="hs-hero-signup-form-floating-input-current-password"
                                    class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.password.again') }}</label>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-submit
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            {{ __('user/auth/password.reset.submit') }}
                        </x-submit>
                    </div>
                </form>
            </div>
        </div>
        <div
            class="hidden md:block md:absolute md:top-0 md:start-1/2 md:end-0 h-full bg-[url('https://images.unsplash.com/photo-1606868306217-dbf5046868d2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1981&q=80')] bg-no-repeat bg-center bg-cover">
        </div>
    </div>
</x-user-guest-layout>
