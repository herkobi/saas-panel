<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import AppLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { RefreshCwIcon } from 'lucide-vue-next';

interface Tenant {
    id: number;
    name: string;
    domain: string | null;
    status: boolean;
    settings: {
        billing?: {
            name: string;
            tax_number: string;
            tax_office?: string;
            address: string;
            city?: string;
            district?: string;
            country?: string;
            postal_code?: string;
            email: string;
            contact_name: string;
            phone?: string;
        };
    };
    created_at: string;
    updated_at: string;
}

interface Subscription {
    id: number;
    plan: {
        name: string;
        billing_period: string;
    };
    ends_at: string;
    status: string;
    price: number;
    next_billing_at: string | null;
    on_trial: boolean;
}

interface FeatureUsage {
    feature_id: number;
    feature_name: string;
    description: string;
    current_usage: number;
    limit: number;
    is_unlimited: boolean;
    usage_percentage: number | null;
    reset_date?: string;
}

interface Activity {
    id: number;
    message: string;
    created_at: string;
}

interface Payment {
    id: number;
    amount: number;
    status: string;
    paid_at: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    type: string;
}

interface Props {
    tenant: Tenant;
    subscription: Subscription | null;
    activities: Activity[];
    payments: Payment[];
    users: User[];
    usage: {
        featureUsage: FeatureUsage[];
        limitWarnings?: Array<{
            feature_id: number;
            feature_name: string;
            percentage: number;
        }>;
    };
}

const props = defineProps<Props>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('tr-TR');
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('tr-TR');
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(amount);
};

const getProgressColor = (percentage: number | null) => {
    if (!percentage) return 'bg-primary';
    if (percentage >= 90) return 'bg-destructive';
    if (percentage >= 75) return 'bg-warning';
    return 'bg-primary';
};

const getStatusBadgeVariant = (status: string) => {
    switch (status) {
        case 'active':
            return 'default';
        case 'pending':
            return 'warning';
        case 'canceled':
            return 'destructive';
        default:
            return 'outline';
    }
};

const getUserTypeBadge = (type: string) => {
    return type === 'tenant_owner' ? 'Sahip' : 'Kullanıcı';
};
</script>

