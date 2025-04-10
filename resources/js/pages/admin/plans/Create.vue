<script setup lang="ts">
import AppLayout from '@/layouts/AdminLayout.vue';
import PlanForm from '@/pages/admin/plans/PlanForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Feature {
    id: number;
    title: string;
    slug: string;
    description: string | null;
}

interface Currency {
    name: string;
    symbol: string;
    position: string;
    thousands_separator: string;
    decimal_separator: string;
    decimals: number;
    iso_code: string;
}

interface Country {
    name: string;
    code: string;
}

interface TaxRate {
    name: string;
    code: string;
    rate: number;
}

const props = defineProps<{
    features: Feature[];
    currencies: Currency[];
    countries: Country[];
    taxRates: TaxRate[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Planlar',
        href: '/panel/plans',
    },
    {
        title: 'Yeni Plan',
        href: '/panel/plans/create',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Yeni Plan Ekle" />
        <div class="container mx-auto p-4">
            <PlanForm mode="create" :features="features" :currencies="currencies" :countries="countries" :taxRates="taxRates" />
        </div>
    </AppLayout>
</template>
