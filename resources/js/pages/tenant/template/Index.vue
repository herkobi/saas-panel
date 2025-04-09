<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';

import AppLayout from '@/layouts/TenantLayout.vue';
import CampaignLayout from '@/layouts/tenant/campaigns/Layout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Kampanya Sayfası" />

        <CampaignLayout>
            <div class="space-y-6">
                <HeadingSmall title="Kampanya Sayfası" description="Kampanya sayfası görünümü ve ayarları" />
            </div>
        </CampaignLayout>
    </AppLayout>
</template>
