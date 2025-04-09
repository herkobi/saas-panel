<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import CampaignForm from './CampaignForm.vue';

import AppLayout from '@/layouts/TenantLayout.vue';
import CampaignLayout from '@/layouts/tenant/campaigns/Layout.vue';

const props = defineProps({});

function handleSubmit(form) {
    form.post(route('app.campaign.store'));
}
</script>

<template>
    <AppLayout>
        <Head title="Kampanya Oluştur" />

        <CampaignLayout>
            <div class="w-full space-y-6">
                <div class="flex w-full items-center justify-between">
                    <HeadingSmall title="Kampanya Oluştur" description="Kampanya bilgilerini bu form yardımıyla oluşturabilirsiniz" />

                    <Link :href="route('app.campaigns')" class="inline-flex">
                        <Button class="h-7 px-2 text-xs" variant="outline">
                            <ArrowLeft class="mr-1.5 h-3.5 w-3.5" />
                            <span>Geri Dön</span>
                        </Button>
                    </Link>
                </div>

                <Separator class="my-4" />

                <CampaignForm submit-label="Kaydet" @submit="handleSubmit" />
            </div>
        </CampaignLayout>
    </AppLayout>
</template>
