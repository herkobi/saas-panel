<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Check, ChevronDown, Copy, Loader2, Plus } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Demo',
        href: '/demo',
    },
];

// Demo stats
const stats = {
    totalLinks: 125,
    totalSpaces: 8,
    totalPixels: 12,
    totalCampaigns: 15,
};

// Demo trafik verileri
const clicksData = {
    '2025-03-20': 45,
    '2025-03-21': 52,
    '2025-03-22': 49,
    '2025-03-23': 62,
    '2025-03-24': 58,
    '2025-03-25': 78,
    '2025-03-26': 84,
    '2025-03-27': 96,
    '2025-03-28': 92,
};

// Demo popüler linkler
const popularLinks = [
    { id: 1, alias: 'promo2025', url: 'https://example.com/spring-promotion-2025', clicks: 235, created_at: '3 gün önce' },
    { id: 2, alias: 'newproduct', url: 'https://example.com/new-product-launch-march', clicks: 187, created_at: '5 gün önce' },
    { id: 3, alias: 'blog-post', url: 'https://example.com/blog/top-10-marketing-trends', clicks: 156, created_at: '1 hafta önce' },
    { id: 4, alias: 'webinar', url: 'https://example.com/webinars/growth-hacking', clicks: 132, created_at: '2 hafta önce' },
    { id: 5, alias: 'discount50', url: 'https://example.com/special-offer-50-percent-off', clicks: 98, created_at: '2 hafta önce' },
];

// Demo son aktiviteler
const recentLinks = [
    {
        id: 6,
        alias: 'spring-sale',
        url: 'https://example.com/spring-sale-2025',
        clicks: 45,
        created_at: '2 saat önce',
        space: { id: 1, name: 'Pazarlama', color: '#3B82F6' },
    },
    {
        id: 7,
        alias: 'newsletter',
        url: 'https://example.com/newsletter-march-2025',
        clicks: 23,
        created_at: '5 saat önce',
        space: { id: 2, name: 'İçerik', color: '#10B981' },
    },
    {
        id: 8,
        alias: 'event-april',
        url: 'https://example.com/events/april-meetup',
        clicks: 12,
        created_at: '8 saat önce',
        space: { id: 3, name: 'Etkinlikler', color: '#F59E0B' },
    },
    {
        id: 9,
        alias: 'case-study',
        url: 'https://example.com/studies/ecommerce-growth',
        clicks: 5,
        created_at: '1 gün önce',
        space: { id: 1, name: 'Pazarlama', color: '#3B82F6' },
    },
    {
        id: 10,
        alias: 'partner-prg',
        url: 'https://example.com/partners/program-details',
        clicks: 0,
        created_at: '1 gün önce',
        space: { id: 4, name: 'İş Ortakları', color: '#EC4899' },
    },
];

// Durum değişkenleri
const isCreateModalOpen = ref(false);
const isChartLoading = ref(true);
const copiedLinkId = ref<number | null>(null);
const name = ref('Mehmet Kullanıcı');

// Tarih aralığı seçenekleri
const dateRangeOptions = [
    { label: 'Son 7 gün', value: '7days' },
    { label: 'Son 30 gün', value: '30days' },
    { label: 'Bu ay', value: 'thisMonth' },
    { label: 'Geçen ay', value: 'lastMonth' },
];

const selectedDateRange = ref('30days');

// Form durumu
const form = ref({
    url: '',
    processing: false,
    errors: {},
    reset() {
        this.url = '';
    },
});

// Butonların tıklama davranışları
const createQuickLink = () => {
    form.value.processing = true;

    // Demo için form gönderimi simüle et
    setTimeout(() => {
        form.value.processing = false;
        form.value.reset();
    }, 1000);
};

// Link kopyalama fonksiyonu
const copyToClipboard = (text: string, linkId: number) => {
    // Demo için sadece durum değişkenini güncelle
    copiedLinkId.value = linkId;
    setTimeout(() => {
        copiedLinkId.value = null;
    }, 2000);
};

