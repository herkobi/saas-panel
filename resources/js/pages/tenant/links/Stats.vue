<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import { ArrowLeft, BarChart3, Calendar, ChevronDown, Download, FileText, FileType2, Globe, Monitor, RefreshCcw } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

interface Stats {
    totalClicks: number;
    uniqueClicks: number;
    dailyClicks: Record<string, number>;
    deviceStats: Array<{ value: string; count: number }>;
    browserStats: Array<{ value: string; count: number }>;
    platformStats: Array<{ value: string; count: number }>;
    countryStats: Array<{ value: string; count: number }>;
    cityStats: Array<{ value: string; count: number }>;
    referrerStats: Array<{ value: string; count: number }>;
    hourlyStats: Array<number>;
    dateRange: { from: string; to: string };
}

interface Link {
    id: number;
    url: string;
    alias: string;
    title: string | null;
    description: string | null;
    clicks: number;
}

interface DateRange {
    startDate: string;
    endDate: string;
}

interface Props {
    link: Link;
    stats: Stats;
    dateRange: DateRange;
}

const props = defineProps<Props>();

// Dışa aktarma fonksiyonları
const exportExcel = () => {
    window.location.href = route('app.link.stats.export', {
        id: props.link.id,
        start_date: startDate.value,
        end_date: endDate.value,
        format: 'excel',
    });
};

const exportCsv = () => {
    window.location.href = route('app.link.stats.export', {
        id: props.link.id,
        start_date: startDate.value,
        end_date: endDate.value,
        format: 'csv',
    });
};

const exportPdf = () => {
    window.location.href = route('app.link.stats.export', {
        id: props.link.id,
        start_date: startDate.value,
        end_date: endDate.value,
        format: 'pdf',
    });
};

// Chart referansları
const dailyClicksChart = ref(null);
const deviceStatsChart = ref(null);
const hourlyStatsChart = ref(null);

// Chart nesneleri
let dailyClicksChartObj = null;
let deviceStatsChartObj = null;
let hourlyStatsChartObj = null;

// Tarih aralığı için state - string olarak saklayacağız
const startDate = ref(props.dateRange.startDate);
const endDate = ref(props.dateRange.endDate);

// Tarih aralığı ile filtreleme
const filterByDateRange = () => {
    window.location.href = route('app.link.stats', {
        id: props.link.id,
        start_date: startDate.value,
        end_date: endDate.value,
    });
};

// Eğer gösterilecek favicon varsa, bu fonksiyon kullanılabilir
const getFavicon = (url: string) => {
    try {
        const domain = new URL(url).hostname;
        // Önce Google'ın servisini dene
        return `https://www.google.com/s2/favicons?domain=${domain}&sz=32`;
    } catch (e) {
        // Hata durumunda varsayılan simge
        return '/favicon.png';
    }
};

// Favicon yükleme hatası
const handleFaviconError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    target.src = '/favicon.png';
};

// Grafikleri oluştur
onMounted(() => {
    // Günlük tıklama grafiği
    if (dailyClicksChart.value) {
        const ctx = dailyClicksChart.value.getContext('2d');
        const dates = Object.keys(props.stats.dailyClicks);
        const clicks = Object.values(props.stats.dailyClicks);

        dailyClicksChartObj = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Tıklamalar',
                        data: clicks,
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
            },
        });
    }

    // Cihaz istatistikleri grafiği (pasta grafik)
    if (deviceStatsChart.value && props.stats.deviceStats.length > 0) {
        const ctx = deviceStatsChart.value.getContext('2d');

        deviceStatsChartObj = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: props.stats.deviceStats.map((item) => item.value),
                datasets: [
                    {
                        data: props.stats.deviceStats.map((item) => item.count),
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                        ],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }

    // Saatlik istatistik grafiği (bar chart)
    if (hourlyStatsChart.value) {
        const ctx = hourlyStatsChart.value.getContext('2d');
        const hours = Array.from({ length: 24 }, (_, i) => i.toString().padStart(2, '0') + ':00');

        hourlyStatsChartObj = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [
                    {
                        label: 'Tıklamalar',
                        data: props.stats.hourlyStats,
                        backgroundColor: 'rgba(75, 192, 192, 0.8)',
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
            },
        });
    }
});

// Component unmount edildiğinde chart'ları temizle
onUnmounted(() => {
    if (dailyClicksChartObj) {
        dailyClicksChartObj.destroy();
    }
    if (deviceStatsChartObj) {
        deviceStatsChartObj.destroy();
    }
    if (hourlyStatsChartObj) {
        hourlyStatsChartObj.destroy();
    }
});

