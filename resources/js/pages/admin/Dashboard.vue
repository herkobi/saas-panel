<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Activity, DollarSign, RefreshCw, UserPlus, Users } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    stats: {
        totalTenants: number;
        activeTenants: number;
        newTenants: number;
        monthlyRevenue: number;
    };
    tenantGrowth: Array<{ date: string; count: number }>;
    planDistribution: Array<{ name: string; count: number }>;
    recentActivities: Array<{
        id: number;
        message: string;
        tenant: { id: number; name: string };
        created_at: string;
    }>;
}>();

const selectedDateRange = ref('30days');

// Grafikleri oluşturma (Mevcut yapınıza uygun)
const renderCharts = async () => {
    // Tenant büyüme grafiği
    const Chart = await import('chart.js/auto');
    const tenantCtx = document.getElementById('tenantGrowthChart');

    if (tenantCtx) {
        new Chart.default(tenantCtx, {
            type: 'line',
            data: {
                labels: props.tenantGrowth.map((item) => item.date),
                datasets: [
                    {
                        label: 'Yeni Tenantlar',
                        data: props.tenantGrowth.map((item) => item.count),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                ],
            },
            options: { responsive: true, maintainAspectRatio: false },
        });
    }

    // Plan dağılım grafiği
    const planCtx = document.getElementById('planDistributionChart');

    if (planCtx) {
        new Chart.default(planCtx, {
            type: 'pie',
            data: {
                labels: props.planDistribution.map((item) => item.name),
                datasets: [
                    {
                        data: props.planDistribution.map((item) => item.count),
                        backgroundColor: ['#3B82F6', '#10B981', '#6366F1', '#F59E0B'],
                    },
                ],
            },
            options: { responsive: true, maintainAspectRatio: false },
        });
    }
};

onMounted(renderCharts);
</script>
<template>
    <Head title="Yönetici Paneli" />

    <AppLayout>
        <div class="flex flex-col space-y-6 p-4">
            <!-- Başlık ve Filtreler -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Yönetici Paneli</h1>
                    <p class="text-muted-foreground text-sm">Sistem istatistikleri ve analizler</p>
                </div>
                <div class="flex items-center space-x-2">
                    <Select v-model="selectedDateRange">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue placeholder="Tarih aralığı" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="7days">Son 7 gün</SelectItem>
                            <SelectItem value="30days">Son 30 gün</SelectItem>
                            <SelectItem value="thisMonth">Bu ay</SelectItem>
                        </SelectContent>
                    </Select>
                    <Button variant="outline" size="sm" @click="renderCharts">
                        <RefreshCw class="mr-2 h-4 w-4" />
                        Yenile
                    </Button>
                </div>
            </div>

            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Toplam Tenant</CardTitle>
                        <Users class="text-muted-foreground h-4 w-4" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalTenants }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Aktif Tenant</CardTitle>
                        <Activity class="text-muted-foreground h-4 w-4" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.activeTenants }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Yeni Tenant</CardTitle>
                        <UserPlus class="text-muted-foreground h-4 w-4" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">+{{ stats.newTenants }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Aylık Gelir</CardTitle>
                        <DollarSign class="text-muted-foreground h-4 w-4" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">${{ stats.monthlyRevenue }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Grafikler -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle>Tenant Büyüme Grafiği</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="h-[300px]">
                            <canvas id="tenantGrowthChart"></canvas>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Plan Dağılımı</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="h-[300px]">
                            <canvas id="planDistributionChart"></canvas>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Son Etkinlikler -->
            <Card>
                <CardHeader>
                    <CardTitle>Son Etkinlikler</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start gap-4">
                            <div class="bg-muted rounded-full p-2">
                                <Activity class="h-4 w-4" />
                            </div>
                            <div>
                                <p class="font-medium">{{ activity.message }}</p>
                                <p class="text-muted-foreground text-sm">
                                    {{ new Date(activity.created_at).toLocaleString() }} •
                                    <a :href="`/admin/tenants/${activity.tenant.id}`" class="text-primary hover:underline">
                                        {{ activity.tenant.name }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
