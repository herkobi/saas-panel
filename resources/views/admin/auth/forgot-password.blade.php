<x-admin-guest-layout>
    <div class="mt-7 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
            <div class="mb-6">
                <img src="{{ asset('herkobi.png') }}" alt="{{ __('global.app') }}" class="max-w-[60%]">
            </div>
            <div class="text-left">
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                    {{ __('admin/auth/forgot.title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('admin/auth/forgot.text') }}
                </p>
            </div>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <div class="mt-5">
                <form method="post" action="{{ route('panel.forgot.password.store') }}">
                    @csrf
                    <div class="grid gap-y-4">
                        <div>
                            <x-label for="email" class="block text-sm mb-2 dark:text-white">
                                {{ __('admin/auth/forgot.form.email') }}
                            </x-label>
                            <div class="relative">
                                <x-input type="email" id="email" name="email"
                                    class="peer py-2 px-4 ps-11 block w-full" :value="old('email')" required autofocus
                                    autocomplete="email" />
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <svg class="flex-shrink-0 w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-mail-check">
                                        <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                        <path d="m16 19 2 2 4-4" />
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <x-submit
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">{{ __('admin/auth/forgot.form.submit') }}</x-submit>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-guest-layout>
