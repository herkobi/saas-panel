<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import AppLayout from '@/layouts/TenantLayout.vue';
import LinkCreateModal from '@/pages/tenant/links/LinkCreateModal.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowRight, Check, ChevronDown, Copy, Loader2, Plus } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/app',
    },
];

// Controller'dan gelen prop'ları tanımla
const props = defineProps<{
    name?: string;
    stats: {
        totalLinks: number;
        totalSpaces: number;
        totalPixels: number;
        totalCampaigns: number;
    };
    recentLinks: Array<{
        id: number;
        url: string;
        alias: string;
        clicks: number;
        space: {
            id: number;
            name: string;
            color: string;
        } | null;
        created_at: string;
    }>;
    popularLinks: Array<{
        id: number;
        url: string;
        alias: string;
        clicks: number;
        created_at: string;
    }>;
    dateRange: {
        start: string;
        end: string;
    };
    clicksData?: Record<string, number>;
    upcomingEvents?: {
        scheduled_links: Array<any>;
        ending_campaigns: Array<any>;
        upcoming_campaigns: Array<any>;
    };
    quickStats?: {
        device: { name: string; percentage: number };
        browser: { name: string; percentage: number };
        country: { name: string; percentage: number };
    };
}>();

const isCreateModalOpen = ref(false);
const isChartLoading = ref(true);
const copiedLinkId = ref<number | null>(null);

// Tarih aralığı seçenekleri
const dateRangeOptions = [
    { label: 'Son 7 gün', value: '7days' },
    { label: 'Son 30 gün', value: '30days' },
    { label: 'Bu ay', value: 'thisMonth' },
    { label: 'Geçen ay', value: 'lastMonth' },
];

const selectedDateRange = ref('30days');

// Tarih aralığını hesaplama yardımcı fonksiyonu
const calculateDateRange = (rangeType: string) => {
    const now = new Date();
    let start: Date,
        end = now;

    switch (rangeType) {
        case '7days':
            start = new Date();
            start.setDate(start.getDate() - 7);
            break;
        case '30days':
            start = new Date();
            start.setDate(start.getDate() - 30);
            break;
        case 'thisMonth':
            start = new Date(now.getFullYear(), now.getMonth(), 1);
            break;
        case 'lastMonth':
            start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            end = new Date(now.getFullYear(), now.getMonth(), 0);
            break;
        default:
            start = new Date();
            start.setDate(start.getDate() - 30);
    }

    return {
        start: formatDate(start),
        end: formatDate(end),
    };
};

// Tarih formatı yardımcı fonksiyonu
const formatDate = (date: Date): string => {
    return date.toISOString().split('T')[0]; // YYYY-MM-DD formatı
};

// Hızlı link oluşturma formu
const form = useForm({
    url: '',
    is_multiple: false,
});

