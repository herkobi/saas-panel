<script setup lang="ts">
import AppContent from '@/components/tenant/AppContent.vue';
import AppShell from '@/components/tenant/AppShell.vue';
import AppSidebar from '@/components/tenant/AppSidebar.vue';
import AppSidebarHeader from '@/components/tenant/AppSidebarHeader.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>
