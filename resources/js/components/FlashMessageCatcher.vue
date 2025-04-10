<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import { usePage } from '@inertiajs/vue3';
import { onMounted, watch } from 'vue';

const page = usePage();
const { toast } = Toaster();

// Flash mesajlarını izleyelim
watch(
    () => page.props.flash,
    (flash) => {
        if (flash.success) {
            toast({
                title: 'Başarılı',
                description: flash.success,
                variant: 'success',
                duration: 5000,
            });
        }

        if (flash.error) {
            toast({
                title: 'Hata',
                description: flash.error,
                variant: 'destructive',
                duration: 5000,
            });
        }

        if (flash.warning) {
            toast({
                title: 'Uyarı',
                description: flash.warning,
                duration: 5000,
            });
        }

        if (flash.info) {
            toast({
                title: 'Bilgi',
                description: flash.info,
                duration: 5000,
            });
        }
    },
    { immediate: true, deep: true },
);

// Sayfa yüklendiğinde de kontrol edelim
onMounted(() => {
    const flash = page.props.flash;
    if (flash?.success) {
        toast({
            title: 'Başarılı',
            description: flash.success,
            variant: 'success',
            duration: 5000,
        });
    }

    if (flash?.error) {
        toast({
            title: 'Hata',
            description: flash.error,
            variant: 'destructive',
            duration: 5000,
        });
    }

    if (flash?.warning) {
        toast({
            title: 'Uyarı',
            description: flash.warning,
            duration: 5000,
        });
    }

    if (flash?.info) {
        toast({
            title: 'Bilgi',
            description: flash.info,
            duration: 5000,
        });
    }
});
</script>

<template>
    <!-- Vue 3'te boş template için div kullanılabilir -->
    <div class="hidden"></div>
</template>
