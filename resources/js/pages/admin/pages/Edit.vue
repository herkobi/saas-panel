<script setup lang="ts">
import AppLayout from '@/layouts/AdminLayout.vue';
import PageForm from '@/pages/admin/pages/PageForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Page {
    id: number;
    title: string;
    slug: string;
    summary: string | null;
    content: string | null;
    status: boolean;
}

const props = defineProps<{
    page: Page;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sayfalar',
        href: '/panel/pages',
    },
    {
        title: `${props.page.title} Düzenle`,
        href: `/panel/pages/${props.page.id}/edit`,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="`${page.title} Düzenle`" />
        <div class="container mx-auto p-4">
            <PageForm mode="edit" :page="page" />
        </div>
    </AppLayout>
</template>
