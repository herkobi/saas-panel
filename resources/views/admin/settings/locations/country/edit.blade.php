<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.settings.partials.navigation')
        </div>
        <div class="col-span-9">
            <h1 class="mb-8 text-xl	font-medium	border-b pb-2">
                {{ __('admin/settings/locations/country.edit.page.title') }}</h1>
            <form action="{{ route('panel.settings.locations.country.update', $country->id) }}" class="form"
                method="post">
                @csrf
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="status" class="block font-medium text-gray-700 col-span-3" :value="__('admin/settings/locations/country.form.status')" />
                    <div class="col-span-9">
                        <div id="status" class="flex justify-start">
                            @foreach (Status::values() as $key => $value)
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                        type="radio" name="status" id="active-status" value="{{ $key }}"
                                        {{ $country->status->value == $key ? 'checked' : '' }} />
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
                    <x-label for="country" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/settings/locations/country.form.country')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-type">
                                <polyline points="4 7 4 4 20 4 20 7" />
                                <line x1="9" x2="15" y1="20" y2="20" />
                                <line x1="12" x2="12" y1="4" y2="20" />
                            </svg>
                        </div>
                        <x-input type="text" id="country" name="country"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/settings/locations/country.form.country') }}" :value="$country->country ? $country->country : old('country')"
                            required autocomplete="country" />
                        <x-input-error :messages="$errors->get('country')" class="mt-2" />
                    </div>
                </div>
                <div class="mb-4 grid gap-4 grid-cols-12">
                    <x-label for="code" class="block font-medium text-sm text-gray-700 col-span-3"
                        :value="__('admin/settings/locations/country.form.code')" />
                    <div class="relative col-span-9">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 4v16"></path>
                                <path d="M17 4v16"></path>
                                <path d="M19 4H9.5a4.5 4.5 0 0 0 0 9H13"></path>
                            </svg>
                        </div>
                        <x-input type="text" id="code" name="code"
                            class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="{{ __('admin/settings/locations/country.form.code') }}" :value="$country->code ? $country->code : old('code')"
                            required autocomplete="code" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-4 grid gap-4 grid-cols-12">
                    <div class="col-start-4 col-span-9">
                        <div class="flex items-center justify-between">
                            <x-submit
                                class="border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                {{ __('admin/settings/locations/country.form.update') }}
                            </x-submit>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
