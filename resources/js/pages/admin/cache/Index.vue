<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Önbellek Yönetimi',
        href: '/panel/cache',
    },
];

const confirmModalOpen = ref(false);
const selectedCache = ref({
    title: '',
    command: '',
    description: '',
});

const caches = [
    {
        id: 1,
        title: 'Uygulama Önbelleği',
        description: 'Uygulama tarafından kullanılan genel cache verilerini temizler, önbellekte tutulan tüm veri yapılarını sıfırlar.',
        command: 'cache:clear',
    },
    {
        id: 2,
        title: 'Rota Önbelleği',
        description: 'Rotaların daha hızlı yüklenmesi için önbelleğe alınan rota tanımlamalarını temizler.',
        command: 'route:clear',
    },
    {
        id: 3,
        title: 'Yapılandırma Önbelleği',
        description: 'Config dosyalarının önbelleğe alınan sürümünü temizler, böylece yapılandırmalar dinamik olarak yüklenir.',
        command: 'config:clear',
    },
    {
        id: 4,
        title: 'Görünüm Önbelleği',
        description: 'Derlenmiş Blade şablonlarının önbelleğe alınmış sürümlerini temizler, böylece şablonlar yeniden derlenir.',
        command: 'view:clear',
    },
    {
        id: 5,
        title: 'Derlenmiş Önbellek',
        description: 'Uygulamanın optimize edilmiş ve derlenmiş PHP dosyalarını temizler, performansla ilgili derlemeleri sıfırlar.',
        command: 'optimize:clear',
    },
    {
        id: 6,
        title: 'Etkinlik Önbelleği',
        description: 'Etkinlik ve event listener tanımlarını önbelleğe alan yapıyı temizler, event çözümlemesini sıfırlar.',
        command: 'event:clear',
    },
];

const openConfirmModal = (cache: any) => {
    selectedCache.value = cache;
    confirmModalOpen.value = true;
};

const clearCache = () => {
    router.post(
        route('panel.cache.clear'),
        { command: selectedCache.value.command },
        {
            onSuccess: () => {
                confirmModalOpen.value = false;
            },
        },
    );
};
</script>

<template>
    <Head title="Önbellek Yönetimi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-4">
            <div class="mb-6">
                <div class="mb-4">
                    <h1 class="text-lg font-medium tracking-tight">Önbellek Yönetimi</h1>
                    <p class="text-muted-foreground mt-0.5 text-sm">Sistem önbelleklerini yönetin</p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <Card v-for="cache in caches" :key="cache.id">
                        <CardHeader class="pt-3 pb-2">
                            <CardTitle class="text-sm font-medium">{{ cache.title }}</CardTitle>
                        </CardHeader>
                        <CardContent class="pb-3">
                            <p class="text-muted-foreground mb-3 text-sm">
                                {{ cache.description }}
                            </p>
                            <Button variant="destructive" size="sm" @click="openConfirmModal(cache)"> Temizle </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Onay Modalı -->
        <Dialog v-model:open="confirmModalOpen">
            <DialogContent class="sm:max-w-[475px]">
                <DialogHeader>
                    <DialogTitle>{{ selectedCache.title }} Temizle</DialogTitle>
                    <DialogDescription>Bu işlem geri alınamaz</DialogDescription>
                </DialogHeader>
                <div class="py-4">
                    <div class="mb-4 border-l-4 border-red-400 bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Uyarı!</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Bu işlem {{ selectedCache.title.toLowerCase() }} kalıcı olarak temizleyecektir.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="mb-2">{{ selectedCache.title }} temizlemek istediğinize emin misiniz?</p>
                    <div class="mb-3 flex items-center rounded bg-gray-50 p-2">
                        <span class="text-sm">{{ selectedCache.description }}</span>
                    </div>
                </div>
                <DialogFooter>
                    <div class="flex w-full justify-between">
                        <Button variant="outline" @click="confirmModalOpen = false">İptal</Button>
                        <Button variant="destructive" @click="clearCache">Evet, Temizle</Button>
                    </div>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
