<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/tenant/AppearanceTabs.vue';
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/TenantLayout.vue';
import SettingsLayout from '@/layouts/tenant/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Görünüm',
        href: '/app/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Görünüm Ayarları" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Görünüm Ayarları" description="Genel görünümü özelleştirin" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
