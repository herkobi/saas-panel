<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    link: {
        type: Object,
        required: true
    }
});

const copied = ref(false);

// Güvenli URL oluşturmak için computed property
const shortUrl = computed(() => {
    if (typeof window === 'undefined' || !window.location || !props.link || !props.link.alias) return '';
    return `${window.location.origin}/${props.link.alias}`;
});

// Detay sayfasına yönlendirme için fonksiyon
const goToLinkDetails = (id) => {
    if (typeof window === 'undefined' || !id) return;
    window.location.href = `/app/link/${id}/detail`;
};

const copyToClipboard = (text) => {
    if (typeof navigator === 'undefined' || !navigator.clipboard) return;

    navigator.clipboard.writeText(text).then(() => {
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    });
};
</script>

<template>
    <Head title="Link Önizleme" />
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC] lg:justify-center lg:p-8">
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-16"
                >
                    <h1 class="mb-3 text-lg font-medium">Link Önizleme</h1>
                    <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        Bu bir önizleme sayfasıdır. Kısaltılmış link hakkında güvenli bilgiler görüntülenmektedir.
                    </p>

                    <div class="mb-6 rounded-md bg-blue-50 p-3 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                        Bu sayfadaki bilgiler ışığında linke güvenip güvenmeyeceğinize karar verebilirsiniz.
                    </div>

                    <div class="mb-4">
                        <h2 class="mb-2 text-sm font-medium">Kısaltılmış Link:</h2>
                        <div class="flex">
                            <input
                                type="text"
                                :value="shortUrl"
                                readonly
                                class="flex-1 rounded-l-sm border border-gray-300 p-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                            />
                            <button
                                @click="copyToClipboard(shortUrl)"
                                class="rounded-r-sm border border-gray-300 bg-gray-100 px-2 dark:border-gray-700 dark:bg-gray-700"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="copied" class="mt-1 text-xs text-green-600">Link kopyalandı!</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="mb-2 text-sm font-medium">Hedef URL:</h2>
                        <p class="break-all rounded-sm border border-gray-200 p-2 text-xs text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            {{ link?.url || 'URL bilgisi bulunamadı' }}
                        </p>
                    </div>

                    <div v-if="link?.title || link?.description" class="mb-4">
                        <h2 class="mb-2 text-sm font-medium">Sayfa Bilgisi:</h2>
                        <div class="rounded-sm border border-gray-200 p-2 dark:border-gray-700 dark:bg-gray-800">
                            <p v-if="link?.title" class="text-sm font-medium">{{ link.title }}</p>
                            <p v-if="link?.description" class="text-xs text-gray-600 dark:text-gray-300">{{ link.description }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <Link href="/" class="inline-block rounded-sm border border-gray-300 px-5 py-1.5 text-sm leading-normal hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                            Ana Sayfaya Dön
                        </Link>
                        <button
                            @click="goToLinkDetails(link.id)"
                            class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                        >
                            Link Bilgilerine Dön
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