// Dönüşüm oranı hesaplama
const conversionRate = computed(() => {
    if (props.stats.totalClicks === 0) return '0%';
    const rate = (props.stats.uniqueClicks / props.stats.totalClicks) * 100;
    return rate.toFixed(1) + '%';
});

// Pasta grafik yoksa gösterilecek uygun mesaj
const noDeviceStatsMessage = computed(() => {
    return props.stats.deviceStats.length === 0 ? 'Henüz cihaz verisi yok' : '';
});
</script>

<template>
    <AppLayout>
        <Head :title="`'${link.alias}' İstatistikleri`" />
        <LinksLayout>
            <div class="w-full space-y-6">
                <!-- Başlık ve Geri Butonu -->
                <div class="flex w-full items-center justify-between">
                    <div>
                        <div class="flex items-start">
                            <img :src="getFavicon(link.url)" @error="handleFaviconError" class="mr-2 mt-1 h-3.5 w-3.5" alt="favicon" />
                            <div>
                                <h2 class="text-lg font-semibold">{{ 'https://kampanya.test/' + link.alias }} İstatistikleri</h2>
                                <p class="text-sm text-muted-foreground">
                                    {{ startDate }} - {{ endDate }} tarihleri arasındaki detaylı istatistik verileri
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Dışa Aktarma Dropdown -->
                        <div class="inline-flex">
                            <Button @click="exportExcel" class="h-7 rounded-r-none px-2 text-xs" size="sm">
                                <Download class="h-3.5 w-3.5" />
                                <span>Excel Olarak İndir</span>
                            </Button>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="default" class="h-7 rounded-l-none border-l-0 px-2 text-xs" size="sm">
                                        <ChevronDown class="h-3.5 w-3.5" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem @click="exportCsv" class="cursor-pointer">
                                        <FileText class="h-3.5 w-3.5" />
                                        <span>CSV Olarak İndir</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem @click="exportPdf" class="cursor-pointer">
                                        <FileType2 class="h-3.5 w-3.5" />
                                        <span>PDF Olarak İndir</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>

                        <Button as-child variant="outline" size="sm">
                            <Link :href="route('app.link.detail', link.id)" class="flex items-center gap-1.5">
                                <ArrowLeft class="h-3.5 w-3.5" />
                                <span>Geri Dön</span>
                            </Link>
                        </Button>
                    </div>
                </div>

                <!-- Tarih Filtreleme -->
                <Card class="shadow-sm">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-base font-medium">Tarih Aralığı</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                            <div class="grid gap-1.5">
                                <label for="startDate" class="text-sm font-medium">Başlangıç Tarihi</label>
                                <div class="relative">
                                    <!-- Basitleştirilmiş tarih girişi -->
                                    <input
                                        type="date"
                                        v-model="startDate"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                        placeholder="Başlangıç tarihi seçin"
                                    />
                                </div>
                            </div>

                            <div class="grid gap-1.5">
                                <label for="endDate" class="text-sm font-medium">Bitiş Tarihi</label>
                                <div class="relative">
                                    <!-- Basitleştirilmiş tarih girişi -->
                                    <input
                                        type="date"
                                        v-model="endDate"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                        placeholder="Bitiş tarihi seçin"
                                    />
                                </div>
                            </div>

                            <Button type="button" variant="default" @click="filterByDateRange">
                                <RefreshCcw class="h-3.5 w-3.5" />
                                <span>Filtreyi Uygula</span>
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Üst İstatistikler -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-sm font-medium text-muted-foreground">Toplam Tıklama</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ stats.totalClicks }}</div>
                        </CardContent>
                    </Card>

                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-sm font-medium text-muted-foreground">Tekil Tıklama</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ stats.uniqueClicks }}</div>
                        </CardContent>
                    </Card>

                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-sm font-medium text-muted-foreground">Dönüşüm Oranı</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ conversionRate }}</div>
                        </CardContent>
                    </Card>

                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-sm font-medium text-muted-foreground">Tarih Aralığı</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-sm font-medium">{{ stats.dateRange.from }} - {{ stats.dateRange.to }}</div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Grafik ve Listeler -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Günlük Tıklama Grafiği -->
                    <Card class="shadow-sm lg:col-span-3">
                        <CardHeader class="pb-2">
                            <CardTitle class="flex items-center text-base font-medium">
                                <BarChart3 class="mr-2 h-5 w-5" />
                                Günlük Tıklama İstatistikleri
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="h-80 w-full">
                                <canvas ref="dailyClicksChart"></canvas>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Cihaz Dağılımı -->
                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="flex items-center text-base font-medium">
                                <Monitor class="mr-2 h-5 w-5" />
                                Cihaz Dağılımı
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="stats.deviceStats.length > 0" class="flex h-64 items-center justify-center">
                                <canvas ref="deviceStatsChart"></canvas>
                            </div>
                            <div v-else class="flex h-64 items-center justify-center">
                                <p class="text-sm text-muted-foreground">{{ noDeviceStatsMessage }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Tarayıcı İstatistikleri -->
                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-base font-medium">Tarayıcı İstatistikleri</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="max-h-64 overflow-auto">
                                <div v-if="stats.browserStats.length > 0" class="space-y-2">
                                    <div
                                        v-for="browser in stats.browserStats"
                                        :key="browser.value"
                                        class="flex items-center justify-between rounded-md border p-2"
                                    >
                                        <span class="text-sm font-medium">{{ browser.value }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-muted-foreground">{{ browser.count }}</span>
                                            <div class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                {{ ((browser.count / stats.totalClicks) * 100).toFixed(1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="flex h-32 items-center justify-center">
                                    <p class="text-sm text-muted-foreground">Henüz tarayıcı verisi yok</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Platform İstatistikleri -->
                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-base font-medium">Platform İstatistikleri</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="max-h-64 overflow-auto">
                                <div v-if="stats.platformStats.length > 0" class="space-y-2">
                                    <div
                                        v-for="platform in stats.platformStats"
                                        :key="platform.value"
                                        class="flex items-center justify-between rounded-md border p-2"
                                    >
                                        <span class="text-sm font-medium">{{ platform.value }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-muted-foreground">{{ platform.count }}</span>
                                            <div class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                {{ ((platform.count / stats.totalClicks) * 100).toFixed(1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="flex h-32 items-center justify-center">
                                    <p class="text-sm text-muted-foreground">Henüz platform verisi yok</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Saatlik Dağılım Grafiği -->
                    <Card class="shadow-sm lg:col-span-3">
                        <CardHeader class="pb-2">
                            <CardTitle class="flex items-center text-base font-medium">
                                <Calendar class="mr-2 h-5 w-5" />
                                Saatlik Dağılım
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="h-64 w-full">
                                <canvas ref="hourlyStatsChart"></canvas>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Ülke İstatistikleri -->
                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="flex items-center text-base font-medium">
                                <Globe class="mr-2 h-5 w-5" />
                                Ülke İstatistikleri
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="max-h-64 overflow-auto">
                                <div v-if="stats.countryStats.length > 0" class="space-y-2">
                                    <div
                                        v-for="country in stats.countryStats"
                                        :key="country.value"
                                        class="flex items-center justify-between rounded-md border p-2"
                                    >
                                        <span class="text-sm font-medium">{{ country.value }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-muted-foreground">{{ country.count }}</span>
                                            <div class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                {{ ((country.count / stats.totalClicks) * 100).toFixed(1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="flex h-32 items-center justify-center">
                                    <p class="text-sm text-muted-foreground">Henüz ülke verisi yok</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Şehir İstatistikleri -->
                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-base font-medium">Şehir İstatistikleri</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="max-h-64 overflow-auto">
                                <div v-if="stats.cityStats.length > 0" class="space-y-2">
                                    <div
                                        v-for="city in stats.cityStats"
                                        :key="city.value"
                                        class="flex items-center justify-between rounded-md border p-2"
                                    >
                                        <span class="text-sm font-medium">{{ city.value }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-muted-foreground">{{ city.count }}</span>
                                            <div class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                {{ ((city.count / stats.totalClicks) * 100).toFixed(1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="flex h-32 items-center justify-center">
                                    <p class="text-sm text-muted-foreground">Henüz şehir verisi yok</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Yönlendiren Siteler -->
                    <Card class="shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-base font-medium">Yönlendiren Siteler</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="max-h-64 overflow-auto">
                                <div v-if="stats.referrerStats.length > 0" class="space-y-2">
                                    <div
                                        v-for="referrer in stats.referrerStats"
                                        :key="referrer.value"
                                        class="flex items-center justify-between rounded-md border p-2"
                                    >
                                        <span class="text-sm font-medium">{{ referrer.value || 'Doğrudan' }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-muted-foreground">{{ referrer.count }}</span>
                                            <div class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                {{ ((referrer.count / stats.totalClicks) * 100).toFixed(1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="flex h-32 items-center justify-center">
                                    <p class="text-sm text-muted-foreground">Henüz yönlendiren site verisi yok</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </LinksLayout>
    </AppLayout>
</template>
