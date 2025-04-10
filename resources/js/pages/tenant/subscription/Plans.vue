<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/Layout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { computed } from 'vue';

interface Plan {
    id: number;
    name: string;
    description: string;
    is_featured: boolean;
    is_free: boolean;
    billing_period: string;
    monthly_price: number;
    yearly_price: number;
    trial_days: number;
    formatted_monthly_price: string;
    formatted_yearly_price: string;
    features: {
        id: number;
        feature_name: string;
        feature_description: string;
        access_type: string;
        limit_type: string;
        limit_value: number | null;
        limit_reset_period: string | null;
        formatted_limit: string;
    }[];
}

interface ActiveSubscription {
    id: number;
    plan_id: number;
    plan_name: string;
    billing_period: string;
    price: number;
    trial_ends_at: string | null;
    ends_at: string | null;
    next_billing_at: string | null;
    status: number;
    status_label: string;
    on_trial: boolean;
    is_active: boolean;
}

interface Props {
    plans: Plan[];
    activeSubscription: ActiveSubscription | null;
}

const props = defineProps<Props>();

// Planları gruplara ayır
const monthlyPlans = computed(() => props.plans.filter((plan) => plan.billing_period === 'monthly' || plan.billing_period === 'both'));
const yearlyPlans = computed(() => props.plans.filter((plan) => plan.billing_period === 'yearly' || plan.billing_period === 'both'));
const bothPlans = computed(() => props.plans.filter((plan) => plan.billing_period === 'both'));

