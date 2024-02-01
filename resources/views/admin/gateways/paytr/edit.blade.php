<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.settings.partials.navigation')
        </div>
        <div class="col-span-9">
            <h1 class="mb-8 text-xl	font-medium	border-b pb-2">{{ __('admin/gateways/paytr.edit.page.title') }}</h1>
            <form action="{{ route('panel.gateways.paytr.update', $paytr->id) }}" class="form" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="status" class="block font-medium text-gray-700 col-span-3" :value="__('admin/gateways/paytr.form.status')" />
                    <div class="col-span-9">
                        <div id="status" class="flex justify-start">
                            @foreach (Status::values() as $key => $value)
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                        type="radio" name="status" id="active-status" value="{{ $key }}"
                                        {{ $paytr->status->value == $key ? 'checked' : '' }} />
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
                        :value="__('admin/gateways/paytr.form.title')" />
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
                            placeholder="{{ __('admin/gateways/paytr.form.title') }}" :value="$paytr->title ? $paytr->title : old('title')" required
                            autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="currency_id" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/paytr.form.currency_id')" />
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
                            :selectedValue="$paytr->currency_id" />
                        <x-input-error :messages="$errors->get('currency_id')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="desc" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/paytr.form.desc')" />
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
                            placeholder="{{ __('admin/gateways/paytr.form.desc') }}" required
                            autocomplete="desc">{{ $paytr->desc ? $paytr->desc : old('desc') }}</x-textarea>
                        <x-input-error :messages="$errors->get('desc')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="logo" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/paytr.form.logo')" />
                    <div class="relative col-span-9">
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <img id='preview_logo' class="object-cover w-[16rem]"
                                    src="{{ Storage::exists(config('panel.logo')) ? Storage::disk()->url(config('panel.logo')) : asset('herkobi.png') }}"
                                    alt="Herkobi Logo" />
                            </div>
                            <label for="logo" class="block">
                                <span class="sr-only">{{ __('admin/settings/general.form.logo') }}</span>
                                <input type="file" onchange="loadLogo(event)" name="logo" id="logo"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                            </label>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="merchant_id" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/paytr.form.merchant_id')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-pi-square">
                                <rect width="18" height="18" x="3" y="3" rx="2" />
                                <path d="M7 7h10" />
                                <path d="M10 7v10" />
                                <path d="M16 17a2 2 0 0 1-2-2V7" />
                            </svg>
                        </div>
                        <x-input type="text" id="merchant_id" name="merchant_id"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/paytr.form.merchant_id') }}" :value="$values['merchant_id']" required
                            autocomplete="merchant_id" />
                        <x-input-error :messages="$errors->get('merchant_id')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="merchant_key_and_salt" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/paytr.form.merchant_key_and_salt')" />
                    <div class="relative col-span-9">
                        <div class="grid gap-4 grid-cols-12">
                            <div class="col-span-6">
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-key-square">
                                            <path
                                                d="M12.4 2.7c.9-.9 2.5-.9 3.4 0l5.5 5.5c.9.9.9 2.5 0 3.4l-3.7 3.7c-.9.9-2.5.9-3.4 0L8.7 9.8c-.9-.9-.9-2.5 0-3.4Z" />
                                            <path d="m14 7 3 3" />
                                            <path d="M9.4 10.6 2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="merchant_key" name="merchant_key"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/gateways/paytr.form.merchant_key') }}"
                                        :value="$values['merchant_key']" required autocomplete="merchant_key" />
                                    <x-input-error :messages="$errors->get('merchant_key')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-span-6">
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-key">
                                            <circle cx="7.5" cy="15.5" r="5.5" />
                                            <path d="m21 2-9.6 9.6" />
                                            <path d="m15.5 7.5 3 3L22 7l-3-3" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="merchant_salt" name="merchant_salt"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/gateways/paytr.form.merchant_salt') }}"
                                        :value="$values['merchant_salt']" required autocomplete="merchant_salt" />
                                    <x-input-error :messages="$errors->get('merchant_salt')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="merchant_ok_url" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/paytr.form.merchant_ok_url')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-link-2">
                                <path d="M9 17H7A5 5 0 0 1 7 7h2" />
                                <path d="M15 7h2a5 5 0 1 1 0 10h-2" />
                                <line x1="8" x2="16" y1="12" y2="12" />
                            </svg>
                        </div>
                        <x-input type="text" id="merchant_ok_url" name="merchant_ok_url"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/paytr.form.merchant_ok_url') }}" :value="$values['merchant_ok_url']"
                            required autocomplete="merchant_ok_url" />
                        <x-input-error :messages="$errors->get('merchant_ok_url')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="merchant_fail_url" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/gateways/paytr.form.merchant_fail_url')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none p-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-link-2-off">
                                <path d="M9 17H7A5 5 0 0 1 7 7" />
                                <path d="M15 7h2a5 5 0 0 1 4 8" />
                                <line x1="8" x2="12" y1="12" y2="12" />
                                <line x1="2" x2="22" y1="2" y2="22" />
                            </svg>
                        </div>
                        <x-input type="text" id="merchant_fail_url" name="merchant_fail_url"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/gateways/paytr.form.merchant_fail_url') }}" :value="$values['merchant_fail_url']"
                            required autocomplete="merchant_fail_url" />
                        <x-input-error :messages="$errors->get('merchant_fail_url')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-4 grid gap-4 grid-cols-12">
                    <div class="col-start-4 col-span-9">
                        <div class="flex items-center justify-between">
                            <x-submit
                                class="border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                {{ __('admin/gateways/paytr.form.update') }}
                            </x-submit>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        var loadLogo = function(event) {
            var input = event.target;
            var file = input.files[0];
            var type = file.type;
            var output = document.getElementById('preview_logo');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
</x-admin-app-layout>
