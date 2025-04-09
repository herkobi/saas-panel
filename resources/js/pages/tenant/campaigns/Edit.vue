<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import CampaignForm from './CampaignForm.vue';

import AppLayout from '@/layouts/TenantLayout.vue';
import CampaignLayout from '@/layouts/tenant/campaigns/Layout.vue';

const props = defineProps({
    campaign: {
        type: Object,
        required: true,
    },
});

function handleSubmit(form) {
    form.post(route('app.campaign.update', props.campaign.id));
}
</script>

<template>
    <AppLayout>
        <Head title="Kampanya Düzenle" />

        <CampaignLayout>
            <div class="w-full space-y-6">
                <div class="flex w-full items-center justify-between">
                    <HeadingSmall title="Kampanya Düzenle" description="Kampanya bilgilerini bu form yardımıyla düzenleyebilirsiniz" />

                    <div class="flex gap-2">
                        <Link :href="route('app.campaigns')" class="inline-flex">
                            <Button class="h-7 px-2 text-xs" variant="outline">
                                <ArrowLeft class="h-3.5 w-3.5" />
                                <span>Geri Dön</span>
                            </Button>
                        </Link>
                        <Link :href="route('app.campaign.detail', campaign.id)" class="inline-flex">
                            <Button class="h-7 px-2 text-xs" variant="secondary">
                                <span>Kampanya Detayı</span>
                            </Button>
                        </Link>
                    </div>
                </div>

                <Separator class="my-4" />

                <h2 class="mb-6 text-xl font-semibold">Kampanya Düzenle: {{ campaign.title }}</h2>
                <CampaignForm :campaign="campaign" submit-label="Güncelle" :is-edit="true" @submit="handleSubmit" />
            </div>
        </CampaignLayout>
    </AppLayout>
</template>
