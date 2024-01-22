<x-user-app-layout>
    @include('user.profile.partials.update-profile-information-form')
    <div class="bg-white dark:bg-slate-900 max-w-[60%]">
        <hr class="mb-4 mt-4">
    </div>
    @include('user.profile.partials.update-password-form')
    <div class="bg-white dark:bg-slate-900 max-w-[60%]">
        <hr class="mb-4 mt-4">
    </div>
    @include('user.profile.partials.delete-user-form')
</x-user-app-layout>
