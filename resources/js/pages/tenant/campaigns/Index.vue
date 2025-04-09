<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/TenantLayout.vue';
import CampaignLayout from '@/layouts/tenant/campaigns/Layout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

// Prop olarak gelen kampanyalar
const props = defineProps({
    campaigns: {
        type: Object,
        required: true,
    },
});

// Tüm kampanyalar için state
const allCampaigns = ref([...props.campaigns.data]);
const currentPage = ref(props.campaigns.meta.current_page);
const hasMore = ref(props.campaigns.meta.has_more);
const isLoading = ref(false);

// Daha fazla kampanya yükleme fonksiyonu
const loadMoreCampaigns = async () => {
    if (!hasMore.value || isLoading.value) return;

    isLoading.value = true;

    try {
        const nextPage = currentPage.value + 1;
        const response = await router.get(
            route('app.campaigns'),
            { page: nextPage, limit: props.campaigns.meta.per_page },
            {
                preserveState: true,
                only: ['campaigns'],
                onSuccess: (page) => {
                    const newCampaigns = page.props.campaigns.data;
                    allCampaigns.value = [...allCampaigns.value, ...newCampaigns];
                    currentPage.value = nextPage;
                    hasMore.value = page.props.campaigns.meta.has_more;
                },
            },
        );
    } catch (error) {
        console.error('Kampanyalar yüklenirken hata oluştu:', error);
    } finally {
        isLoading.value = false;
    }
};

// Sayfa sonuna gelindiğinde yükleme yapmak için Intersection Observer
const observer = ref(null);
const lastCampaignRef = ref(null);

onMounted(() => {
    observer.value = new IntersectionObserver(
        (entries) => {
            const entry = entries[0];
            if (entry.isIntersecting && hasMore.value && !isLoading.value) {
                loadMoreCampaigns();
            }
        },
        { threshold: 0.1 },
    );

    if (lastCampaignRef.value) {
        observer.value.observe(lastCampaignRef.value);
    }
});

// Son elemanı izlemek için ref fonksiyonu
const setLastCampaignRef = (el, index) => {
    if (index === allCampaigns.value.length - 1) {
        if (observer.value && lastCampaignRef.value) {
            observer.value.unobserve(lastCampaignRef.value);
        }
        lastCampaignRef.value = el;
        if (observer.value && el) {
            observer.value.observe(el);
        }
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Kampanyalar" />

        <CampaignLayout>
            <div class="space-y-6">
                <HeadingSmall title="Kampanyalar" description="Kayıtlı kampanya kısa linkleriniz" />

                <div v-if="allCampaigns.length" class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-4">
                    <div
                        v-for="(campaign, index) in allCampaigns"
                        :key="campaign.id"
                        :ref="(el) => setLastCampaignRef(el, index)"
                        class="overflow-hidden rounded-lg bg-white shadow-sm transition-shadow hover:shadow-md"
                    >
                        <Link :href="route('app.campaign.detail', campaign.id)" class="block text-gray-800 no-underline">
                            <!-- Kampanya resmi -->
                            <div class="relative aspect-video overflow-hidden rounded-t-lg">
                                <img v-if="campaign.image" :src="campaign.image" alt="Kampanya Görseli" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full w-full items-center justify-center bg-muted">
                                    <span class="text-muted-foreground">Görsel Yok</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="mb-2 line-clamp-2 font-medium">{{ campaign.title }}</h3>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div class="flex">
                                        <span class="mr-2 font-medium">Başlangıç:</span>
                                        <span>{{ new Date(campaign.start_date).toLocaleDateString('tr-TR') }}</span>
                                    </div>
                                    <div class="flex">
                                        <span class="mr-2 font-medium">Bitiş:</span>
                                        <span>{{ new Date(campaign.end_date).toLocaleDateString('tr-TR') }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs"
                                            :class="{
                                                'bg-green-100 text-green-800': campaign.status === 1,
                                                'bg-gray-100 text-gray-800': campaign.status === 2,
                                                'bg-yellow-100 text-yellow-800': campaign.status === 3,
                                            }"
                                        >
                                            {{ campaign.status === 1 ? 'Aktif' : campaign.status === 2 ? 'Arşiv' : 'Taslak' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Yükleniyor mesajı -->
                <div v-if="isLoading" class="mt-4 text-center">
                    <p class="text-gray-500">Kampanyalar yükleniyor...</p>
                </div>

                <!-- Manuel "Daha Fazla Yükle" butonu (opsiyonel) -->
                <div v-if="hasMore && !isLoading" class="mt-4 text-center">
                    <Button @click="loadMoreCampaigns" variant="outline" class="px-4 py-2"> Daha Fazla Göster </Button>
                </div>

                <div v-if="allCampaigns.length === 0 && !isLoading" class="rounded-lg bg-white p-8 text-center">
                    <p class="mb-4 text-gray-500">Henüz kampanya oluşturmadınız.</p>
                </div>
            </div>
        </CampaignLayout>
    </AppLayout>
</template>
