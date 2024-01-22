<div class="bg-white dark:bg-slate-900 max-w-[60%]">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
            {{ __('user/profile/profile.profile.form.title') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('user/profile/profile.profile.form.text') }}
        </p>
    </div>
    <div class="mb-4">
        <form id="send-verification" method="post" action="{{ route('app.verification.send') }}">
            @csrf
        </form>
    </div>
    <form method="post" action="{{ route('app.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
            <div class="sm:col-span-3">
                <x-label for="af-account-full-name"
                    class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    {{ __('user/profile/profile.profile.form.full.name') }}
                </x-label>
            </div>
            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <input id="af-account-full-name" type="text" name="name"
                        class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        placeholder="{{ __('user/profile/profile.profile.form.name') }}"
                        value="{{ old('name', $user->name) }}" required autocomplete="name">
                    <input type="text" name="surname"
                        class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        placeholder="{{ __('user/profile/profile.profile.form.surname') }}"
                        value="{{ old('name', $user->surname) }}" required autocomplete="surname">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                <x-input-error class="mt-2" :messages="$errors->get('surname')" />
            </div>
            <div class="sm:col-span-3">
                <x-label for="af-account-email" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    {{ __('user/profile/profile.profile.form.email') }}
                </x-label>
            </div>
            <div class="sm:col-span-9">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                        <svg class="flex-shrink-0 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-plus">
                            <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                            <path d="M19 16v6" />
                            <path d="M16 19h6" />
                        </svg>
                    </div>
                    <input type="email" id="af-account-email" name="email"
                        class="py-2 px-4 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        placeholder="{{ __('user/profile/profile.profile.form.email') }}"
                        value="{{ old('email', $user->email) }}" required autocomplete="email">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-3 mb-3">
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="mt-5 flex justify-end gap-x-2">
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
            <x-submit
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                {{ __('user/profile/profile.profile.form.submit') }}
            </x-submit>
        </div>
    </form>
</div>
