<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    link: {
        type: Object,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
});

onMounted(() => {
    // 3 saniye sonra yönlendirme
    setTimeout(() => {
        window.location.href = props.url;
    }, 3000);
});

// Piksel kodlarını HTML'e güvenli bir şekilde ekle
const renderPixelCode = (pixel) => {
    if (pixel.type === 'facebook') {
        return `
            <!-- Facebook Pixel Code -->
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '${pixel.value}');
            fbq('track', 'PageView');
        `;
    } else if (pixel.type === 'google') {
        return `
            <!-- Google Tag Manager -->
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','${pixel.value}');
        `;
    } else {
        return pixel.value; // Custom piksel
    }
};
</script>

<template>
    <Head title="Yönlendiriliyor..." />
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC] lg:justify-center lg:p-8"
    >
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-16"
                >
                    <h1 class="mb-3 text-lg font-medium">Yönlendiriliyor...</h1>
                    <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">Lütfen bekleyin, hedef siteye yönlendiriliyorsunuz.</p>

                    <div class="mt-6 flex justify-center">
                        <a
                            :href="url"
                            class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                        >
                            Yönlendirme çalışmazsa tıklayın
                        </a>
                    </div>
                </div>
                <div
                    class="relative -mb-px aspect-[335/376] w-full shrink-0 overflow-hidden rounded-t-lg bg-[#fff2f2] dark:bg-[#1D0002] lg:-ml-px lg:mb-0 lg:aspect-auto lg:w-[438px] lg:rounded-r-lg lg:rounded-t-none"
                >
                    <!-- Sağ taraf görsel bölümü -->
                    <div class="flex h-full items-center justify-center">
                        <div class="flex h-24 w-24 items-center justify-center">
                            <div class="h-12 w-12 animate-spin rounded-full border-4 border-t-4 border-blue-500 border-t-transparent"></div>
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 rounded-t-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:overflow-hidden lg:rounded-r-lg lg:rounded-t-none"
                    />
                </div>
            </main>
        </div>

        <!-- Piksel kodları -->
        <div class="hidden">
            <div v-for="pixel in link.pixels" :key="pixel.id" v-html="renderPixelCode(pixel)"></div>
        </div>
    </div>
</template>
