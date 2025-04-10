<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/Layout.vue';
import { Head } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import { onMounted, onUnmounted, ref } from 'vue';

interface FeatureUsage {
    feature_name: string;
    description: string;
    current_usage: number;
    limit: number;
    is_unlimited: boolean;
    reset_date: string | null;
    usage_history?: {
        date: string;
        usage: number;
    }[];
    usage_percentage?: number | null;
}

interface Subscription {
    id: number;
    plan_id: number;
    plan_name: string;
    billing_period: string;
    price: number;
    ends_at: string | null;
    next_billing_at: string | null;
    status: number;
    status_label: string;
    trial_ends_at: string | null;
    on_trial: boolean;
}

interface LimitWarning {
    feature_id: number;
    feature_name: string;
    feature_slug: string;
    threshold: number;
    current_usage: number;
    limit: number;
    percentage: number;
}

interface Props {
    subscription: Subscription | null;
    featureUsage: FeatureUsage[];
    limitWarnings: LimitWarning[];
}

const props = defineProps<Props>();

// Chart referansları
const chartRefs = ref<Record<string, HTMLCanvasElement | null>>({});
const charts = ref<Record<string, Chart | null>>({});

// Progress çubuğu renkleri
const getProgressColor = (percentage: number | null) => {
    if (!percentage) return 'bg-primary';
    if (percentage >= 90) return 'bg-destructive';
    if (percentage >= 75) return 'bg-warning';
    return 'bg-primary';
};

// Kalan kullanım miktarını hesapla
const getRemainingUsage = (current: number, limit: number) => {
    return Math.max(0, limit - current);
};

// Limit Uyarı badge rengi
const getWarningBadgeColor = (threshold: number) => {
    if (threshold >= 90) return 'bg-red-100 text-red-800';
    if (threshold >= 75) return 'bg-yellow-100 text-yellow-800';
    return 'bg-blue-100 text-blue-800';
};

// Badge stil sınıfları
const getStatusBadgeClass = (status: string) => {
    if (status === 'Aktif') return 'bg-green-500 text-white';
    if (status === 'Beklemede') return 'bg-yellow-500 text-white';
    if (status === 'İptal Edildi') return 'bg-red-500 text-white';
    return '';
};

// Kullanım grafiklerini oluştur
onMounted(() => {
    if (props.featureUsage && props.featureUsage.length > 0) {
        props.featureUsage.forEach((feature, index) => {
            if (feature.usage_history && feature.usage_history.length > 0 && chartRefs.value[`chart-${index}`]) {
                const ctx = chartRefs.value[`chart-${index}`]?.getContext('2d');
                if (ctx) {
                    // Grafik verilerini hazırla
                    const labels = feature.usage_history.map((item) => {
                        const date = new Date(item.date);
                        return `${date.getDate()}/${date.getMonth() + 1}`;
                    });

                    const data = feature.usage_history.map((item) => item.usage);

                    // Grafik oluştur
                    charts.value[`chart-${index}`] = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Kullanım',
                                    data,
                                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                    callbacks: {
                                        label: function (context) {
                                            return `${context.parsed.y} kullanım`;
                                        },
                                    },
                                },
                            },
                        },
                    });
                }
            }
        });
    }
});

// Component unmount edildiğinde chart'ları temizle
onUnmounted(() => {
    Object.values(charts.value).forEach((chart) => {
        if (chart) {
            chart.destroy();
        }
    });
});
</script>

