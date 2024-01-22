<div class="bg-white dark:bg-slate-900 max-w-[60%]">
    <header class="mb-4">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('user/profile/profile.delete.form.title') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('user/profile/profile.delete.form.text') }}
        </p>
    </header>
    <x-danger-button
        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
        data-hs-overlay="#hs-danger-alert">
        {{ __('user/profile/profile.delete.form.delete.button') }}
    </x-danger-button>

    <div id="hs-danger-alert"
        class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
        <form method="post" action="{{ route('app.profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all md:max-w-2xl md:w-full m-3 md:mx-auto">
                <div
                    class="relative flex flex-col bg-white border shadow-sm rounded-xl overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                    <div class="absolute top-2 end-2">
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-transparent dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#hs-danger-alert">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-10 overflow-y-auto">
                        <div class="flex gap-x-4 md:gap-x-7">
                            <!-- Icon -->
                            <span
                                class="flex-shrink-0 inline-flex justify-center items-center w-[46px] h-[46px] sm:w-[62px] sm:h-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                                <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                            </span>
                            <!-- End Icon -->
                            <div class="grow">
                                <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                                    Delete Personal Account
                                </h3>
                                <p class="text-gray-500">
                                    Permanently remove your Personal Account and all of its contents from the Vercel
                                    platform. This action is not reversible, so please continue with caution.
                                </p>
                                <div class="mt-4">
                                    <x-label for="password" value="{{ __('Password') }}" class="sr-only" />
                                    <x-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                                        placeholder="{{ __('Password') }}" />
                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex justify-end items-center gap-x-2 py-3 px-4 bg-gray-50 border-t dark:bg-gray-800 dark:border-gray-700">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#hs-danger-alert">
                            Cancel
                        </button>
                        <x-submit
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-500 text-white hover:bg-red-600 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Delete personal account
                        </x-submit>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{--
    <x-confirm-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('app.profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>
            <div class="mt-6">
                <x-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-button>
                <x-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-button>
            </div>
        </form>
    </x-confirm-modal> --}}
</div>
