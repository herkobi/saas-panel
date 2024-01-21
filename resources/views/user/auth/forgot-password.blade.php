<x-user-guest-layout>
    <div class="relative h-[100vh] overflow-hidden">
        <div class="mx-auto max-w-screen-md py-12 px-4 sm:px-6 md:max-w-screen-xl md:px-8">
            <div class="md:pe-8 md:w-1/2 xl:pe-0 xl:w-5/12">
                <img src="{{ asset('herkobi.png') }}" alt="Herkobi" class="h-auto max-w-[45%] mb-12">
                <h1
                    class="text-xl text-gray-800 font-bold md:text-4xl md:leading-tight lg:text-4xl lg:leading-tight dark:text-gray-200">
                    {!! __('user/auth/forgot.title') !!}</h1>
                <p class="mt-3 text-base text-gray-500 mb-6">
                    {{ __('user/auth/forgot.text') }}
                </p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form accept="{{ route('app.forgot.password.store') }}" method="POST">
                    @csrf
                    <div class="grid gap-y-4">
                        <div>
                            <x-label for="email" class="block text-sm mb-2 dark:text-white"
                                :value="__('user/auth/forgot.form.email')"></x-label>
                            <div class="relative">
                                <x-input type="email" id="email" name="email" :value="old('email')" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <x-submit
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">{{ __('user/auth/forgot.form.submit') }}</x-submit>
                    </div>
                </form>
                <a href="{{ route('app.login') }}"
                    class="w-full mt-3 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">{{ __('user/auth/forgot.login') }}</a>
            </div>
        </div>
        <div
            class="hidden md:block md:absolute md:top-0 md:start-1/2 md:end-0 h-full bg-[url('https://images.unsplash.com/photo-1606868306217-dbf5046868d2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1981&q=80')] bg-no-repeat bg-center bg-cover">
        </div>
    </div>
</x-user-guest-layout>
