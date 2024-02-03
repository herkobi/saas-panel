<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.users.partials.navigation')
        </div>
        <div class="col-span-9">
            <h1 class="mb-8 text-xl	font-medium	border-b pb-2">{{ __('admin/users/users.edit.page.title') }}</h1>
            <form action="{{ route('panel.user.edit.update', $user->id) }}" class="form" method="post">
                @csrf
                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                    <div class="text-gray-900 mb-4">
                        <p class="font-medium text-lg">{{ __('admin/users/users.personal.title') }}</p>
                        <p class="text-sm text-gray-600">{{ __('admin/users/users.personal.text') }}</p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="relative mb-4">
                            <x-label for="status" class="block font-medium text-gray-700 col-span-3"
                                :value="__('admin/settings/currencies.form.status')" />
                            <div class="col-span-9">
                                <div id="status" class="flex justify-start">
                                    @foreach (AdminStatus::values() as $key => $value)
                                        <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                            <input
                                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                                type="radio" name="status" id="active-status"
                                                value="{{ $key }}"
                                                {{ $user->status->value == $key ? 'checked' : '' }} />
                                            <x-label for="active-status"
                                                class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                                :value="__('global.' . $value)" />
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-12 mb-4">
                            <div class="md:col-span-6">
                                <x-label for="name" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/users/users.form.name')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-square-user-round">
                                            <path d="M18 21a6 6 0 0 0-12 0" />
                                            <circle cx="12" cy="11" r="4" />
                                            <rect width="18" height="18" x="3" y="3" rx="2" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="name" name="name"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/users/users.form.name') }}" :value="$user->name ? $user->name : old('name')" required
                                        autocomplete="name" />
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6">
                                <x-label for="surname" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/users/users.form.surname')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-square-user">
                                            <rect width="18" height="18" x="3" y="3" rx="2" />
                                            <circle cx="12" cy="10" r="3" />
                                            <path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="surname" name="surname"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/users/users.form.surname') }}" :value="$user->surname ? $user->surname : old('surname')"
                                        required autocomplete="surname" />
                                </div>
                                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                            </div>
                        </div>
                        <div class="relative mb-4">
                            <x-label for="title" class="block font-medium text-sm text-gray-700" :value="__('admin/users/users.form.title')" />
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                    <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-type">
                                        <polyline points="4 7 4 4 20 4 20 7" />
                                        <line x1="9" x2="15" y1="20" y2="20" />
                                        <line x1="12" x2="12" y1="4" y2="20" />
                                    </svg>
                                </div>
                                <x-input type="text" id="title" name="title"
                                    class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                    placeholder="{{ __('admin/users/users.form.title') }}" :value="$user->title ? $user->title : old('title')" required
                                    autocomplete="title" />
                            </div>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 gap-y-2 text-sm grid-cols-3">
                    <div class="relative md:col-start-2 md:col-span-2">
                        <hr class="mt-6 mb-10">
                    </div>
                </div>
                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3 mb-4">
                    <div class="text-gray-900 mb-4">
                        <p class="font-medium text-lg">{{ __('admin/users/users.account.title') }}</p>
                        <p class="text-sm text-gray-600">{{ __('admin/users/users.account.text') }}</p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-12">
                            <div class="md:col-span-6">
                                <x-label for="email" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/users/users.form.email')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-mail-plus">
                                            <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8" />
                                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                            <path d="M19 16v6" />
                                            <path d="M16 19h6" />
                                        </svg>
                                    </div>
                                    <x-input type="email" id="email" name="email"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/users/users.form.email') }}" :value="$user->email ? $user->email : old('email')"
                                        required autocomplete="email" />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6">
                                <x-label for="username" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/users/users.form.username')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-check-check">
                                            <path d="M18 6 7 17l-5-5" />
                                            <path d="m22 10-7.5 7.5L13 16" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="username" name="username"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/users/users.form.username') }}" :value="$user->username ? $user->username : old('username')"
                                        required autocomplete="username" />
                                </div>
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 gap-y-2 text-sm grid-cols-3">
                    <div class="relative md:col-span-1 md:col-start-2 mt-6">
                        <x-submit
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            {{ __('admin/users/users.form.update') }}
                        </x-submit>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