// Tarih aralığı değiştiğinde verileri güncelle
watch(selectedDateRange, () => {
    isChartLoading.value = true;

    // Demo için yükleme davranışı simüle et
    setTimeout(() => {
        renderTrafficChart();
        isChartLoading.value = false;
    }, 800);
});

// Trafik grafiği
onMounted(() => {
    // Demo için yükleme davranışı simüle et
    setTimeout(() => {
        renderTrafficChart();
        isChartLoading.value = false;
    }, 800);
});

// Trafik grafiği oluşturma fonksiyonu
const renderTrafficChart = async () => {
    try {
        // Chart.js import
        const Chart = await import('chart.js/auto');
        const ctx = document.getElementById('trafficChart') as HTMLCanvasElement;
        if (!ctx) return;

        // Mevcut bir chart varsa temizle
        const chartInstance = Chart.default.getChart(ctx);
        if (chartInstance) {
            chartInstance.destroy();
        }

        const labels = Object.keys(clicksData).sort();
        const data = labels.map((date) => clicksData[date] || 0);

        new Chart.default(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tıklamalar',
                        data: data,
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 12,
                        },
                        bodyFont: {
                            size: 12,
                        },
                        callbacks: {
                            label: function (context) {
                                return `${context.parsed.y} tıklama`;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                        },
                    },
                },
            },
        });
    } catch (error) {
        console.error('Grafik yüklenirken hata oluştu:', error);
    }
};

// Her bir link için domain + alias URL'ini oluştur
const getFullLinkUrl = (alias: string) => {
    return `https://example.com/${alias}`;
};

// Route fonksiyonu simülasyonu
const route = (name: string, params?: any) => {
    if (name === 'app.link.detail') {
        return `#/link/${params}`;
    }
    if (name === 'app.link.create') {
        return `#/link/create`;
    }
    if (name === 'app.campaign.create') {
        return `#/campaign/create`;
    }
    if (name === 'app.links') {
        return `#/links`;
    }
    return '#';
};
</script>

