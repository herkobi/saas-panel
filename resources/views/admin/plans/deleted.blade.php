<x-admin-app-layout>
    <div class="grid grid-cols-12 gap-7">
        <div class="col-span-3">
            @include('admin.plans.partials.navigation')
        </div>
        <div class="col-span-9">
            @if (is_null($plans))
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div
                                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                            {{ __('admin/plans/plans.deleted.title') }}
                                        </h2>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('admin/plans/plans.deleted.text') }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="max-w-sm w-full min-h-[400px] flex flex-col justify-center mx-auto px-6 py-4">
                                    <div
                                        class="flex justify-center items-center w-[46px] h-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
                                        <svg class="flex-shrink-0 w-6 h-6 text-gray-600 dark:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                                            <path
                                                d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                                        </svg>
                                    </div>
                                    <h2 class="mt-5 font-semibold text-gray-800 dark:text-white">
                                        No draft test invoices
                                    </h2>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        Draft an invoice and send it to a customer.
                                    </p>
                                    <div class="mt-5 grid sm:flex gap-2">
                                        <button type="button"
                                            class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                                <path d="M12 5v14" />
                                            </svg>
                                            Create a new invoice
                                        </button>
                                        <button type="button"
                                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                            Use a Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div
                            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                            <div
                                class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-gray-700">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                        {{ __('admin/plans/plans.page.title') }}
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('admin/plans/plans.page.text') }}
                                    </p>
                                </div>
                                <div>
                                    <div class="inline-flex gap-x-2">
                                        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                            href="{{ route('panel.plans.plan.create') }}">
                                            <svg class="flex-shrink-0 w-3 h-3" xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                </path>
                                            </svg>
                                            {{ __('admin/plans/plans.add.button') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-slate-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    {{ __('admin/plans/plans.table.status') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    {{ __('admin/plans/plans.table.title') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    {{ __('admin/plans/plans.table.usage') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    {{ __('admin/plans/plans.table.price') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($plans as $plan)
                                        <tr>
                                            <td class="h-px w-px whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    @if ($plan->status->value == 1)
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg class="w-2.5 h-2.5" xmlns="http://www.w3.org/2000/svg"
                                                                width="16" height="16" fill="currentColor"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z">
                                                                </path>
                                                            </svg>
                                                            {{ Status::title($plan->status) }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                            <svg class="w-2.5 h-2.5" xmlns="http://www.w3.org/2000/svg"
                                                                width="16" height="16" fill="currentColor"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                                                </path>
                                                            </svg>
                                                            {{ Status::title($plan->status) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="h-px w-72 whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <span
                                                        class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $plan->title }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-72 whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    @if (is_null($plan->periodicity_type))
                                                        <span
                                                            class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('admin/plans/plans.table.free') }}</span>
                                                    @else
                                                        <span
                                                            class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                            {{ __('admin/plans/plans.table.period', [
                                                                'interval' => $plan->periodicity,
                                                                'unit' => __('global.period_ek_' . $plan->periodicity_type->name),
                                                            ]) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="h-px w-72 whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <span
                                                        class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $plan->currency->symbol . '' . $plan->price }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px whitespace-nowrap">
                                                <div class="flex items-start justfiy-between px-6 py-1.5 gap-2">
                                                    <a class="cursor-pointer inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                        data-hs-overlay="#restore-modal">
                                                        {{ __('admin/plans/plans.table.restore') }}
                                                    </a>
                                                    <a class="cursor-pointer inline-flex items-center gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                        data-hs-overlay="#destroy-modal">
                                                        {{ __('admin/plans/plans.table.destroy') }}
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div
                                class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">{{ count($plans) }}</span>
                                        {{ __('global.results') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if (!is_null($plans))
        <div id="restore-modal"
            class="hs-overlay hs-overlay-backdrop-open:bg-blue-950/90 hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="false">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="relative flex flex-col bg-white border shadow-sm rounded-xl overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                    <div class="absolute top-2 end-2">
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-transparent dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#restore-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-10 overflow-y-auto">
                        <div class="flex gap-x-4 md:gap-x-7">
                            <span
                                class="flex-shrink-0 inline-flex justify-center items-center w-[46px] h-[46px] sm:w-[62px] sm:h-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                                <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                            </span>
                            <div class="grow">
                                <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                                    {{ __('admin/plans/plans.confirm.title') }}
                                </h3>
                                <p class="text-gray-500">
                                    {{ __('admin/plans/plans.confirm.text') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex justify-end items-center gap-x-2 py-3 px-4 bg-gray-50 border-t dark:bg-gray-800 dark:border-gray-700">
                        <x-button
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#confirm-modal">
                            {{ __('admin/plans/plans.confirm.cancel') }}
                        </x-button>
                        <form method="post" action="{{ route('panel.plans.plan.restore', $plan->id) }}">
                            @csrf
                            <x-submit
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-500 text-white hover:bg-red-600 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                href="#">
                                {{ __('admin/plans/plans.confirm.submit') }}
                            </x-submit>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="destroy-modal"
            class="hs-overlay hs-overlay-backdrop-open:bg-blue-950/90 hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="false">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="relative flex flex-col bg-white border shadow-sm rounded-xl overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                    <div class="absolute top-2 end-2">
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-transparent dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#destroy-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-10 overflow-y-auto">
                        <div class="flex gap-x-4 md:gap-x-7">
                            <span
                                class="flex-shrink-0 inline-flex justify-center items-center w-[46px] h-[46px] sm:w-[62px] sm:h-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                                <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                            </span>
                            <div class="grow">
                                <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                                    {{ __('admin/plans/plans.confirm.title') }}
                                </h3>
                                <p class="text-gray-500">
                                    {{ __('admin/plans/plans.confirm.text') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex justify-end items-center gap-x-2 py-3 px-4 bg-gray-50 border-t dark:bg-gray-800 dark:border-gray-700">
                        <x-button
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#confirm-modal">
                            {{ __('admin/plans/plans.confirm.cancel') }}
                        </x-button>
                        <form method="post" action="{{ route('panel.plans.plan.forcedelete', $plan->id) }}">
                            @csrf
                            <x-submit
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-500 text-white hover:bg-red-600 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                href="#">
                                {{ __('admin/plans/plans.confirm.submit') }}
                            </x-submit>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-admin-app-layout>
