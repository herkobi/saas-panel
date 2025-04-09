<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    link: {
        type: Object,
        required: true,
    },
});

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

function submitFormZero() {
    const form = document.getElementById('zero-form');
    if (form) {
        form.submit();
    }
}

function submitFormOne() {
    const form = document.getElementById('one-form');
    if (form) {
        form.submit();
    }
}
</script>


<template>
    <Head title="Piksel İzni" />
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC] lg:justify-center lg:p-8">
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-16"
                >
                    <h1 class="mb-3 text-lg font-medium">Çerezler ve İzleme</h1>
                    <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        Bu link izleme pikselleri içermektedir. Devam etmeden önce lütfen seçiminizi yapın.
                    </p>

                    <div class="mb-6 rounded-md bg-blue-50 p-3 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                        <p>Bu link, hedef siteye yönlendirilmeden önce bazı piksel takip kodları çalıştırmak istiyor. Bu piksel kodları:</p>
                        <ul class="mt-2 list-inside list-disc">
                            <li>Ziyaretiniz hakkında istatistiksel bilgiler toplar</li>
                            <li>Link oluşturan kişiye dönüşüm verisi sağlar</li>
                            <li>Pazarlama ve analiz amaçlı çerezler kullanabilir</li>
                        </ul>
                    </div>

                    <div class="mt-6 flex flex-col space-y-3 sm:flex-row sm:justify-between sm:space-x-3 sm:space-y-0">

                        <form  id="zero-form" :action="route('redirect.validate_consent', props.link.alias)" method="post" class="flex-1">
                            <input type="hidden" name="_token" :value="csrfToken" />
                            <input type="hidden" name="consent" value="-1" />
                            <button
                                type="button"
                                @click="submitFormZero"
                                class="w-full rounded-sm border border-gray-300 bg-white px-4 py-1.5 text-sm leading-normal text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                            >
                                Piksel takibi olmadan devam et
                            </button>
                        </form>

                        <form id="one-form" :action="route('redirect.validate_consent', props.link.alias)" method="post" class="flex-1">
                            <input type="hidden" name="_token" :value="csrfToken" />
                            <input type="hidden" name="consent" value="1" />
                            <button
                                type="button"
                                @click="submitFormOne"
                                class="w-full rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                            >
                                Piksel takibi ile devam et
                            </button>
                        </form>
                    </div>
                </div>
                <div
                    class="relative -mb-px aspect-[335/376] w-full shrink-0 overflow-hidden rounded-t-lg bg-[#fff2f2] dark:bg-[#1D0002] lg:-ml-px lg:mb-0 lg:aspect-auto lg:w-[438px] lg:rounded-r-lg lg:rounded-t-none"
                >
                    <!-- Sağ taraf görsel bölümü -->
                    <div class="flex h-full items-center justify-center">
                        <div class="text-6xl text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-24 w-24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
