<script setup lang="ts">
import FlashMessageCatcher from '@/components/FlashMessageCatcher.vue';
import { Toaster } from '@/components/ui/sonner';
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
    <Toaster />
    <FlashMessageCatcher />
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
