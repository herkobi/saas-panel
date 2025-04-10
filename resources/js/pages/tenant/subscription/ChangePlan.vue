<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
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
    is_upgrade: boolean;
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

interface CurrentSubscription {
    id: number;
    plan_id: number;
    plan_name: string;
    billing_period: string;
    price: number;
    ends_at: string | null;
    formatted_price: string;
}

interface Props {
    plans: Record<string | number, Plan> | Plan[];
    currentSubscription: CurrentSubscription;
}

const props = defineProps<Props>();

// Planları diziye dönüştür (object veya array olabilir)
const plansArray = computed(() => {
    if (!props.plans) return [];

    // Eğer plans diziyse
    if (Array.isArray(props.plans)) {
        return props.plans;
    }

    // Eğer plans obje ise, değerleri diziye dönüştür
    return Object.values(props.plans);
});

// Planları gruplara ayır
const monthlyPlans = computed(() => plansArray.value.filter((plan) => plan.billing_period === 'monthly' || plan.billing_period === 'both'));
const yearlyPlans = computed(() => plansArray.value.filter((plan) => plan.billing_period === 'yearly' || plan.billing_period === 'both'));

// Plan değiştirme seçeneklerine git
const goToChangeOptions = (planId: number, billingPeriod: string) => {
    router.visit(route('app.subscription.change.options', { plan: planId, billing_period: billingPeriod }));
};
</script>

<template>
    <AppLayout>
        <Head title="Plan Değiştir" />

        <SubscriptionLayout>
            <div class="space-y-8">
                <HeadingSmall title="Plan Değiştir" description="Abonelik planınızı değiştirin ve ihtiyaçlarınıza daha uygun bir plana geçiş yapın" />

                <div class="rounded-lg border p-6 shadow-sm">
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div>
                            <h3 class="text-lg font-medium">Mevcut Aboneliğiniz</h3>
                            <p class="text-sm text-muted-foreground">{{ currentSubscription.plan_name }} ({{ currentSubscription.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }})</p>
                        </div>
                        <div class="text-lg font-medium">
                            {{ currentSubscription.formatted_price }}
                        </div>
                    </div>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div class="rounded-md border p-4">
                            <h4 class="text-sm font-medium">Faturalama Dönemi</h4>
                            <p class="mt-1 text-sm">{{ currentSubscription.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }}</p>
                        </div>
                        <div class="rounded-md border p-4">
                            <h4 class="text-sm font-medium">Dönem Bitiş Tarihi</h4>
                            <p class="mt-1 text-sm">
                                {{ currentSubscription.ends_at ? new Date(currentSubscription.ends_at).toLocaleDateString() : 'Süresiz' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Plan listesi boşsa veya gelmediyse hata mesajı göster -->
                <div v-if="plansArray.length === 0" class="rounded-lg border p-6 shadow-sm">
                    <div class="text-center">
                        <h3 class="text-lg font-medium">Plan Bilgileri Yüklenemiyor</h3>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Mevcut plan bilgileri yüklenirken bir sorun oluştu. Lütfen daha sonra tekrar deneyin veya yönetici ile iletişime geçin.
                        </p>
                        <Button as="a" class="mt-4" :href="route('app.subscription.usage')">Geri Dön</Button>
                    </div>
                </div>

                <!-- Aylık Planlar -->
                <div v-if="monthlyPlans.length > 0" class="space-y-4">
                    <h3 class="text-lg font-medium">Aylık Planlar</h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <Card
                            v-for="plan in monthlyPlans"
                            :key="`monthly-${plan.id}`"
                            :class="{ 'border-primary': plan.is_featured, 'ring-1 ring-primary': plan.is_featured }"
                        >
                            <CardHeader>
                                <div class="flex justify-between">
                                    <CardTitle>{{ plan.name }}</CardTitle>
                                    <Badge :variant="plan.is_upgrade ? 'default' : 'outline'">
                                        {{ plan.is_upgrade ? 'Yükseltme' : 'Düşürme' }}
                                    </Badge>
                                </div>
                                <CardDescription>{{ plan.description }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="mb-4 text-center">
                                    <div class="text-3xl font-bold">
                                        {{ plan.formatted_monthly_price }}
                                    </div>
                                    <div class="text-sm text-muted-foreground">aylık</div>
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
                                <Button class="w-full" as="a" @click="goToChangeOptions(plan.id, 'monthly')">
                                    Bu Plana Geç
                                </Button>
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
                            :key="`yearly-${plan.id}`"
                            :class="{ 'border-primary': plan.is_featured, 'ring-1 ring-primary': plan.is_featured }"
                        >
                            <CardHeader>
                                <div class="flex justify-between">
                                    <CardTitle>{{ plan.name }}</CardTitle>
                                    <Badge :variant="plan.is_upgrade ? 'default' : 'outline'">
                                        {{ plan.is_upgrade ? 'Yükseltme' : 'Düşürme' }}
                                    </Badge>
                                </div>
                                <CardDescription>{{ plan.description }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="mb-4 text-center">
                                    <div class="text-3xl font-bold">
                                        {{ plan.formatted_yearly_price }}
                                    </div>
                                    <div class="text-sm text-muted-foreground">yıllık</div>
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
                                <Button class="w-full" as="a" @click="goToChangeOptions(plan.id, 'yearly')">
                                    Plana Geç
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </div>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