<template>
    <AppLayout>
        <Head :title="tenant.name" />

        <div class="container mx-auto p-4">
            <!-- Başlık -->
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold">{{ tenant.name }}</h1>
                <div class="flex gap-2">
                    <Badge :variant="tenant.status ? 'default' : 'destructive'" class="flex w-14 items-center justify-center">
                        {{ tenant.status ? 'Aktif' : 'Pasif' }}
                    </Badge>
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                <!-- SOL TARAF -->
                <div class="space-y-6 lg:col-span-1">
                    <!-- 1. Tenant Bilgileri (Tüm Detaylar) -->
                    <Card>
                        <CardHeader class="pb-3">
                            <CardTitle class="text-base">Tenant Bilgileri</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4 text-sm">
                            <div class="grid grid-cols-3 gap-2">
                                <span class="text-muted-foreground">Domain:</span>
                                <span class="col-span-2">{{ tenant.domain || '-' }}</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <span class="text-muted-foreground">Oluşturulma:</span>
                                <span class="col-span-2">{{ formatDateTime(tenant.created_at) }}</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <span class="text-muted-foreground">Güncellenme:</span>
                                <span class="col-span-2">{{ formatDateTime(tenant.updated_at) }}</span>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- 2. Abonelik Bilgileri (Tüm Detaylar) -->
                    <Card v-if="subscription">
                        <CardHeader class="pb-3">
                            <CardTitle class="text-base">Abonelik Detayları</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground">Plan Adı:</span>
                                <span>{{ subscription.plan.name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground">Faturalandırma:</span>
                                <span>{{ subscription.plan.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground">Aylık Ücret:</span>
                                <span>{{ formatCurrency(subscription.price) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground">Durum:</span>
                                <Badge :variant="getStatusBadgeVariant(subscription.status)" class="text-xs">
                                    {{
                                        subscription.status === 'active' ? 'Aktif' : subscription.status === 'pending' ? 'Beklemede' : 'İptal Edildi'
                                    }}
                                </Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground">Başlangıç:</span>
                                <span>{{ formatDate(subscription.ends_at) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground">Bitiş Tarihi:</span>
                                <span>{{ formatDate(subscription.ends_at) }}</span>
                            </div>
                            <div v-if="subscription.next_billing_at" class="flex items-center justify-between">
                                <span class="text-muted-foreground">Sonraki Fatura:</span>
                                <span>{{ formatDateTime(subscription.next_billing_at) }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- SAĞ TARAF -->
                <div class="space-y-6 lg:col-span-3">
                    <!-- 3. Fatura ve Yetkili Bilgileri (Tüm Detaylar) -->
                    <Card>
                        <CardHeader class="pb-3">
                            <CardTitle class="text-base">Fatura & Yetkili Bilgileri</CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-1 gap-6 text-sm md:grid-cols-2">
                            <!-- Firma Bilgileri -->
                            <div class="space-y-3">
                                <h3 class="font-medium">Firma Bilgileri</h3>
                                <div class="space-y-2">
                                    <template v-if="tenant.settings?.billing">
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Firma Adı:</span>
                                            <span>{{ tenant.settings.billing.name || '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Vergi Dairesi / No:</span>
                                            <span
                                                >{{ tenant.settings.billing.tax_office || '-' }} /
                                                {{ tenant.settings.billing.tax_number || '-' }}</span
                                            >
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Adres:</span>
                                            <span class="text-right">{{ tenant.settings.billing.address || '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">İl / İlçe:</span>
                                            <span>{{ tenant.settings.billing.city || '-' }} / {{ tenant.settings.billing.district || '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Ülke:</span>
                                            <span>{{ tenant.settings.billing.country || '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Posta Kodu:</span>
                                            <span>{{ tenant.settings.billing.postal_code || '-' }}</span>
                                        </div>
                                    </template>
                                    <div v-else class="text-muted-foreground">Fatura bilgisi bulunamadı</div>
                                </div>
                            </div>

                            <!-- Yetkili Bilgileri -->
                            <div class="space-y-3">
                                <h3 class="font-medium">Yetkili Bilgileri</h3>
                                <div class="space-y-2">
                                    <template v-if="tenant.settings?.billing">
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Yetkili Adı:</span>
                                            <span>{{ tenant.settings.billing.contact_name || '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Email:</span>
                                            <span>{{ tenant.settings.billing.email || '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Telefon:</span>
                                            <span>{{ tenant.settings.billing.phone || '-' }}</span>
                                        </div>
                                    </template>
                                    <div v-else class="text-muted-foreground">Yetkili bilgisi bulunamadı</div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- 4. Kullanım Bilgileri (Tüm Detaylar) -->
                    <Card v-if="usage.featureUsage.length > 0">
                        <CardHeader class="pb-3">
                            <div class="flex items-center justify-between">
                                <CardTitle class="text-base">Kullanım İstatistikleri</CardTitle>
                                <Button variant="ghost" size="sm" @click="$inertia.reload()" class="h-7 w-7 p-0">
                                    <RefreshCwIcon class="h-3.5 w-3.5" />
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-for="feature in usage.featureUsage" :key="feature.feature_id" class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium">{{ feature.feature_name }}</h4>
                                        <p class="text-muted-foreground text-sm">{{ feature.description || 'Açıklama yok' }}</p>
                                    </div>
                                    <div v-if="feature.is_unlimited">
                                        <Badge variant="secondary">Sınırsız</Badge>
                                    </div>
                                    <div v-else class="text-right">
                                        <div class="text-sm">
                                            {{ feature.current_usage }} / {{ feature.limit }}
                                            <span class="text-muted-foreground"> ({{ feature.usage_percentage?.toFixed(1) || 0 }}%) </span>
                                        </div>
                                        <div class="text-muted-foreground text-xs">Kalan: {{ feature.limit - feature.current_usage }}</div>
                                    </div>
                                </div>

                                <Progress
                                    v-if="!feature.is_unlimited"
                                    :value="feature.usage_percentage || 0"
                                    :class="getProgressColor(feature.usage_percentage)"
                                    class="h-2"
                                />

                                <div v-if="feature.reset_date" class="text-muted-foreground text-xs">
                                    ↻ {{ formatDateTime(feature.reset_date) }} tarihinde sıfırlanacak
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- 5. Son Aktivite (Tüm Detaylar) -->
                    <Card>
                        <CardHeader class="pb-3">
                            <CardTitle class="text-base">Son 5 Aktivite</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="rounded-md border">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="p-3 text-left">Mesaj</th>
                                            <th class="p-3 text-left">Tarih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="activities.length === 0" class="border-b">
                                            <td colspan="3" class="text-muted-foreground p-4 text-center">Henüz aktivite kaydı bulunmamaktadır</td>
                                        </tr>
                                        <tr v-for="activity in activities" :key="activity.id" class="hover:bg-muted/50 border-b">
                                            <td class="p-3">{{ activity.message }}</td>
                                            <td class="p-3">{{ formatDateTime(activity.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center pt-2">
                                <Button variant="outline" size="sm" class="text-sm"> Tüm Aktiviteleri Görüntüle </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- 6. Son Ödemeler (Tüm Detaylar) -->
                    <Card>
                        <CardHeader class="pb-3">
                            <CardTitle class="text-base">Son 5 Ödeme</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="rounded-md border">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="p-3 text-left">Tutar</th>
                                            <th class="p-3 text-left">Durum</th>
                                            <th class="p-3 text-left">Tarih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="payments.length === 0" class="border-b">
                                            <td colspan="4" class="text-muted-foreground p-4 text-center">Henüz ödeme kaydı bulunmamaktadır</td>
                                        </tr>
                                        <tr v-for="payment in payments" :key="payment.id" class="hover:bg-muted/50 border-b">
                                            <td class="p-3">{{ formatCurrency(payment.amount) }}</td>
                                            <td class="p-3">
                                                <Badge :variant="payment.status === 'success' ? 'default' : 'destructive'" class="text-xs">
                                                    {{ payment.status === 'success' ? 'Başarılı' : 'Başarısız' }}
                                                </Badge>
                                            </td>
                                            <td class="p-3">{{ formatDateTime(payment.paid_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center pt-2">
                                <Button variant="outline" size="sm" class="text-sm"> Tüm Ödemeleri Görüntüle </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- 7. Kullanıcılar (Tüm Detaylar) -->
                    <Card>
                        <CardHeader class="pb-3">
                            <CardTitle class="text-base">Sistem Kullanıcıları</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="rounded-md border">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="p-3 text-left">Ad</th>
                                            <th class="p-3 text-left">Email</th>
                                            <th class="p-3 text-left">Rol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="user in users" :key="user.id" class="hover:bg-muted/50 border-b">
                                            <td class="p-3">{{ user.name }}</td>
                                            <td class="p-3">{{ user.email }}</td>
                                            <td class="p-3">
                                                <Badge variant="outline" class="text-xs">
                                                    {{ getUserTypeBadge(user.type) }}
                                                </Badge>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
