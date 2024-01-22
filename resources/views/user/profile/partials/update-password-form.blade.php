<div class="bg-white dark:bg-slate-900 max-w-[60%]">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
            {{ __('user/profile/profile.password.form.title') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('user/profile/profile.password.form.text') }}
        </p>
    </div>
    <form method="post" action="{{ route('app.password.update') }}">
        @csrf
        @method('put')
        <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
            <div class="sm:col-span-3">
                <x-label for="af-current-password" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    {{ __('user/profile/profile.password.form.current') }}
                </x-label>
            </div>
            <div class="sm:col-span-9">
                <div class="relative">
                    <input type="password" name="current_password"
                        id="hs-hero-signup-form-floating-input-current-password"
                        class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                        placeholder="********" autocomplete="new-password">
                    <button type="button"
                        data-hs-toggle-password='{
                            "target": "#hs-hero-signup-form-floating-input-current-password"
                          }'
                        class="absolute top-0 end-0 p-3.5 rounded-e-md dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg class="flex-shrink-0 w-3.5 h-3.5 text-gray-400 dark:text-gray-600" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                            <path class="hs-password-active:hidden"
                                d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                            <path class="hs-password-active:hidden"
                                d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                            <line class="hs-password-active:hidden" x1="2" x2="22" y1="2"
                                y2="22" />
                            <path class="hidden hs-password-active:block"
                                d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                            <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                    <label for="hs-hero-signup-form-floating-input-current-password"
                        class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/profile/profile.password.form.current') }}</label>
                </div>
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>
            <div class="sm:col-span-3">
                <x-label for="af-new-password" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    {{ __('user/profile/profile.password.form.new') }}
                </x-label>
            </div>
            <div class="sm:col-span-9">
                <div class="relative">
                    <div class="relative">
                        <input type="password" name="password" id="hs-hero-signup-form-floating-input-new-password"
                            class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                            placeholder="********" autocomplete="new-password">
                        <button type="button"
                            data-hs-toggle-password='{
                                "target": "#hs-hero-signup-form-floating-input-new-password"
                              }'
                            class="absolute top-0 end-0 p-3.5 rounded-e-md dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-gray-400 dark:text-gray-600" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                <path class="hs-password-active:hidden"
                                    d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                <path class="hs-password-active:hidden"
                                    d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                <line class="hs-password-active:hidden" x1="2" x2="22" y1="2"
                                    y2="22" />
                                <path class="hidden hs-password-active:block"
                                    d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3" />
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
                                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
            <div class="sm:col-span-3">
                <x-label for="af-confirm-password"
                    class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    {{ __('user/profile/profile.password.form.confirm') }}
                </x-label>
            </div>
            <div class="sm:col-span-9">
                <div class="relative">
                    <input type="password" name="password_confirmation"
                        id="hs-hero-signup-form-floating-input-new-password"
                        class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                        placeholder="********" autocomplete="new-password">
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
                            <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                    <label for="hs-hero-signup-form-floating-input-new-password"
                        class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">{{ __('user/auth/register.form.password.again') }}</label>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
        <div class="mt-5 flex justify-end gap-x-2">
            <x-submit
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">{{ __('user/profile/profile.password.form.submit') }}</x-submit>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('global.saved') }}</p>
            @endif
        </div>
    </form>
</div>