<template>
    <Head title="Demo" />

    <div class="mx-auto max-w-7xl">
        <div class="flex h-full flex-1 flex-col space-y-4 p-4 sm:p-6">
            <!-- Header alanı -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 space-y-0.5 sm:mb-0">
                        <h2 class="text-xl font-semibold tracking-tight">Başlangıç</h2>
                        <p class="text-sm text-muted-foreground">Hoşgeldin, {{ name }}. İşte hesabınızın son durumu.</p>
                    </div>

                    <div class="flex shrink-0 space-x-2">
                        <!-- Link Oluşturma Butonları -->
                        <div class="inline-flex">
                            <Button @click="isCreateModalOpen = true" class="h-7 rounded-r-none px-2 text-xs" size="sm">
                                <Plus class="mr-2 h-3.5 w-3.5" />
                                <span>Hızlı Link Oluştur</span>
                            </Button>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="default" class="h-7 rounded-l-none border-l-0 px-2 text-xs" size="sm">
                                        <ChevronDown class="h-3.5 w-3.5" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem asChild>
                                        <Link :href="route('app.link.create')"> Detaylı Link Oluştur </Link>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>

                        <!-- Kampanya Oluşturma Butonu -->
                        <Button class="h-7 px-2 text-xs">
                            <Link :href="route('app.campaign.create')" class="flex items-center gap-1.5">
                                <Plus class="h-3.5 w-3.5" />
                                <span>Yeni Kampanya</span>
                            </Link>
                        </Button>
                    </div>
                </div>
                <Separator class="my-3 sm:my-4" />
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <!-- Sol Sütun: İstatistikler ve Grafik -->
                <div class="flex flex-col space-y-4">
                    <!-- İstatistik kartları -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Link sayısı kartı -->
                        <TooltipProvider>
                            <Tooltip>
                                <TooltipTrigger asChild>
                                    <div class="cursor-help rounded-lg border bg-card p-4 shadow-sm">
                                        <div class="flex flex-col space-y-1">
                                            <span class="text-xs font-medium text-muted-foreground">Toplam Link</span>
                                            <span class="text-xl font-bold">{{ stats.totalLinks }}</span>
                                        </div>
                                    </div>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p class="text-xs">Sistemde oluşturulmuş toplam link sayısı</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>

                        <!-- Kampanya sayısı kartı -->
                        <TooltipProvider>
                            <Tooltip>
                                <TooltipTrigger asChild>
                                    <div class="cursor-help rounded-lg border bg-card p-4 shadow-sm">
                                        <div class="flex flex-col space-y-1">
                                            <span class="text-xs font-medium text-muted-foreground">Toplam Kampanya</span>
                                            <span class="text-xl font-bold">{{ stats.totalCampaigns }}</span>
                                        </div>
                                    </div>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p class="text-xs">Sistemde oluşturulmuş toplam kampanya sayısı</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>

                    <!-- Trafik Grafiği -->
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between px-4 py-3">
                            <div>
                                <CardTitle className="text-base">Trafik Özeti</CardTitle>
                                <CardDescription className="text-xs">Seçili dönemdeki tıklama sayıları</CardDescription>
                            </div>
                            <Select v-model="selectedDateRange">
                                <SelectTrigger class="h-8 w-[140px] text-xs">
                                    <SelectValue placeholder="Tarih aralığı" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="option in dateRangeOptions" :key="option.value" :value="option.value">
                                        {{ option.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </CardHeader>
                        <CardContent className="px-4 py-2">
                            <div class="relative h-[300px]">
                                <!-- Loading spinner -->
                                <div v-if="isChartLoading" class="absolute inset-0 z-10 flex items-center justify-center bg-background/80">
                                    <Loader2 class="h-8 w-8 animate-spin text-muted-foreground" />
                                </div>

                                <canvas id="trafficChart"></canvas>
                            </div>
                        </CardContent>
                        <!-- Hızlı İstatistik Kartları -->
                        <div class="border-t px-4 py-3">
                            <div class="grid grid-cols-3 gap-4">
                                <!-- En Popüler Cihaz -->
                                <div>
                                    <h4 class="text-xs font-medium text-muted-foreground">En Popüler Cihaz</h4>
                                    <div class="mt-1 flex items-center">
                                        <svg class="mr-1 h-4 w-4 text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.5 1.5H8.25C7.65326 1.5 7.08097 1.73705 6.65901 2.15901C6.23705 2.58097 6 3.15326 6 3.75V20.25C6 20.8467 6.23705 21.419 6.65901 21.841C7.08097 22.2629 7.65326 22.5 8.25 22.5H15.75C16.3467 22.5 16.919 22.2629 17.341 21.841C17.7629 21.419 18 20.8467 18 20.25V3.75C18 3.15326 17.7629 2.58097 17.341 2.15901C16.919 1.73705 16.3467 1.5 15.75 1.5H13.5M10.5 1.5V3H13.5V1.5M10.5 1.5H13.5M10.5 18.75H13.5"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        <span class="font-medium">Mobil</span>
                                        <span class="ml-1 text-xs text-muted-foreground">(65%)</span>
                                    </div>
                                </div>

                                <!-- En Popüler Tarayıcı -->
                                <div>
                                    <h4 class="text-xs font-medium text-muted-foreground">En Popüler Tarayıcı</h4>
                                    <div class="mt-1 flex items-center">
                                        <svg class="mr-1 h-4 w-4 text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M2 12H22"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M12 2C14.5013 4.73835 15.9228 8.29203 16 12C15.9228 15.708 14.5013 19.2616 12 22C9.49872 19.2616 8.07725 15.708 8 12C8.07725 8.29203 9.49872 4.73835 12 2Z"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        <span class="font-medium">Chrome</span>
                                        <span class="ml-1 text-xs text-muted-foreground">(78%)</span>
                                    </div>
                                </div>

                                <!-- En Popüler Ülke -->
                                <div>
                                    <h4 class="text-xs font-medium text-muted-foreground">En Popüler Ülke</h4>
                                    <div class="mt-1 flex items-center">
                                        <svg class="mr-1 h-4 w-4 text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M3.6 9H20.4"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M3.6 15H20.4"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M11.5 3C9.81 5.59 9 8.73 9 12C9 15.27 9.81 18.41 11.5 21"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M12.5 3C14.19 5.59 15 8.73 15 12C15 15.27 14.19 18.41 12.5 21"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        <span class="font-medium">Türkiye</span>
                                        <span class="ml-1 text-xs text-muted-foreground">(86%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Card>

                    <!-- Grafik altına gelecek olan Yaklaşan Etkinlikler bölümü -->
                    <Card class="mt-4">
                        <CardHeader className="px-4 py-3">
                            <CardTitle className="text-base">Yaklaşan Etkinlikler</CardTitle>
                            <CardDescription className="text-xs">Yakın zamanda planlanan ve yayınlanacak içerikler</CardDescription>
                        </CardHeader>
                        <CardContent className="px-4 py-3">
                            <div class="space-y-3">
                                <!-- Zamanlanmış Linkler -->
                                <div class="flex items-center space-x-3 border-l-2 border-blue-500 pl-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-link"
                                        >
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium">Bahar İndirimi Linki</p>
                                            <span class="text-xs text-muted-foreground">1 Nisan 2025</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">Zamanlanmış link yayını</p>
                                    </div>
                                </div>

                                <!-- Bitiş tarihi yaklaşan Kampanya -->
                                <div class="flex items-center space-x-3 border-l-2 border-yellow-500 pl-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-100 text-yellow-700">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-timer"
                                        >
                                            <path d="M10 2h4"></path>
                                            <path d="M12 14v-4"></path>
                                            <path d="M12 14v-4"></path>
                                            <path d="M12 9a7 7 0 1 0 0 14 7 7 0 1 0 0-14Z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium">Mart Sonu Kampanyası</p>
                                            <span class="text-xs text-muted-foreground">Son 2 gün</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">31 Mart 2025'te sona erecek</p>
                                    </div>
                                </div>

                                <!-- Yayınlanacak Kampanya -->
                                <div class="flex items-center space-x-3 border-l-2 border-green-500 pl-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-100 text-green-700">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-megaphone"
                                        >
                                            <path d="m3 11 18-5v12L3 13"></path>
                                            <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium">Yaz Koleksiyonu</p>
                                            <span class="text-xs text-muted-foreground">15 Nisan 2025</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">Otomatik yayınlanacak kampanya</p>
                                    </div>
                                </div>

                                <!-- Zamanlanmış Pixel Aktivasyonu -->
                                <div class="flex items-center space-x-3 border-l-2 border-purple-500 pl-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-purple-100 text-purple-700">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-square-code"
                                        >
                                            <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                                            <path d="m10 10-2 2 2 2"></path>
                                            <path d="m14 14 2-2-2-2"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium">Facebook Pixel Aktivasyonu</p>
                                            <span class="text-xs text-muted-foreground">10 Nisan 2025</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">Yeni pazarlama kampanyası için</p>
                                    </div>
                                </div>

                                <!-- Kampanya izleme başlangıcı -->
                                <div class="flex items-center space-x-3 border-l-2 border-red-500 pl-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-100 text-red-700">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-bar-chart"
                                        >
                                            <line x1="12" x2="12" y1="20" y2="10"></line>
                                            <line x1="18" x2="18" y1="20" y2="4"></line>
                                            <line x1="6" x2="6" y1="20" y2="16"></line>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium">Q2 İzleme Başlangıcı</p>
                                            <span class="text-xs text-muted-foreground">1 Nisan 2025</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">2. Çeyrek izleme raporları başlıyor</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sağ Sütun: Link Oluşturma, Popüler Linkler ve Aktiviteler -->
                <div class="flex flex-col space-y-4">
                    <!-- Link Oluşturma ve Popüler Linkler -->
                    <Card>
                        <CardContent className="p-4">
                            <!-- Link Oluşturma Formu -->
                            <form @submit.prevent="createQuickLink" class="mb-4">
                                <div class="flex">
                                    <Input
                                        v-model="form.url"
                                        placeholder="URL girin..."
                                        type="url"
                                        required
                                        class="h-9 rounded-r-none"
                                        :disabled="form.processing"
                                    />
                                    <Button type="submit" class="h-9 rounded-l-none px-3" :disabled="form.processing">
                                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                                        <ArrowRight v-else class="h-4 w-4" />
                                    </Button>
                                </div>
                                <div v-if="form.errors.url" class="mt-1 text-xs text-red-500">{{ form.errors.url }}</div>
                            </form>

                            <!-- Separator -->
                            <Separator class="my-3" />

                            <!-- Popüler Linkler -->
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-sm font-medium">Popüler Linkler</h3>
                                <Link :href="route('app.links')" class="text-xs text-primary">Tümünü gör</Link>
                            </div>
                            <div
                                v-for="link in popularLinks.slice(0, 5)"
                                :key="link.id"
                                class="group flex items-center justify-between border-b py-2 last:border-0"
                            >
                                <div class="min-w-0 flex-1">
                                    <Link :href="route('app.link.detail', link.id)" class="text-sm font-medium hover:underline">
                                        {{ link.alias }}
                                    </Link>
                                    <p class="truncate text-xs text-muted-foreground">{{ link.url }}</p>
                                </div>
                                <div class="ml-4 flex items-center space-x-2">
                                    <div class="rounded-full bg-secondary px-2 py-0.5 text-xs">{{ link.clicks }}</div>

                                    <TooltipProvider>
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Button
                                                    variant="ghost"
                                                    size="sm"
                                                    className="h-6 w-6 p-0"
                                                    @click="copyToClipboard(getFullLinkUrl(link.alias), link.id)"
                                                >
                                                    <Check v-if="copiedLinkId === link.id" class="h-4 w-4 text-green-500" />
                                                    <Copy v-else class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                                    <span class="sr-only">Kopyala</span>
                                                </Button>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                <p class="text-xs">
                                                    {{ copiedLinkId === link.id ? 'Kopyalandı!' : 'Linki kopyala' }}
                                                </p>
                                            </TooltipContent>
                                        </Tooltip>
                                    </TooltipProvider>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Son Aktiviteler -->
                    <Card>
                        <CardContent className="p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-sm font-medium">Son Aktiviteler</h3>
                                <Link :href="route('app.links')" class="text-xs text-primary">Tümünü gör</Link>
                            </div>

                            <div v-for="link in recentLinks.slice(0, 5)" :key="link.id" class="mb-3 last:mb-0">
                                <div class="flex flex-col space-y-1">
                                    <div class="flex items-center justify-between">
                                        <Link :href="route('app.link.detail', link.id)" class="text-sm font-medium hover:underline">
                                            {{ link.alias }}
                                        </Link>
                                        <span class="text-xs text-muted-foreground">{{ link.created_at }}</span>
                                    </div>
                                    <p class="line-clamp-1 text-xs text-muted-foreground">{{ link.url }}</p>
                                    <div class="flex items-center">
                                        <span
                                            v-if="link.space"
                                            class="mr-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs"
                                            :style="{ backgroundColor: link.space.color + '20', color: link.space.color }"
                                        >
                                            {{ link.space.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>
