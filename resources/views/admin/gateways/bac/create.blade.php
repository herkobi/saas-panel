<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.settings.partials.navigation')
        </div>
        <div class="col-span-9">
            <h1 class="mb-8 text-xl	font-medium	border-b pb-2">{{ __('admin/gateways/bac.edit.page.title') }}</h1>
            <form action="{{ route('panel.gateways.bac.create.store') }}" class="form" method="post">
                @csrf
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="status" class="block font-medium text-gray-700 col-span-3" :value="__('admin/gateways/bac.form.status')" />
                    <div class="col-span-9">
                        <div id="status" class="flex justify-start">
                            @foreach (Status::values() as $key => $value)
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                        type="radio" name="status" id="active-status" value="{{ $key }}" />
                                    <x-label for="active-status"
                                        class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        :value="__('global.' . $value)" />
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="title" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.title')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-type">
                                <polyline points="4 7 4 4 20 4 20 7" />
                                <line x1="9" x2="15" y1="20" y2="20" />
                                <line x1="12" x2="12" y1="4" y2="20" />
                            </svg>
                        </div>
                        <x-input type="text" id="title" name="title"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/bac.form.title') }}" :value="old('title')" required
                            autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="currency_id" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.currency_id')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-start pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-text-cursor-input">
                                <path d="M5 4h1a3 3 0 0 1 3 3 3 3 0 0 1 3-3h1" />
                                <path d="M13 20h-1a3 3 0 0 1-3-3 3 3 0 0 1-3 3H5" />
                                <path d="M5 16H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h1" />
                                <path d="M13 8h7a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-7" />
                                <path d="M9 7v10" />
                            </svg>
                        </div>
                        <x-select class="ps-11 block w-full" name="currency_id" id="currency_id" :options="$currencies"
                            :selectedValue="null" />
                        <x-input-error :messages="$errors->get('currency_id')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="desc" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.desc')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-start pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-quote">
                                <path
                                    d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z" />
                                <path
                                    d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z" />
                            </svg>
                        </div>
                        <x-textarea id="desc" name="desc"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/bac.form.desc') }}" required
                            autocomplete="desc">{{ old('desc') }}</x-textarea>
                        <x-input-error :messages="$errors->get('desc')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="account_name" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.account_name')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-square-user">
                                <rect width="18" height="18" x="3" y="3" rx="2" />
                                <circle cx="12" cy="10" r="3" />
                                <path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" />
                            </svg>
                        </div>
                        <x-input type="text" id="account_name" name="account_name"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/bac.form.account_name') }}" :value="old('account_name')" required
                            autocomplete="account_name" />
                        <x-input-error :messages="$errors->get('account_name')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="account_bank" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.account_bank')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-landmark">
                                <line x1="3" x2="21" y1="22" y2="22" />
                                <line x1="6" x2="6" y1="18" y2="11" />
                                <line x1="10" x2="10" y1="18" y2="11" />
                                <line x1="14" x2="14" y1="18" y2="11" />
                                <line x1="18" x2="18" y1="18" y2="11" />
                                <polygon points="12 2 20 7 4 7" />
                            </svg>
                        </div>
                        <x-input type="text" id="account_bank" name="account_bank"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/bac.form.account_bank') }}" :value="old('account_bank')" required
                            autocomplete="account_bank" />
                        <x-input-error :messages="$errors->get('account_bank')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="account_number" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.account_number')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-hand-coins">
                                <path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17" />
                                <path
                                    d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9" />
                                <path d="m2 16 6 6" />
                                <circle cx="16" cy="9" r="2.9" />
                                <circle cx="6" cy="5" r="3" />
                            </svg>
                        </div>
                        <x-input type="text" id="account_number" name="account_number"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/bac.form.account_number') }}" :value="old('account_number')"
                            required autocomplete="account_number" />
                        <x-input-error :messages="$errors->get('account_number')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="account_iban" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.account_iban')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-file-digit">
                                <path d="M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <rect width="4" height="6" x="2" y="12" rx="2" />
                                <path d="M10 12h2v6" />
                                <path d="M10 18h4" />
                            </svg>
                        </div>
                        <x-input type="text" id="account_iban" name="account_iban"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/bac.form.account_iban') }}" :value="old('account_iban')" required
                            autocomplete="account_iban" />
                        <x-input-error :messages="$errors->get('account_iban')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="account_swift" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/bac.form.account_swift')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-mouse-pointer-click">
                                <path d="m9 9 5 12 1.8-5.2L21 14Z" />
                                <path d="M7.2 2.2 8 5.1" />
                                <path d="m5.1 8-2.9-.8" />
                                <path d="M14 4.1 12 6" />
                                <path d="m6 12-1.9 2" />
                            </svg>
                        </div>
                        <x-input type="text" id="account_swift" name="account_swift"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/bac.form.account_swift') }}" :value="old('account_swift')"
                            required autocomplete="account_swift" />
                        <x-input-error :messages="$errors->get('account_swift')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-4 grid gap-4 grid-cols-12">
                    <div class="col-start-4 col-span-9">
                        <div class="flex items-center justify-between">
                            <x-submit
                                class="border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                {{ __('admin/gateways/bac.form.submit') }}
                            </x-submit>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
