<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    link: {
        type: Object,
        required: true,
    },
});

const errors = ref('');
const formPassword = ref('');
const csrfToken = ref('');

const page = usePage();

onMounted(() => {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        csrfToken.value = metaToken.getAttribute('content') || '';
    } else {
        csrfToken.value = page.props.csrf_token || '';
    }
});

function submitForm() {
    const form = document.getElementById('password-form');
    if (form) {
        form.submit();
    }
}
</script>

<template>
    <Head title="Şifre Korumalı Link" />
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC] lg:justify-center lg:p-8"
    >
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-16"
                >
                    <h1 class="mb-3 text-lg font-medium">Şifre Korumalı Link</h1>
                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">Bu link şifre korumalıdır. Devam etmek için şifreyi giriniz.</p>

                    <!-- Geleneksel form kullanıyoruz, AJAX yerine -->
                    <form id="password-form" :action="route('redirect.validate_password', props.link.alias)" method="post">
                        <!-- CSRF token ekle -->
                        <input type="hidden" name="_token" :value="csrfToken" />

                        <div class="space-y-4">
                            <div>
                                <label for="password" class="mb-1 block text-sm font-medium">Şifre</label>
                                <input
                                    id="password"
                                    v-model="formPassword"
                                    name="password"
                                    type="password"
                                    class="w-full rounded-sm border border-gray-300 p-2 text-sm focus:border-black focus:outline-none dark:border-gray-700 dark:bg-gray-800"
                                    required
                                />
                                <div v-if="props.link.password_hint" class="mt-1 text-xs text-gray-500">İpucu: {{ props.link.password_hint }}</div>
                                <div v-if="errors" class="mt-1 text-xs text-red-500">
                                    {{ errors }}
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="submitForm"
                                class="w-full rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                            >
                                Devam Et
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="relative -mb-px aspect-[335/376] w-full shrink-0 overflow-hidden rounded-t-lg bg-[#fff2f2] dark:bg-[#1D0002] lg:-ml-px lg:mb-0 lg:aspect-auto lg:w-[438px] lg:rounded-r-lg lg:rounded-t-none"
                >
                    <!-- Sağ taraf görsel bölümü -->
                    <div class="flex h-full items-center justify-center">
                        <div class="text-6xl text-purple-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-24 w-24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 rounded-t-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:overflow-hidden lg:rounded-r-lg lg:rounded-t-none"
                    />
                </div>
            </main>
        </div>
    </div>
</template>
