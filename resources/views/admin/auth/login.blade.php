<x-admin-guest-layout>
    <div class="mt-7 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
            <div class="mb-6">
                <img src="{{ asset('herkobi.png') }}" alt="{{ __('global.app') }}" class="max-w-[60%]">
            </div>
            <div class="text-left">
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                    {{ __('admin/auth/login.form.title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('admin/auth/login.form.text') }}
                </p>
            </div>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <div class="mt-5">
                <form method="post" action="{{ route('panel.login') }}">
                    @csrf
                    <div class="grid gap-y-4">
                        <div>
                            <x-label for="email" class="block text-sm mb-2 dark:text-white">
                                {{ __('admin/auth/login.form.email') }}
                            </x-label>
                            <div class="relative">
                                <x-input type="text" id="input_type" name="input_type"
                                    class="peer py-2 px-4 ps-11 block w-full" :value="old('input_type')" required autofocus
                                    autocomplete="input_type" />
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <svg class="flex-shrink-0 w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                        <div>
                            <div class="flex justify-between items-center">
                                <x-label for="password"
                                    class="block text-sm mb-2 dark:text-white">{{ __('admin/auth/login.form.password') }}</x-label>
                                <a class="text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                    href="{{ route('panel.forgot.password') }}">{{ __('admin/auth/login.form.forgot.password') }}</a>
                            </div>
                            <div class="relative">
                                <x-input type="password" id="password" name="password"
                                    class="peer py-2 px-4 ps-11 block w-full" :value="old('password')" required
                                    autocomplete="current-password" />
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <svg class="flex-shrink-0 w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-asterisk-square">
                                        <rect width="18" height="18" x="3" y="3" rx="2" />
                                        <path d="M12 8v8" />
                                        <path d="m8.5 14 7-4" />
                                        <path d="m8.5 10 7 4" />
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="flex items-center">
                            <div class="flex">
                                <input id="remember-me" name="remember" type="checkbox"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                            </div>
                            <div class="ms-3">
                                <x-label for="remember-me"
                                    class="text-sm dark:text-white">{{ __('admin/auth/login.form.remember.me') }}</x-label>
                            </div>
                        </div>
                        <x-submit
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">{{ __('admin/auth/login.form.login') }}</x-submit>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-guest-layout>
