<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.settings.partials.navigation')
        </div>
        <div class="col-span-9">
            <h1 class="mb-8 text-xl	font-medium	border-b pb-2">{{ __('admin/settings/general.page.title') }}</h1>
            <form action="{{ route('panel.settings.general.update') }}" class="form" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                    <div class="text-gray-900 mb-4">
                        <p class="font-medium text-lg">{{ __('admin/settings/general.app.title') }}</p>
                        <p class="text-sm text-gray-600">{{ __('admin/settings/general.app.text') }}</p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-6">
                            <div class="md:col-span-6">
                                <x-label for="title" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/settings/general.form.title')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-type">
                                            <polyline points="4 7 4 4 20 4 20 7" />
                                            <line x1="9" x2="15" y1="20" y2="20" />
                                            <line x1="12" x2="12" y1="4" y2="20" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="title" name="title"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/settings/general.form.title') }}" :value="config('panel.title')"
                                        required autocomplete="title" />
                                </div>
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6 mt-4">
                                <x-label for="slogan" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/settings/general.form.slogan')" />
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <svg class="flex-shrink-0 h-4 w-4 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-quote">
                                            <path
                                                d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z" />
                                            <path
                                                d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z" />
                                        </svg>
                                    </div>
                                    <x-input type="text" id="slogan" name="slogan"
                                        class="py-2 px-4 ps-11 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        placeholder="{{ __('admin/settings/general.form.slogan') }}" :value="config('panel.slogan')"
                                        required autocomplete="slogan" />
                                </div>
                                <x-input-error :messages="$errors->get('slogan')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6 mt-4">
                                <x-label for="logo" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/settings/general.form.logo')" />
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
                                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6 mt-4">
                                <x-label for="favicon" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/settings/general.form.favicon')" />
                                <div class="flex items-center space-x-6">
                                    <div class="shrink-0">
                                        <img id='preview_favicon' class="h-16 w-16 object-cover rounded-full"
                                            src="{{ Storage::exists(config('panel.logo')) ? Storage::disk()->url(config('panel.favicon')) : asset('herkobi-favicon.png') }}"
                                            alt="Herkobi Favicon" />
                                    </div>
                                    <label for="favicon" class="block">
                                        <span class="sr-only">{{ __('admin/settings/general.favicon') }}</span>
                                        <input type="file" onchange="loadFavicon(event)" name="favicon"
                                            id="favicon"
                                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('favicon')" class="mt-2" />
                            </div>
                            <div class="mt-4">
                                <x-submit
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                    {{ __('admin/settings/general.form.submit') }}
                                </x-submit>
                            </div>
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
        var loadFavicon = function(event) {
            var input = event.target;
            var file = input.files[0];
            var type = file.type;
            var output = document.getElementById('preview_favicon');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
</x-admin-app-layout>
