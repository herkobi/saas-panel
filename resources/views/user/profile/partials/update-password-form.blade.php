<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('app.password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-label for="update_password_current_password" :value="__('Current Password')" />
            <x-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-label for="update_password_password" :value="__('New Password')" />
            <x-input id="update_password_password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-submit>{{ __('Save') }}</x-submit>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<div class="bg-white dark:bg-slate-900 max-w-[60%]">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
            {{ __('user/profile/profile.password.form.title') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('user/profile/profile.password.form.text') }}
        </p>
    </div>
    <form>
        <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
            <div class="sm:col-span-3">
                <label for="af-account-password" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Password
                </label>
            </div>
            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-account-password" type="text"
                        class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        placeholder="Enter current password">
                    <input type="text"
                        class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        placeholder="Enter new password">
                </div>
            </div>
        </div>
        <div class="mt-5 flex justify-end gap-x-2">
            <button type="button"
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Cancel
            </button>
            <button type="button"
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Save changes
            </button>
        </div>
    </form>
</div>