// Hızlı link oluşturma işlemi
const createQuickLink = () => {
    form.post(route('app.link.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

// Link kopyalama fonksiyonu
const copyToClipboard = async (text: string, linkId: number) => {
    try {
        await navigator.clipboard.writeText(text);
        copiedLinkId.value = linkId;
        setTimeout(() => {
            copiedLinkId.value = null;
        }, 2000);
    } catch (err) {
        console.error('Kopyalama başarısız oldu', err);
    }
};

// Seçilen tarih aralığına göre trafik verilerini alacak fonksiyon
const updateChartData = async () => {
    isChartLoading.value = true;

    try {
        // Burada backend'den yeni verileri alabiliriz
        // Şimdilik sadece mevcut verileri kullanıyoruz ve grafiği yeniden çiziyoruz
        // Gerçek uygulamada burada bir API çağrısı yapılır

        setTimeout(async () => {
            await renderTrafficChart();
            isChartLoading.value = false;
        }, 500);
    } catch (error) {
        console.error('Trafik verileri yüklenirken hata oluştu', error);
        isChartLoading.value = false;
    }
};

// Tarih aralığı değiştiğinde verileri güncelle
watch(selectedDateRange, updateChartData);

// Trafik grafiği
onMounted(() => {
    if (props.clicksData && Object.keys(props.clicksData).length > 0) {
        setTimeout(() => {
            renderTrafficChart();
            isChartLoading.value = false;
        }, 500);
    } else {
        isChartLoading.value = false;
    }
});

// Trafik grafiği oluşturma fonksiyonu
const renderTrafficChart = async () => {
    if (!props.clicksData) return;

    // Chart.js import
    const Chart = await import('chart.js/auto');
    const ctx = document.getElementById('trafficChart') as HTMLCanvasElement;
    if (!ctx) return;

    // Mevcut bir chart varsa temizle
    const chartInstance = Chart.default.getChart(ctx);
    if (chartInstance) {
        chartInstance.destroy();
    }

    const labels = Object.keys(props.clicksData).sort();
    const data = labels.map((date) => props.clicksData?.[date] || 0);

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
};

// Her bir link için domain + alias URL'ini oluştur
const getFullLinkUrl = (alias: string) => {
    return `${window.location.origin}/${alias}`;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col space-y-4 p-2 sm:p-4">
            <!-- Header alanı -->
            <div class="sm:mb-2">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 space-y-0.5 sm:mb-0">
                        <h2 class="text-xl font-semibold tracking-tight">Başlangıç</h2>
                        <p class="text-sm text-muted-foreground">Hoşgeldin, {{ name ?? 'Kullanıcı' }}. İşte hesabınızın son durumu.</p>
                    </div>

                    <div class="flex shrink-0 space-x-2">
                        <!-- Link Oluşturma Butonları -->
                        <div class="inline-flex">
                            <Button @click="isCreateModalOpen = true" class="h-7 rounded-r-none px-2 text-xs" size="sm">
                                <Plus class="h-3.5 w-3.5" />
                                <span>Hızlı Link Oluştur</span>
                            </Button>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="default" class="h-7 rounded-l-none border-l-0 px-2 text-xs" size="sm">
                                        <ChevronDown class="h-3.5 w-3.5" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem as-child>
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

                <!-- Hızlı Link Oluşturma Modalı -->
                <LinkCreateModal v-model:open="isCreateModalOpen" />
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

                                <canvas v-if="clicksData && Object.keys(clicksData).length > 0" id="trafficChart"></canvas>
                                <div v-else-if="!isChartLoading" class="flex h-full items-center justify-center">
                                    <p class="text-sm text-muted-foreground">Henüz trafik verisi bulunmuyor.</p>
                                </div>
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

                    <!-- Yaklaşan Etkinlikler bölümü -->
                    <Card>
                        <CardHeader className="px-4 py-3">
                            <CardTitle className="text-base">Yaklaşan Etkinlikler</CardTitle>
                            <CardDescription className="text-xs">Yakın zamanda planlanan ve yayınlanacak içerikler</CardDescription>
                        </CardHeader>
                        <CardContent className="px-4 py-3">
                            <div class="space-y-3">
                                <!-- Zamanlanmış Linkler -->
                                <div
                                    v-for="link in upcomingEvents?.scheduled_links"
                                    :key="'link' + link.id"
                                    class="flex items-center space-x-3 border-l-2 border-blue-500 pl-3"
                                >
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
                                            <p class="text-sm font-medium">{{ link.title }}</p>
                                            <span class="text-xs text-muted-foreground">{{ link.date }}</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">{{ link.description }}</p>
                                    </div>
                                </div>

                                <!-- Sona eren kampanyalar -->
                                <div
                                    v-for="campaign in upcomingEvents?.ending_campaigns"
                                    :key="'end' + campaign.id"
                                    class="flex items-center space-x-3 border-l-2 border-yellow-500 pl-3"
                                >
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
                                            <p class="text-sm font-medium">{{ campaign.title }}</p>
                                            <span class="text-xs text-muted-foreground">{{ campaign.relative_date }}</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">{{ campaign.description }}</p>
                                    </div>
                                </div>

                                <!-- Yakında başlayacak kampanyalar -->
                                <div
                                    v-for="campaign in upcomingEvents?.upcoming_campaigns"
                                    :key="'upcoming' + campaign.id"
                                    class="flex items-center space-x-3 border-l-2 border-green-500 pl-3"
                                >
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
                                            <p class="text-sm font-medium">{{ campaign.title }}</p>
                                            <span class="text-xs text-muted-foreground">{{ campaign.date }}</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">{{ campaign.description }}</p>
                                    </div>
                                </div>

                                <!-- Etkinlik yoksa mesaj göster -->
                                <div
                                    v-if="
                                        !upcomingEvents?.scheduled_links?.length &&
                                        !upcomingEvents?.ending_campaigns?.length &&
                                        !upcomingEvents?.upcoming_campaigns?.length
                                    "
                                    class="flex items-center justify-center py-3"
                                >
                                    <p class="text-xs text-muted-foreground">Yaklaşan etkinlik bulunmuyor.</p>
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

                            <div v-if="popularLinks.length === 0" class="flex items-center justify-center py-3">
                                <p class="text-xs text-muted-foreground">Henüz tıklama verisi bulunmuyor.</p>
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

                            <div v-if="recentLinks.length === 0" class="flex items-center justify-center py-3">
                                <p class="text-xs text-muted-foreground">Henüz link oluşturulmamış.</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
