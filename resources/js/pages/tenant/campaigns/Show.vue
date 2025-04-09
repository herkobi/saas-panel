<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/TenantLayout.vue';
import CampaignLayout from '@/layouts/tenant/campaigns/Layout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    campaign: {
        type: Object,
        required: true,
    },
});

const confirmDelete = ref(false);

function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('tr-TR');
}

function publishCampaign() {
    router.post(route('app.campaign.publish', props.campaign.id));
}

function archiveCampaign() {
    router.post(route('app.campaign.archive', props.campaign.id));
}

function duplicateCampaign() {
    router.post(route('app.campaign.duplicate', props.campaign.id));
}
</script>

<template>
    <AppLayout>
        <Head :title="campaign.title" />

        <CampaignLayout>
            <div class="w-full space-y-6">
                <!-- Üst Kısım: Geri Butonu, Başlık/Durum, Aksiyon Butonları -->
                <div class="flex items-start justify-between">
                    <!-- Sol taraf - Geri Butonu ve Başlık/Durum -->
                    <div class="flex items-start">
                        <!-- Geri butonu -->
                        <div class="mr-4 self-start pt-1">
                            <Link :href="route('app.campaigns')" class="inline-flex">
                                <Button class="h-9 w-9 p-0" variant="outline">
                                    <ArrowLeft class="h-4 w-4" />
                                </Button>
                            </Link>
                        </div>

                        <!-- Başlık ve durum kısmında Badge komponentini kullanalım -->
                        <div class="space-y-2">
                            <h2 class="text-2xl font-semibold">{{ campaign.title }}</h2>
                            <div class="flex items-center space-x-2">
                                <!-- Status'ü direkt kontrol ederek gösterelim -->
                                <Badge v-if="Number(campaign.status) === 1" class="text-bg-success pt-0"> Aktif </Badge>
                                <Badge v-else-if="Number(campaign.status) === 2" class="text-bg-secondary pt-0"> Arşiv </Badge>
                                <Badge v-else-if="Number(campaign.status) === 3" class="text-bg-warning pt-0"> Taslak </Badge>

                                <!-- Öne çıkan badge'i -->
                                <Badge v-if="campaign.is_featured" class="bg-blue-100 text-blue-800"> Öne Çıkan </Badge>
                            </div>
                        </div>
                    </div>

                    <!-- Sağ taraf - Aksiyon Butonları -->
                    <div class="flex space-x-2">
                        <!-- Durum bazlı butonlar - Number() ile dönüşüm ekledik -->
                        <Button v-if="Number(campaign.status) === 3" @click="publishCampaign" class="h-9 px-3 text-sm" variant="default">
                            Yayınla
                        </Button>

                        <Button v-if="Number(campaign.status) === 1" @click="archiveCampaign" class="h-9 px-3 text-sm" variant="secondary">
                            Arşivle
                        </Button>

                        <Button v-if="Number(campaign.status) === 2" @click="duplicateCampaign" class="h-9 px-3 text-sm" variant="outline">
                            Kopyasını Oluştur
                        </Button>

                        <Link
                            :href="route('app.campaign.edit', campaign.id)"
                            class="rounded-md bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-700"
                        >
                            Düzenle
                        </Link>

                        <!-- Sil butonu sadece taslak durumunda gösterilir -->
                        <button
                            v-if="Number(campaign.status) === 3"
                            @click="confirmDelete = true"
                            class="rounded-md bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-500"
                        >
                            Sil
                        </button>
                    </div>
                </div>

                <Separator class="my-4" />

                <!-- İki sütunlu ana içerik -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Sol sütun - Ana Bilgiler -->
                    <div class="space-y-6 md:col-span-2">
                        <!-- Kampanya Linki -->
                        <div v-if="campaign.external_link">
                            <h3 class="mb-2 text-base font-semibold text-gray-800">Kampanya Linki</h3>
                            <a :href="campaign.external_link" target="_blank" class="text-blue-600 hover:underline">
                                {{ campaign.external_link }}
                            </a>
                        </div>

                        <!-- Kampanya İçeriği -->
                        <div v-if="campaign.content">
                            <h3 class="mb-2 text-base font-semibold text-gray-800">Kampanya İçeriği</h3>
                            <div class="prose max-w-none" v-html="campaign.content"></div>
                        </div>

                        <!-- Kampanya Koşulları -->
                        <div v-if="campaign.terms">
                            <h3 class="mb-2 text-base font-semibold text-gray-800">Kampanya Koşulları</h3>
                            <div class="prose max-w-none" v-html="campaign.terms"></div>
                        </div>

                        <!-- SEO Bilgileri -->
                        <div v-if="campaign.meta_title || campaign.meta_description">
                            <h3 class="mb-2 text-base font-semibold text-gray-800">SEO Bilgileri</h3>
                            <div v-if="campaign.meta_title" class="mb-2">
                                <span class="text-sm font-medium text-gray-500">Başlık:</span>
                                <p>{{ campaign.meta_title }}</p>
                            </div>
                            <div v-if="campaign.meta_description">
                                <span class="text-sm font-medium text-gray-500">Açıklama:</span>
                                <p>{{ campaign.meta_description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sağ sütun - Yan Panel -->
                    <div class="space-y-6">
                        <!-- Kampanya Görseli -->
                        <div>
                            <img v-if="campaign.image" :src="campaign.image" :alt="campaign.title" class="h-auto w-full rounded-lg shadow-sm" />
                        </div>

                        <!-- Tarih Bilgileri -->
                        <div class="mt-4 space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Başlangıç Tarihi</h4>
                                <p class="text-base">{{ formatDate(campaign.start_date) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Bitiş Tarihi</h4>
                                <p class="text-base">{{ formatDate(campaign.end_date) }}</p>
                            </div>
                            <div v-if="campaign.apply_name">
                                <h4 class="text-sm font-medium text-gray-500">{{ campaign.apply_name }}</h4>
                                <p class="text-base">{{ formatDate(campaign.apply_date) }}</p>
                            </div>
                        </div>

                        <!-- Form Kullanımı Bilgisi -->
                        <div v-if="campaign.has_form" class="rounded-lg bg-blue-50 p-4">
                            <h4 class="font-medium text-blue-800">Form Bilgisi</h4>
                            <p class="text-sm text-blue-600">Bu kampanya için form kullanılmaktadır.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Silme Onay Modalı -->
            <div v-if="confirmDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
                    <h3 class="mb-4 text-lg font-medium">Kampanyayı Silmek İstediğinize Emin Misiniz?</h3>
                    <p class="mb-6 text-gray-600">Bu işlem geri alınamaz. Kampanya kalıcı olarak silinecektir.</p>
                    <div class="flex justify-end space-x-3">
                        <button
                            @click="confirmDelete = false"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50"
                        >
                            İptal
                        </button>
                        <Link
                            :href="route('app.campaign.destroy', campaign.id)"
                            method="delete"
                            as="button"
                            class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-500"
                        >
                            Evet, Sil
                        </Link>
                    </div>
                </div>
            </div>
        </CampaignLayout>
    </AppLayout>
</template>
