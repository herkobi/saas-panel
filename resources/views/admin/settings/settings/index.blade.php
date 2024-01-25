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
                        <p class="font-medium text-lg">
                            {{ __('admin/settings/general.app.title') }}</p>
                        <p class="text-gray-600">{{ __('admin/settings/general.app.text') }}
                        </p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-6">
                            <div class="md:col-span-6">
                                <x-label for="title" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/settings/general.form.title')" />
                                <x-input type="text" name="title" id="title"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full"
                                    placeholder="{{ __('admin/settings/general.form.title') }}" :value="config('panel.title')" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                            <div class="md:col-span-6 mt-4">
                                <x-label for="slogan" class="block font-medium text-sm text-gray-700"
                                    :value="__('admin/settings/general.form.slogan')" />
                                <x-input type="text" name="slogan" id="slogan"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full"
                                    placeholder="{{ __('admin/settings/general.form.slogan') }}" :value="config('panel.slogan')" />
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
                                <x-submit>
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
