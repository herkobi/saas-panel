<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.settings.partials.navigation')
        </div>
        <div class="col-span-9">
            <h1 class="mb-8 text-xl	font-medium	border-b pb-2">{{ __('admin/settings/payments.create.page.title') }}</h1>
        </div>
    </div>
</x-admin-app-layout>
