<script setup lang="ts">
import FlashMessageCatcher from '@/components/FlashMessageCatcher.vue';
import AppFooter from '@/components/AppFooter.vue';
import { Toaster } from '@/components/ui/sonner';
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
    <Toaster />
    <FlashMessageCatcher />
    <AppLayout :breadcrumbs="breadcrumbs" class="mb-12">
        <slot />
    </AppLayout>
    <AppFooter />
</template>