<template>
    <AppLayout>
        <Head title="Abonelik Bilgisi" />

        <SubscriptionLayout>
            <div class="space-y-6">
                <HeadingSmall title="Abonelik Bilgisi" description="Geçerli abonelik planınızı ve kullanım limitlerini görüntüleyin" />

                <!-- Limit Uyarıları -->
                <div v-if="limitWarnings && limitWarnings.length > 0" class="space-y-3">
                    <div v-for="warning in limitWarnings" :key="`${warning.feature_id}-${warning.threshold}`" class="rounded-lg border p-4">
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                            <div class="flex items-start space-x-3">
                                <div class="shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ warning.feature_name }} özelliği için limit uyarısı!</h4>
                                    <p class="text-sm text-muted-foreground">
                                        Kullanım limitinin {{ warning.percentage.toFixed(1) }}% oranına ulaştınız. Mevcut kullanım:
                                        {{ warning.current_usage }}/{{ warning.limit }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span :class="getWarningBadgeColor(warning.threshold)" class="rounded-full px-2.5 py-1 text-xs font-medium">
                                    {{ warning.threshold }}% doluluk
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abonelik Bilgileri Kartı -->
                <div v-if="subscription" class="rounded-lg border p-6 shadow-sm">
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div>
                            <h3 class="text-lg font-medium">Mevcut Abonelik</h3>
                            <p class="text-sm text-muted-foreground">
                                {{ subscription.plan_name }} ({{ subscription.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }})
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <Badge :class="getStatusBadgeClass(subscription.status_label)" class="px-2 py-0.5 text-xs">{{
                                subscription.status_label
                            }}</Badge>
                            <Badge v-if="subscription.on_trial" variant="outline" class="px-2 py-0.5 text-xs">Deneme Süresi</Badge>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="rounded-md border p-4">
                            <h4 class="text-sm font-medium">Faturalama Dönemi</h4>
                            <p class="mt-1 text-sm">{{ subscription.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }}</p>
                        </div>
                        <div class="rounded-md border p-4">
                            <h4 class="text-sm font-medium">Bitiş Tarihi</h4>
                            <p class="mt-1 text-sm">
                                {{ subscription.ends_at ? new Date(subscription.ends_at).toLocaleDateString() : 'Süresiz' }}
                            </p>
                        </div>
                        <div class="rounded-md border p-4">
                            <h4 class="text-sm font-medium">Sonraki Ödeme</h4>
                            <p class="mt-1 text-sm">
                                {{ subscription.next_billing_at ? new Date(subscription.next_billing_at).toLocaleDateString() : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end space-x-4">
                        <Button as="a" variant="outline" :href="route('app.subscription.change')">Plan Değiştir</Button>
                        <Button as="a" variant="destructive" :href="route('app.subscription.cancel.options', { subscription: subscription.id })">
                            Aboneliği İptal Et
                        </Button>
                    </div>
                </div>

                <!-- Abonelik yoksa planlar linkine yönlendir -->
                <div v-else class="rounded-lg border p-6 shadow-sm">
                    <div class="text-center">
                        <h3 class="text-lg font-medium">Aktif Abonelik Bulunamadı</h3>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Şu anda aktif bir aboneliğiniz bulunmamaktadır. Hizmetleri kullanmak için bir plan seçmelisiniz.
                        </p>
                        <Button as="a" class="mt-4" :href="route('app.subscription.plans')">Planları Görüntüle</Button>
                    </div>
                </div>

                <!-- Kullanım Limitleri Kartı - Sadece abonelik varsa göster -->
                <Card v-if="subscription && featureUsage && featureUsage.length > 0">
                    <CardHeader>
                        <CardTitle class="text-xl">Özellik Kullanımı</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-6">
                            <div v-for="(feature, index) in featureUsage" :key="index" class="space-y-3">
                                <div class="flex flex-col space-y-2 md:flex-row md:items-center md:justify-between md:space-y-0">
                                    <div>
                                        <h4 class="font-medium">{{ feature.feature_name }}</h4>
                                        <p class="text-sm text-muted-foreground">{{ feature.description }}</p>
                                    </div>
                                    <div v-if="feature.is_unlimited">
                                        <Badge>Sınırsız</Badge>
                                    </div>
                                    <div v-else class="text-sm">
                                        {{ feature.current_usage }} / {{ feature.limit }}
                                        <span v-if="feature.reset_date" class="ml-2 text-muted-foreground">
                                            ({{ new Date(feature.reset_date).toLocaleDateString() }} tarihinde yenilenir)
                                        </span>
                                    </div>
                                </div>

                                <div v-if="!feature.is_unlimited">
                                    <Progress :value="feature.usage_percentage" :class="getProgressColor(feature.usage_percentage)" />
                                    <div class="mt-1 flex justify-between text-xs text-muted-foreground">
                                        <span>Kullanılan: {{ feature.current_usage }}</span>
                                        <span>Kalan: {{ getRemainingUsage(feature.current_usage, feature.limit) }}</span>
                                    </div>
                                </div>

                                <!-- Kullanım grafiği -->
                                <div v-if="feature.usage_history && feature.usage_history.length > 1" class="mt-4 h-40">
                                    <h5 class="mb-2 text-sm font-medium text-muted-foreground">Son 30 Gün Kullanımı</h5>
                                    <canvas :ref="(el) => (chartRefs[`chart-${index}`] = el)"></canvas>
                                </div>

                                <Separator v-if="index < featureUsage.length - 1" class="mt-4" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
