<script setup lang="ts">
import FlashMessageCatcher from '@/components/FlashMessageCatcher.vue';
import { ToastProvider } from '@/components/ui/toast';
import AppLayout from '@/layouts/admin/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <ToastProvider />
    <FlashMessageCatcher />
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
