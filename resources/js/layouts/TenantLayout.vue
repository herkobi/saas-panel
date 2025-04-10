<script setup lang="ts">
import FlashMessageCatcher from '@/components/FlashMessageCatcher.vue';
import AppFooter from '@/components/tenant/AppFooter.vue';
import { ToastProvider } from '@/components/ui/toast';
import AppLayout from '@/layouts/tenant/app/AppHeaderLayout.vue';
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
    <AppLayout :breadcrumbs="breadcrumbs" class="mb-12">
        <slot />
    </AppLayout>
    <AppFooter />
</template>
