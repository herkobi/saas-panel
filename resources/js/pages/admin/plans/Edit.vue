<script setup lang="ts">
import AppLayout from '@/layouts/AdminLayout.vue';
import PlanForm from '@/pages/admin/plans/PlanForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface PlanFeature {
    id: number;
    feature_id: number;
    feature_title: string;
    access_type: 'access_only' | 'limited';
    limit_type: 'renewable' | 'cumulative' | null;
    limit_value: number | null;
    limit_reset_period: 'hourly' | 'daily' | 'weekly' | 'monthly' | 'yearly' | null;
    restore_on_delete: boolean;
}

interface Plan {
    id: number;
    name: string;
    description: string | null;
    is_featured: boolean;
    is_free: boolean;
    billing_period: 'monthly' | 'yearly' | 'both';
    country_code: string;
    currency_code: string;
    tax_rate_code: string;
    monthly_price: number | null;
    yearly_price: number | null;
    trial_days: number;
    grace_period_days: number;
    payment_timing: 'upfront' | 'later';
    status: boolean;
    sort_order: number | null;
    planFeatures: PlanFeature[];
}

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
    plan: Plan;
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
        title: `${props.plan.name} Düzenle`,
        href: `/panel/plans/${props.plan.id}/edit`,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="`${plan.name} Düzenle`" />
        <div class="container mx-auto p-4">
            <PlanForm mode="edit" :plan="plan" :features="features" :currencies="currencies" :countries="countries" :taxRates="taxRates" />
        </div>
    </AppLayout>
</template>
