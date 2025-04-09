<script setup lang="ts">
import AppLayout from '@/layouts/AdminLayout.vue';
import PageForm from '@/pages/admin/pages/PageForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sayfalar',
        href: '/panel/pages',
    },
    {
        title: 'Yeni Sayfa',
        href: '/panel/pages/create',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Yeni Sayfa Ekle" />
        <div class="container mx-auto p-4">
            <PageForm mode="create" />
        </div>
    </AppLayout>
</template>
