<script setup lang="ts">
import AppLayout from '@/layouts/AdminLayout.vue';
import ContractForm from '@/pages/admin/contracts/ContractForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Contract {
    id: number;
    title: string;
    slug: string;
    date: string;
    content: string;
    status: boolean;
}

const props = defineProps<{
    contract: Contract;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sözleşmeler',
        href: '/panel/contracts',
    },
    {
        title: `${props.contract.title} Düzenle`,
        href: `/panel/contracts/${props.contract.id}/edit`,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="`${contract.title} Düzenle`" />
        <div class="container mx-auto p-4">
            <ContractForm mode="edit" :contract="contract" />
        </div>
    </AppLayout>
</template>
