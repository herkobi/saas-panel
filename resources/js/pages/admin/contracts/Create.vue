<script setup lang="ts">
import AppLayout from '@/layouts/AdminLayout.vue';
import ContractForm from '@/pages/admin/contracts/ContractForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sözleşmeler',
        href: '/panel/contracts',
    },
    {
        title: 'Yeni Sözleşme',
        href: '/panel/contracts/create',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Yeni Sözleşme Ekle" />
        <div class="container mx-auto p-4">
            <ContractForm mode="create" />
        </div>
    </AppLayout>
</template>
