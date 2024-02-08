<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.plans.partials.navigation')
        </div>
        <div class="col-span-9">
            <h1 class="mb-8 text-xl	font-medium	border-b pb-2">{{ __('admin/plans/plans.create.page.title') }}</h1>
            <form action="{{ route('panel.plans.plan.create.store') }}" class="form" method="post">
                @csrf
                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                    <div class="text-gray-900 mb-4">
                        <p class="font-medium text-lg">{{ __('admin/plans/plans.plan.title') }}</p>
                        <p class="text-sm text-gray-600">{{ __('admin/plans/plans.plan.text') }}</p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="relative mb-4">
                            <x-label for="status" class="block font-medium text-gray-700 col-span-3"
                                :value="__('admin/plans/plans.form.status')" />
                            <div class="col-span-9">
                                <div id="status" class="flex justify-start">
                                    @foreach (Status::values() as $key => $value)
                                        <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                            <input
                                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                                type="radio" name="status" id="active-status"
                                                value="{{ $key }}" />
                                            <x-label for="active-status"
                                                class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                                :value="__('global.' . $value)" />
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                        <div class="relative mb-4">
                            <x-label for="title" class="block font-medium text-sm text-gray-700" :value="__('admin/plans/plans.form.title')" />
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
                                    placeholder="{{ __('admin/plans/plans.form.title') }}" :value="old('title')" required
                                    autocomplete="title" />
                            </div>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-12 mb-4">
                            <div class="md:col-span-6">
                                <x-label for="price" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/plans/plans.form.price')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-circle-dollar-sign">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                            <path d="M12 18V6" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="price" name="price"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/plans/plans.form.price') }}" :value="old('price')"
                                        required autocomplete="price" />
                                </div>
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6">
                                <x-label for="currency_id" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/plans/plans.form.currency_id')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-receipt-text">
                                            <path
                                                d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                                            <path d="M14 8H8" />
                                            <path d="M16 12H8" />
                                            <path d="M13 16H8" />
                                        </svg>
                                    </div>
                                    <x-select class="ps-11 block w-full" name="currency_id" id="currency_id"
                                        :options="$currencies" :selectedValue="null" />
                                </div>
                                <x-input-error :messages="$errors->get('currency_id')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-12 mb-4">
                            <div class="md:col-span-6">
                                <x-label for="periodicity" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/plans/plans.form.periodicity')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-repeat-2">
                                            <path d="m2 9 3-3 3 3" />
                                            <path d="M13 18H7a2 2 0 0 1-2-2V6" />
                                            <path d="m22 15-3 3-3-3" />
                                            <path d="M11 6h6a2 2 0 0 1 2 2v10" />
                                        </svg>
                                    </div>
                                    <x-input type="number" id="periodicity" name="periodicity" min="0"
                                        step="1"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/plans/plans.form.periodicity') }}" :value="old('periodicity')"
                                        required autocomplete="periodicity" />
                                </div>
                                <x-input-error :messages="$errors->get('periodicity')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6 mb-4">
                                <x-label for="periodicity_type" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/plans/plans.form.periodicity_type')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-gantt-chart">
                                            <path d="M8 6h10" />
                                            <path d="M6 12h9" />
                                            <path d="M11 18h7" />
                                        </svg>
                                    </div>
                                    <select
                                        class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 ps-11"
                                        name="periodicity_type" id="periodicity_type">
                                        <option selected>{{ __('global.selected') }}
                                        </option>
                                        @foreach (Period::values() as $key => $value)
                                            <option value="{{ $value }}">
                                                {{ __('global.period_' . $value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('periodicity_type')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6">
                                <x-label for="grace_days" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/plans/plans.form.grace_days')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-calendar-plus-2">
                                            <path d="M8 2v4" />
                                            <path d="M16 2v4" />
                                            <rect width="18" height="18" x="3" y="4" rx="2" />
                                            <path d="M3 10h18" />
                                            <path d="M10 16h4" />
                                            <path d="M12 14v4" />
                                        </svg>
                                    </div>
                                    <x-input type="number" id="grace_days" name="grace_days" min="0"
                                        step="1"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/plans/plans.form.grace_days') }}" :value="old('grace_days')"
                                        required autocomplete="grace_days" />
                                </div>
                                <x-input-error :messages="$errors->get('grace_days')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 gap-y-2 text-sm grid-cols-3">
                    <div class="relative md:col-span-1 md:col-start-2 mt-6">
                        <x-submit
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            {{ __('admin/plans/plans.form.submit') }}
                        </x-submit>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