// Plan seçme işlemi
const handlePlanSelection = (planId: number, billingPeriod: string) => {
    // Aktif abonelik varsa plan değiştirme seçeneklerine, yoksa checkout sayfasına git
    if (props.activeSubscription) {
        router.visit(route('app.subscription.change.options', { plan: planId, billing_period: billingPeriod }));
    } else {
        router.visit(route('app.subscription.checkout', { plan: planId, billing_period: billingPeriod }));
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Abonelik Planları" />

        <SubscriptionLayout>
            <div class="space-y-8">
                <HeadingSmall title="Abonelik Planları" description="Size uygun planı seçin ve hemen kullanmaya başlayın" />

                <!-- Aylık Planlar -->
                <div v-if="monthlyPlans.length > 0" class="space-y-4">
                    <h3 class="text-lg font-medium">Aylık Planlar</h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <Card
                            v-for="plan in monthlyPlans"
                            :key="plan.id"
                            :class="{ 'border-primary': plan.is_featured, 'ring-1 ring-primary': plan.is_featured }"
                        >
                            <CardHeader>
                                <CardTitle>{{ plan.name }}</CardTitle>
                                <CardDescription>{{ plan.description }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="mb-4 text-center">
                                    <div class="text-3xl font-bold">
                                        {{ plan.formatted_monthly_price }}
                                    </div>
                                    <div class="text-sm text-muted-foreground">aylık</div>
                                    <div v-if="plan.trial_days > 0" class="mt-1 text-sm">{{ plan.trial_days }} gün ücretsiz deneme</div>
                                </div>

                                <ul class="space-y-2">
                                    <li v-for="feature in plan.features" :key="feature.id" class="flex items-start">
                                        <Check class="mr-2 h-3.5 w-3.5 text-green-500" />
                                        <span>
                                            {{ feature.feature_name }}
                                            <span v-if="feature.formatted_limit !== 'Erişim'" class="ml-1 text-sm font-medium"
                                                >({{ feature.formatted_limit }})</span
                                            >
                                        </span>
                                    </li>
                                </ul>
                            </CardContent>
                            <CardFooter>
                                <Button
                                    v-if="!activeSubscription || activeSubscription.plan_id !== plan.id"
                                    class="w-full"
                                    as="a"
                                    @click="handlePlanSelection(plan.id, 'monthly')"
                                >
                                    {{ activeSubscription ? 'Plana Geç' : 'Seç' }}
                                </Button>
                                <Button v-else class="w-full" variant="outline" disabled> Mevcut Plan </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </div>

                <!-- Yıllık Planlar -->
                <div v-if="yearlyPlans.length > 0" class="space-y-4">
                    <h3 class="text-lg font-medium">Yıllık Planlar</h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <Card
                            v-for="plan in yearlyPlans"
                            :key="plan.id"
                            :class="{ 'border-primary': plan.is_featured, 'ring-1 ring-primary': plan.is_featured }"
                        >
                            <CardHeader>
                                <CardTitle>{{ plan.name }}</CardTitle>
                                <CardDescription>{{ plan.description }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="mb-4 text-center">
                                    <div class="text-3xl font-bold">
                                        {{ plan.formatted_yearly_price }}
                                    </div>
                                    <div class="text-sm text-muted-foreground">yıllık</div>
                                    <div v-if="plan.trial_days > 0" class="mt-1 text-sm">{{ plan.trial_days }} gün ücretsiz deneme</div>
                                </div>

                                <ul class="space-y-2">
                                    <li v-for="feature in plan.features" :key="feature.id" class="flex items-start">
                                        <Check class="mr-2 h-3.5 w-3.5 text-green-500" />
                                        <span>
                                            {{ feature.feature_name }}
                                            <span v-if="feature.formatted_limit !== 'Erişim'" class="ml-1 text-sm font-medium"
                                                >({{ feature.formatted_limit }})</span
                                            >
                                        </span>
                                    </li>
                                </ul>
                            </CardContent>
                            <CardFooter>
                                <Button
                                    v-if="!activeSubscription || activeSubscription.plan_id !== plan.id"
                                    class="w-full"
                                    as="a"
                                    @click="handlePlanSelection(plan.id, 'yearly')"
                                >
                                    {{ activeSubscription ? 'Plana Geç' : 'Seç' }}
                                </Button>
                                <Button v-else class="w-full" variant="outline" disabled> Mevcut Plan </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </div>

                <!-- Hem Aylık Hem Yıllık Seçenekli Planlar -->
                <div v-if="bothPlans.length > 0" class="space-y-4">
                    <h3 class="text-lg font-medium">Esnek Ödeme Seçenekli Planlar</h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <Card
                            v-for="plan in bothPlans"
                            :key="plan.id"
                            :class="{ 'border-primary': plan.is_featured, 'ring-1 ring-primary': plan.is_featured }"
                        >
                            <CardHeader>
                                <CardTitle>{{ plan.name }}</CardTitle>
                                <CardDescription>{{ plan.description }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="mb-4 space-y-2">
                                    <div class="flex items-center justify-center space-x-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold">
                                                {{ plan.formatted_monthly_price }}
                                            </div>
                                            <div class="text-xs text-muted-foreground">aylık</div>
                                        </div>
                                        <div class="text-sm text-muted-foreground">veya</div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold">
                                                {{ plan.formatted_yearly_price }}
                                            </div>
                                            <div class="text-xs text-muted-foreground">yıllık</div>
                                        </div>
                                    </div>
                                    <div v-if="plan.trial_days > 0" class="text-center text-sm">{{ plan.trial_days }} gün ücretsiz deneme</div>
                                </div>

                                <ul class="space-y-2">
                                    <li v-for="feature in plan.features" :key="feature.id" class="flex items-start">
                                        <Check class="mr-2 h-3.5 w-3.5 text-green-500" />
                                        <span>
                                            {{ feature.feature_name }}
                                            <span v-if="feature.formatted_limit !== 'Erişim'" class="ml-1 text-sm font-medium"
                                                >({{ feature.formatted_limit }})</span
                                            >
                                        </span>
                                    </li>
                                </ul>
                            </CardContent>
                            <CardFooter class="flex flex-col space-y-2">
                                <div v-if="!activeSubscription || activeSubscription.plan_id !== plan.id" class="grid w-full grid-cols-2 gap-2">
                                    <Button as="a" @click="handlePlanSelection(plan.id, 'monthly')"> Aylık </Button>
                                    <Button as="a" @click="handlePlanSelection(plan.id, 'yearly')"> Yıllık </Button>
                                </div>
                                <Button v-else class="w-full" variant="outline" disabled> Mevcut Plan </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </div>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
