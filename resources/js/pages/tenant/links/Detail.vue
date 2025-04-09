<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import { ArrowLeft, BarChart3, Eye, MonitorCheck, Plus, SquareArrowOutUpRight, Timer, Trash, XIcon } from 'lucide-vue-next';
import QRious from 'qrious';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

interface Space {
    id: string;
    name: string;
    color: string;
}

interface Pixel {
    id: number;
    name: string;
    type: string;
}

interface Stats {
    totalClicks: number;
    clicksMap: { [key: string]: number };
    topReferrers: { value: string; count: number }[];
    topCountries: { value: string; count: number }[];
    topBrowsers: { value: string; count: number }[];
    topPlatforms: { value: string; count: number }[];
}

interface Link {
    id: number;
    url: string;
    alias: string;
    title: string | null;
    description: string | null;
    image: string | null;
    disabled: boolean;
    clicks: number;
    expiration_clicks: number | null;
    expiration_url: string | null;
    ends_at: string | null;
    published_at: string | null;
    password: string | null;
    space: Space | null;
    target_type: number;
    goal: number | null;
    country_target: { key: string; value: string }[] | null;
    platform_target: { key: string; value: string }[] | null;
    language_target: { key: string; value: string }[] | null;
    rotation_target: { value: string }[] | null;
    utm_source: string | null;
    utm_medium: string | null;
    utm_campaign: string | null;
    utm_term: string | null;
    utm_content: string | null;
    pixels: Pixel[];
    created_at: string;
}

interface Props {
    link: Link;
    uniqueClicks: number;
    conversionRate: string;
    stats: Stats;
    spaces: Space[];
    pixels: Pixel[];
    countries: Record<string, string>;
    platforms: string[];
    languages: Record<string, { name: string; iso: string; rtl: number }>;
}

const props = defineProps<Props>();

// Modal visibility states
const editBasicModalOpen = ref(false);
const editExtrasModalOpen = ref(false);
const editUtmModalOpen = ref(false);
const freezeLinkModalOpen = ref(false);
const activateLinkModalOpen = ref(false);
const deleteLinkModalOpen = ref(false);

// Inline edit mode states
const isEditingTarget = ref(false);

// Forms
const basicForm = useForm({
    alias: props.link.alias,
    space_id: props.link.space?.id || '',
    goal: props.link.goal,
    goal_enabled: !!props.link.goal,
    published_at_date: props.link.published_at ? new Date(props.link.published_at).toISOString().split('T')[0] : '',
    published_at_time: props.link.published_at ? new Date(props.link.published_at).toISOString().split('T')[1].substr(0, 5) : '',
});

const extrasForm = useForm({
    password: props.link.password ? '••••••••' : '', // Şifreyi yıldızla göster
    password_hint: props.link.password_hint || '', // Yeni eklenen password_hint
    password_changed: false, // Şifre değişikliğini izlemek için
    expiration_clicks: props.link.expiration_clicks,
    expiration_date: props.link.ends_at ? new Date(props.link.ends_at).toISOString().split('T')[0] : '',
    expiration_time: props.link.ends_at ? new Date(props.link.ends_at).toISOString().split('T')[1].substr(0, 5) : '',
    expiration_url: props.link.expiration_url || '',
    pixel_ids: props.link.pixels.map((pixel) => pixel.id),
    password_enabled: !!props.link.password,
    expiration_clicks_enabled: !!props.link.expiration_clicks,
    expiration_date_enabled: !!props.link.ends_at,
    expiration_url_enabled: !!props.link.expiration_url,
    pixels_enabled: props.link.pixels && props.link.pixels.length > 0,
});

const utmForm = useForm({
    utm_source: props.link.utm_source || '',
    utm_medium: props.link.utm_medium || '',
    utm_campaign: props.link.utm_campaign || '',
    utm_term: props.link.utm_term || '',
    utm_content: props.link.utm_content || '',
    utm_campaign_enabled: !!props.link.utm_campaign,
    utm_term_enabled: !!props.link.utm_term,
    utm_content_enabled: !!props.link.utm_content,
});

const targetForm = useForm({
    target_type: props.link.target_type,
    country_target: props.link.country_target || [],
    platform_target: props.link.platform_target || [],
    language_target: props.link.language_target || [],
    rotation_target: props.link.rotation_target || [],
});

// Format URL for better readability
const formatUrl = (url: string) => {
    return url.replace(/^https?:\/\//i, '');
};

// Check if link is active
const isLinkActive = (link: Link) => {
    const isExpired = link.ends_at ? new Date(link.ends_at) < new Date() : false;
    const isClickLimitReached = link.expiration_clicks ? link.clicks >= link.expiration_clicks : false;
    return !link.disabled && !isExpired && !isClickLimitReached;
};

// Detail.vue dosyasında aşağıdaki kodu diğer ref tanımlamalarının yanına ekleyelim:
const isGoalEnabled = ref(!!props.link.goal);

// submitBasicForm fonksiyonunu güncelleyelim
const submitBasicForm = () => {
    // Alias alanı kontrolü
    if (!basicForm.alias || basicForm.alias.trim() === '') {
        return;
    }

    basicForm.goal_enabled = isGoalEnabled.value;
    if (!isGoalEnabled.value) {
        basicForm.goal = null;
    }

    // Sadece linkin durumu pasif ise ve tarih/saat belirtilmişse published_at değerini ayarla
    if (props.link.disabled && basicForm.published_at_date && basicForm.published_at_time) {
        basicForm.published_at = basicForm.published_at_date + ' ' + basicForm.published_at_time;
    } else {
        // Aktif linkler veya tarih/saat belirtilmemiş pasif linkler için published_at değerini temizle
        basicForm.published_at = null;
    }

    basicForm.post(route('app.link.update.basic', props.link.id), {
        onSuccess: () => {
            editBasicModalOpen.value = false;
        },
        onError: (errors) => {
            // Hata mesajlarını görüntüle - basit bir alert olarak
            if (Object.keys(errors).length > 0) {
                let errorMessages = [];
                for (const field in errors) {
                    errorMessages.push(errors[field]);
                }
                alert('Hata: ' + errorMessages.join('\n'));
            }
        },
    });
};

// Ek özellikler için aktiflik durumları
const isPasswordEnabled = ref(!!props.link.password);
const isExpirationDateEnabled = ref(!!props.link.ends_at);
const isExpirationClicksEnabled = ref(!!props.link.expiration_clicks);
const isExpirationUrlEnabled = ref(!!props.link.expiration_url);
const isPixelsEnabled = ref(props.link.pixels && props.link.pixels.length > 0);

// Geçici seçim için state
const selectedPixel = ref(null);

// Hesaplanan özellik: Henüz seçilmemiş pikseller
const availablePixels = computed(() => {
    return props.pixels.filter((pixel) => !extrasForm.pixel_ids.includes(pixel.id));
});

// Piksel adını ID'ye göre bulan yardımcı fonksiyon
const findPixelName = (pixelId) => {
    const pixel = props.pixels.find((p) => p.id === pixelId);
    return pixel ? pixel.name : 'Bilinmeyen Piksel';
};

// Seçilen pikseli ekleyen fonksiyon
const addSelectedPixel = (value) => {
    if (isPixelsEnabled && value && !extrasForm.pixel_ids.includes(value)) {
        extrasForm.pixel_ids.push(value);
    }
    selectedPixel.value = null; // Seçimi sıfırla
};

// Piksel kaldırma fonksiyonu
const removePixel = (pixelId) => {
    extrasForm.pixel_ids = extrasForm.pixel_ids.filter((id) => id !== pixelId);
};

// Şifre değiştirildiğinde bu değeri true olarak ayarla
const onPasswordChange = () => {
    if (extrasForm.password !== '••••••••') {
        extrasForm.password_changed = true;
    }
};

const submitExtrasForm = () => {
    // Şifre değişiklikleri kontrolü
    if (!isPasswordEnabled.value) {
        extrasForm.password = null;
        extrasForm.password_hint = null;
    }
    // Şifre açık ama değiştirilmediyse ve orijinal şifre varsa (yıldızlarla gösterilen)
    else if (!extrasForm.password_changed && props.link.password && extrasForm.password === '••••••••') {
        delete extrasForm.password; // Şifreyi gönderme, böylece mevcut şifre korunur
    }

    // Diğer switch kontrolleri - mevcut kodunuz
    if (!isExpirationClicksEnabled.value) {
        extrasForm.expiration_clicks = null;
    }

    if (!isExpirationDateEnabled.value) {
        extrasForm.expiration_date = null;
        extrasForm.expiration_time = null;
    }

    if (!isExpirationUrlEnabled.value) {
        extrasForm.expiration_url = null;
    }

    if (!isPixelsEnabled.value) {
        extrasForm.pixel_ids = [];
    }

    // Switch durumlarını forma ekle
    extrasForm.password_enabled = isPasswordEnabled.value;
    extrasForm.expiration_clicks_enabled = isExpirationClicksEnabled.value;
    extrasForm.expiration_date_enabled = isExpirationDateEnabled.value;
    extrasForm.expiration_url_enabled = isExpirationUrlEnabled.value;
    extrasForm.pixels_enabled = isPixelsEnabled.value;

    // Form gönderimi
    extrasForm.post(route('app.link.update.extra', props.link.id), {
        preserveScroll: true,
        onSuccess: () => {
            editExtrasModalOpen.value = false;
        },
    });
};

// Modal açılırken şifre değiştirilme durumunu sıfırla
watch(
    () => editExtrasModalOpen.value,
    (newValue) => {
        if (newValue) {
            extrasForm.password = props.link.password ? '••••••••' : '';
            extrasForm.password_hint = props.link.password_hint || '';
            extrasForm.password_changed = false;
        }
    },
);

// UTM Parametreleri için switch referansları
const isUtmCampaignEnabled = ref(!!props.link.utm_campaign);
const isUtmTermEnabled = ref(!!props.link.utm_term);
const isUtmContentEnabled = ref(!!props.link.utm_content);

// Kampanya adı etkinleştirildiğinde otomatik olarak kaynak ve medyada etkinleşecek
const isUtmSourceActive = computed(() => isUtmCampaignEnabled.value);
const isUtmMediumActive = computed(() => isUtmCampaignEnabled.value);

const submitUtmForm = () => {
    // Form değerlerini switch durumuna göre ayarla
    if (!isUtmCampaignEnabled.value) {
        utmForm.utm_campaign = null;
        utmForm.utm_source = null;
        utmForm.utm_medium = null;
    }

    if (!isUtmTermEnabled.value) {
        utmForm.utm_term = null;
    }

    if (!isUtmContentEnabled.value) {
        utmForm.utm_content = null;
    }

    // Switch durumlarını forma ekle
    utmForm.utm_campaign_enabled = isUtmCampaignEnabled.value;
    utmForm.utm_term_enabled = isUtmTermEnabled.value;
    utmForm.utm_content_enabled = isUtmContentEnabled.value;

    // Form gönderimi
    utmForm.post(route('app.link.update.utm', props.link.id), {
        preserveScroll: true,
        onSuccess: () => {
            editUtmModalOpen.value = false;
        },
    });
};

// Hedefleme türleri için etkinleştirme durumları
const countryTargetEnabled = ref(!!props.link.country_target && props.link.country_target.length > 0);
const platformTargetEnabled = ref(!!props.link.platform_target && props.link.platform_target.length > 0);
const languageTargetEnabled = ref(!!props.link.language_target && props.link.language_target.length > 0);
const rotationTargetEnabled = ref(!!props.link.rotation_target && props.link.rotation_target.length > 0);

// Hedefleme için düzenleme modu
const startTargetEdit = () => {
    isEditingTarget.value = true;

    // Hedefleme türlerine göre varsayılan değerleri atayalım
    targetForm.target_type = props.link.target_type;

    // Checkbox durumlarını ayarla
    countryTargetEnabled.value = !!props.link.country_target && props.link.country_target.length > 0;
    platformTargetEnabled.value = !!props.link.platform_target && props.link.platform_target.length > 0;
    languageTargetEnabled.value = !!props.link.language_target && props.link.language_target.length > 0;
    rotationTargetEnabled.value = !!props.link.rotation_target && props.link.rotation_target.length > 0;

    // Ülke hedefleme
    if (props.link.country_target && props.link.country_target.length) {
        targetForm.country_target = [...props.link.country_target];
    } else {
        targetForm.country_target = [{ key: '', value: '' }];
    }

    // Platform hedefleme
    if (props.link.platform_target && props.link.platform_target.length) {
        targetForm.platform_target = [...props.link.platform_target];
    } else {
        targetForm.platform_target = [{ key: '', value: '' }];
    }

    // Dil hedefleme
    if (props.link.language_target && props.link.language_target.length) {
        targetForm.language_target = [...props.link.language_target];
    } else {
        targetForm.language_target = [{ key: '', value: '' }];
    }

    // Rotasyon hedefleme
    if (props.link.rotation_target && props.link.rotation_target.length) {
        targetForm.rotation_target = [...props.link.rotation_target];
    } else {
        targetForm.rotation_target = [{ value: '' }];
    }
};

// Hedefleme düzenlemesini iptal et
const cancelTargetEdit = () => {
    isEditingTarget.value = false;
    targetForm.reset();
};

// Hedefleme düzenlemesini kaydet
const submitTargetForm = () => {
    // targetForm değerlerini güncelleyelim
    targetForm.target_type = 0;

    // Checkbox'a göre hedefleme verilerini güncelleyelim
    if (!countryTargetEnabled.value) {
        targetForm.country_target = [];
    } else {
        targetForm.country_target = targetForm.country_target.filter((row) => row.key && row.value);
    }

    // Diğer hedefleme türleri için benzer loglar
    if (!platformTargetEnabled.value) {
        targetForm.platform_target = [];
    } else {
        targetForm.platform_target = targetForm.platform_target.filter((row) => row.key && row.value);
    }

    if (!languageTargetEnabled.value) {
        targetForm.language_target = [];
    } else {
        targetForm.language_target = targetForm.language_target.filter((row) => row.key && row.value);
    }

    if (!rotationTargetEnabled.value) {
        targetForm.rotation_target = [];
    } else {
        targetForm.rotation_target = targetForm.rotation_target.filter((row) => row.value);
    }

    // En az bir hedefleme varsa target_type'ı 1 yap
    if (
        targetForm.country_target.length > 0 ||
        targetForm.platform_target.length > 0 ||
        targetForm.language_target.length > 0 ||
        targetForm.rotation_target.length > 0
    ) {
        targetForm.target_type = 1;
    }

    // Form gönderimi
    targetForm.post(route('app.link.update.target', props.link.id), {
        onSuccess: () => {
            isEditingTarget.value = false;
        },
    });
};

// Önizleme için sabit boyut
const previewSize = 200; // Önizleme için sabit boyut

// İndirme için seçilebilir boyutlar
const qrSize = ref(600);
const qrSizeOptions = [600, 1200, 2400];

// URL için computed property
const qrCodeUrl = computed(() => `https://kampanya.test/${props.link.alias}`);

// QR kod için canvas ref
const qrCanvasRef = ref(null);
let qr = null;

// QR kodu oluşturma fonksiyonu
const generateQRCode = () => {
    if (!qrCanvasRef.value) return;

    // QRious ile QR kodu oluşturma - önizleme için sabit boyut kullanıyoruz
    qr = new QRious({
        element: qrCanvasRef.value,
        value: qrCodeUrl.value,
        size: previewSize, // Önizleme için sabit boyut
        backgroundAlpha: 1, // Tam opak arka plan
        foreground: '#000000',
        background: '#ffffff', // Beyaz arka plan
        level: 'Q', // Error correction level (L, M, Q, H)
    });
};

// QR kod indirme fonksiyonu
const downloadQRCode = () => {
    if (!qrCanvasRef.value || !qr) {
        console.error('QR kod canvas elementi bulunamadı');
        return;
    }

    // Seçilen boyutta yeni bir QR kodu oluştur
    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = qrSize.value;
    tempCanvas.height = qrSize.value;

    // Yeni QR kodu oluştur
    const tempQr = new QRious({
        element: tempCanvas,
        value: qrCodeUrl.value,
        size: qrSize.value,
        backgroundAlpha: 1, // Tam opak arka plan
        foreground: '#000000',
        background: '#ffffff', // Beyaz arka plan
        level: 'Q',
    });

    // Oluşturulan QR kodu indir
    const url = tempCanvas.toDataURL('image/png');
    const a = document.createElement('a');
    a.href = url;
    a.download = `qrcode-${props.link.alias}-${qrSize.value}x${qrSize.value}.png`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
};

// İlk yükleme
onMounted(() => {
    generateQRCode();
});

const toggleLinkStatus = () => {
    useForm().post(route('app.link.toggle', props.link.id), {
        onSuccess: () => {
            freezeLinkModalOpen.value = false;
            activateLinkModalOpen.value = false;
        },
    });
};

const deleteLink = () => {
    useForm().delete(route('app.link.destroy', props.link.id), {
        onSuccess: () => {
            window.location.href = route('app.links');
        },
    });
};

const clicksChart = ref(null);
let chart = null;

onMounted(() => {
    if (props.stats && props.stats.clicksMap && clicksChart.value) {
        const ctx = clicksChart.value.getContext('2d');

        // Tıklama verisini hazırla
        const labels = Object.keys(props.stats.clicksMap);
        const data = Object.values(props.stats.clicksMap);

        // Tıklama grafiğini oluştur
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tıklamalar',
                        data: data,
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
                },
            },
        });
    }
});

// Component unmount edildiğinde chart'ı temizle
onUnmounted(() => {
    if (chart) {
        chart.destroy();
        chart = null;
    }
});

const isLinkDisabled = computed(() => !!props.link.disabled);
const today = ref(new Date().toISOString().split('T')[0]);

watch(
    () => editBasicModalOpen.value,
    (newValue) => {
        if (newValue) {
            // Form verilerini ayarla
            basicForm.alias = props.link.alias;
            basicForm.space_id = props.link.space?.id || '';
            basicForm.goal = props.link.goal;

            // Sadece pasif linkler için published_at değerlerini göster
            if (props.link.disabled) {
                // Link pasifse, varsa published_at değerlerini göster
                basicForm.published_at_date = props.link.published_at ? new Date(props.link.published_at).toISOString().split('T')[0] : '';
                basicForm.published_at_time = props.link.published_at
                    ? new Date(props.link.published_at).toISOString().split('T')[1].substr(0, 5)
                    : '';
            } else {
                // Link aktifse published_at alanlarını boşalt
                basicForm.published_at_date = '';
                basicForm.published_at_time = '';
            }
        }
    },
);
</script>
<template>
    <AppLayout>
        <Head title="Link Detayı" />

        <LinksLayout>
            <div class="w-full space-y-6">
                <div class="flex w-full items-center justify-between">
                    <HeadingSmall title="Link Detayı" :description="'Link bilgilerini görüntüleyin ve düzenleyin'" />

                    <Button as-child variant="outline" size="sm" class="h-8">
                        <Link :href="route('app.links')" class="flex items-center gap-1.5">
                            <ArrowLeft class="h-4 w-4" />
                            <span>Geri Dön</span>
                        </Link>
                    </Button>
                </div>

                <!-- Ana İçerik -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Sol kolon (Link Bilgileri, Hedefleme ve İstatistikler) -->
                    <div class="space-y-6 md:col-span-2">
                        <!-- İstatistikler -->
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div class="rounded-lg border bg-card p-3 text-center">
                                <h3 class="text-xl font-bold">{{ props.link.clicks }}</h3>
                                <p class="text-xs text-muted-foreground">Toplam Tıklama</p>
                            </div>
                            <div class="rounded-lg border bg-card p-3 text-center">
                                <h3 class="text-xl font-bold">{{ props.uniqueClicks }}</h3>
                                <p class="text-xs text-muted-foreground">Tekil Tıklama</p>
                            </div>
                            <div class="rounded-lg border bg-card p-3 text-center">
                                <h3 class="text-xl font-bold">{{ props.conversionRate }}</h3>
                                <p class="text-xs text-muted-foreground">Dönüşüm Oranı</p>
                            </div>
                            <div class="rounded-lg border bg-card p-3 text-center">
                                <h3 class="text-xl font-bold">{{ props.uniqueClicks }} / {{ props.link.goal || 0 }}</h3>
                                <p class="text-xs text-muted-foreground">Hedef</p>
                            </div>
                        </div>

                        <!-- Genel Bilgiler -->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-base font-medium">Genel Bilgiler</CardTitle>
                                <Button variant="ghost" size="sm" @click="editBasicModalOpen = true" class="h-7 text-xs">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        fill="currentColor"
                                        class="bi bi-pencil-square"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
                                        />
                                    </svg>
                                    <span class="ml-1.5">Düzenle</span>
                                </Button>
                            </CardHeader>
                            <CardContent class="text-sm">
                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Durum:</div>
                                    <div class="col-span-2">
                                        <span v-if="props.link.disabled" class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-800"
                                            >Pasif</span
                                        >
                                        <span v-else class="rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-800">Aktif</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Kısa Link:</div>
                                    <div class="col-span-2">
                                        <a :href="`https://kampanya.test/${props.link.alias}`" target="_blank" class="text-blue-600 hover:underline">
                                            https://kampanya.test/{{ props.link.alias }}
                                        </a>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Orijinal URL:</div>
                                    <div class="col-span-2">
                                        <a :href="props.link.url" target="_blank" class="text-blue-600 hover:underline">
                                            {{ props.link.url }}
                                        </a>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Takma Ad:</div>
                                    <div class="col-span-2">{{ props.link.alias || 'Tanımlanmamış' }}</div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Alan:</div>
                                    <div class="col-span-2">{{ props.link.space?.name || 'Tanımlanmamış' }}</div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Hedeflenen Tıklama:</div>
                                    <div class="col-span-2">{{ props.link.goal || 'Tanımlanmamış' }}</div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Oluşturulma Tarihi:</div>
                                    <div class="col-span-2">{{ new Date(props.link.created_at).toLocaleString('tr-TR') }}</div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 py-1">
                                    <div class="font-medium">Yayınlama Tarihi:</div>
                                    <div class="col-span-2">
                                        {{ props.link.published_at ? new Date(props.link.published_at).toLocaleString('tr-TR') : 'Tanımlanmamış' }}
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Hedefleme kartını buraya taşıyalım -->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-base font-medium">Hedefleme</CardTitle>
                                <Button v-if="!isEditingTarget" variant="ghost" size="sm" @click="startTargetEdit" class="h-7 text-xs">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        fill="currentColor"
                                        class="bi bi-pencil-square"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
                                        />
                                    </svg>
                                    <span class="ml-1.5">Düzenle</span>
                                </Button>
                            </CardHeader>
                            <CardContent class="text-sm">
                                <div v-if="!isEditingTarget">
                                    <!-- Her hedefleme türü için ayrı bölümler gösterelim -->
                                    <div v-if="props.link.country_target && props.link.country_target.length > 0" class="mb-4">
                                        <p class="mb-2 font-medium">Ülke bazlı hedefleme:</p>
                                        <div
                                            v-for="(target, index) in props.link.country_target"
                                            :key="`country-${index}`"
                                            class="mb-2 rounded border p-2"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="font-medium">{{ countries[target.key] || target.key }}</span>
                                                    <span class="text-muted-foreground">→</span>
                                                    <a :href="target.value" target="_blank" class="text-blue-600 hover:underline">{{
                                                        formatUrl(target.value)
                                                    }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="props.link.platform_target && props.link.platform_target.length > 0" class="mb-4">
                                        <p class="mb-2 font-medium">Platform bazlı hedefleme:</p>
                                        <div
                                            v-for="(target, index) in props.link.platform_target"
                                            :key="`platform-${index}`"
                                            class="mb-2 rounded border p-2"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="font-medium">{{ target.key }}</span>
                                                    <span class="text-muted-foreground">→</span>
                                                    <a :href="target.value" target="_blank" class="text-blue-600 hover:underline">{{
                                                        formatUrl(target.value)
                                                    }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="props.link.language_target && props.link.language_target.length > 0" class="mb-4">
                                        <p class="mb-2 font-medium">Dil bazlı hedefleme:</p>
                                        <div
                                            v-for="(target, index) in props.link.language_target"
                                            :key="`language-${index}`"
                                            class="mb-2 rounded border p-2"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="font-medium">{{ languages[target.key]?.name || target.key }}</span>
                                                    <span class="text-muted-foreground">→</span>
                                                    <a :href="target.value" target="_blank" class="text-blue-600 hover:underline">{{
                                                        formatUrl(target.value)
                                                    }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="props.link.rotation_target && props.link.rotation_target.length > 0" class="mb-4">
                                        <p class="mb-2 font-medium">Rotasyon bazlı hedefleme:</p>
                                        <div
                                            v-for="(target, index) in props.link.rotation_target"
                                            :key="`rotation-${index}`"
                                            class="mb-2 rounded border p-2"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="font-medium">URL {{ index + 1 }}</span>
                                                    <span class="text-muted-foreground">→</span>
                                                    <a :href="target.value" target="_blank" class="text-blue-600 hover:underline">{{
                                                        formatUrl(target.value)
                                                    }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        v-if="
                                            !props.link.country_target?.length &&
                                            !props.link.platform_target?.length &&
                                            !props.link.language_target?.length &&
                                            !props.link.rotation_target?.length
                                        "
                                        class="text-muted-foreground"
                                    >
                                        Hedefleme yapılandırılmamış.
                                    </div>
                                </div>

                                <!-- Hedefleme Düzenleme Formu -->
                                <div v-if="isEditingTarget" class="space-y-4">
                                    <!-- Her bir hedefleme türü için ayrı bölümler -->
                                    <div class="mb-4">
                                        <div class="mb-2 flex items-center space-x-2">
                                            <Checkbox id="enable-country-targeting" v-model:checked="countryTargetEnabled" />
                                            <Label for="enable-country-targeting" class="text-sm font-medium"> Ülke bazlı hedefleme </Label>
                                        </div>

                                        <!-- Ülke Hedefleme -->
                                        <div v-if="countryTargetEnabled" class="ml-6 space-y-4">
                                            <div v-for="(row, index) in targetForm.country_target" :key="`country-${index}`" class="flex gap-2">
                                                <div class="w-2/5">
                                                    <div class="relative">
                                                        <Select v-model="row.key">
                                                            <SelectTrigger>
                                                                <SelectValue :placeholder="'Ülke seçiniz'" />
                                                            </SelectTrigger>
                                                            <SelectContent>
                                                                <SelectGroup>
                                                                    <SelectItem v-for="(name, code) in countries" :key="code" :value="code">
                                                                        {{ name }}
                                                                    </SelectItem>
                                                                </SelectGroup>
                                                            </SelectContent>
                                                        </Select>
                                                        <Button
                                                            v-if="row.key"
                                                            type="button"
                                                            variant="ghost"
                                                            class="absolute right-8 top-0 h-full px-2"
                                                            @click="row.key = ''"
                                                        >
                                                            <span class="sr-only">Temizle</span>
                                                            <XIcon class="h-4 w-4 text-muted-foreground" />
                                                        </Button>
                                                    </div>
                                                </div>
                                                <div class="relative w-2/5">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <LinkIcon class="h-4 w-4 text-muted-foreground" />
                                                    </div>
                                                    <Input v-model="row.value" type="url" placeholder="https://..." class="pl-10" />
                                                </div>
                                                <div class="w-1/5">
                                                    <Button
                                                        type="button"
                                                        variant="destructive"
                                                        class="w-full"
                                                        @click="targetForm.country_target.splice(index, 1)"
                                                    >
                                                        <Trash class="h-3.5 w-3.5" />
                                                    </Button>
                                                </div>
                                            </div>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                size="sm"
                                                class="flex items-center gap-1"
                                                @click="targetForm.country_target.push({ key: '', value: '' })"
                                            >
                                                <Plus class="h-3.5 w-3.5" />
                                                <span>Yeni Ülke Ekle</span>
                                            </Button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="mb-2 flex items-center space-x-2">
                                            <Checkbox id="enable-platform-targeting" v-model:checked="platformTargetEnabled" />
                                            <Label for="enable-platform-targeting" class="text-sm font-medium"> Platform bazlı hedefleme </Label>
                                        </div>

                                        <!-- Platform Hedefleme -->
                                        <div v-if="platformTargetEnabled" class="ml-6 space-y-4">
                                            <div v-for="(row, index) in targetForm.platform_target" :key="`platform-${index}`" class="flex gap-2">
                                                <div class="w-2/5">
                                                    <div class="relative">
                                                        <Select v-model="row.key">
                                                            <SelectTrigger>
                                                                <SelectValue :placeholder="'Platform seçiniz'" />
                                                            </SelectTrigger>
                                                            <SelectContent>
                                                                <SelectGroup>
                                                                    <SelectItem v-for="platform in platforms" :key="platform" :value="platform">
                                                                        {{ platform }}
                                                                    </SelectItem>
                                                                </SelectGroup>
                                                            </SelectContent>
                                                        </Select>
                                                        <Button
                                                            v-if="row.key"
                                                            type="button"
                                                            variant="ghost"
                                                            class="absolute right-8 top-0 h-full px-2"
                                                            @click="row.key = ''"
                                                        >
                                                            <span class="sr-only">Temizle</span>
                                                            <XIcon class="h-4 w-4 text-muted-foreground" />
                                                        </Button>
                                                    </div>
                                                </div>
                                                <div class="relative w-2/5">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <LinkIcon class="h-4 w-4 text-muted-foreground" />
                                                    </div>
                                                    <Input v-model="row.value" type="url" placeholder="https://..." class="pl-10" />
                                                </div>
                                                <div class="w-1/5">
                                                    <Button
                                                        type="button"
                                                        variant="destructive"
                                                        class="w-full"
                                                        @click="targetForm.platform_target.splice(index, 1)"
                                                    >
                                                        <Trash class="h-3.5 w-3.5" />
                                                    </Button>
                                                </div>
                                            </div>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                size="sm"
                                                class="flex items-center gap-1"
                                                @click="targetForm.platform_target.push({ key: '', value: '' })"
                                            >
                                                <Plus class="h-3.5 w-3.5" />
                                                <span>Yeni Platform Ekle</span>
                                            </Button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="mb-2 flex items-center space-x-2">
                                            <Checkbox id="enable-language-targeting" v-model:checked="languageTargetEnabled" />
                                            <Label for="enable-language-targeting" class="text-sm font-medium"> Dil bazlı hedefleme </Label>
                                        </div>

                                        <!-- Dil Hedefleme -->
                                        <div v-if="languageTargetEnabled" class="ml-6 space-y-4">
                                            <div v-for="(row, index) in targetForm.language_target" :key="`language-${index}`" class="flex gap-2">
                                                <div class="w-2/5">
                                                    <div class="relative">
                                                        <Select v-model="row.key">
                                                            <SelectTrigger>
                                                                <SelectValue :placeholder="'Dil seçiniz'" />
                                                            </SelectTrigger>
                                                            <SelectContent>
                                                                <SelectGroup>
                                                                    <SelectItem v-for="(data, code) in languages" :key="code" :value="code">
                                                                        {{ data.name }} ({{ data.iso }})
                                                                    </SelectItem>
                                                                </SelectGroup>
                                                            </SelectContent>
                                                        </Select>
                                                        <Button
                                                            v-if="row.key"
                                                            type="button"
                                                            variant="ghost"
                                                            class="absolute right-8 top-0 h-full px-2"
                                                            @click="row.key = ''"
                                                        >
                                                            <span class="sr-only">Temizle</span>
                                                            <XIcon class="h-4 w-4 text-muted-foreground" />
                                                        </Button>
                                                    </div>
                                                </div>
                                                <div class="relative w-2/5">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <LinkIcon class="h-4 w-4 text-muted-foreground" />
                                                    </div>
                                                    <Input v-model="row.value" type="url" placeholder="https://..." class="pl-10" />
                                                </div>
                                                <div class="w-1/5">
                                                    <Button
                                                        type="button"
                                                        variant="destructive"
                                                        class="w-full"
                                                        @click="targetForm.language_target.splice(index, 1)"
                                                    >
                                                        <Trash class="h-3.5 w-3.5" />
                                                    </Button>
                                                </div>
                                            </div>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                size="sm"
                                                class="flex items-center gap-1"
                                                @click="targetForm.language_target.push({ key: '', value: '' })"
                                            >
                                                <Plus class="h-3.5 w-3.5" />
                                                <span>Yeni Dil Ekle</span>
                                            </Button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="mb-2 flex items-center space-x-2">
                                            <Checkbox id="enable-rotation-targeting" v-model:checked="rotationTargetEnabled" />
                                            <Label for="enable-rotation-targeting" class="text-sm font-medium"> Rotasyon bazlı hedefleme </Label>
                                        </div>

                                        <!-- Rotasyon Hedefleme -->
                                        <div v-if="rotationTargetEnabled" class="ml-6 space-y-4">
                                            <div v-for="(row, index) in targetForm.rotation_target" :key="`rotation-${index}`" class="flex gap-2">
                                                <div class="relative w-4/5">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <LinkIcon class="h-4 w-4 text-muted-foreground" />
                                                    </div>
                                                    <Input v-model="row.value" type="url" placeholder="https://..." class="pl-10" />
                                                </div>
                                                <div class="w-1/5">
                                                    <Button
                                                        type="button"
                                                        variant="destructive"
                                                        class="w-full"
                                                        @click="targetForm.rotation_target.splice(index, 1)"
                                                    >
                                                        <Trash class="h-3.5 w-3.5" />
                                                    </Button>
                                                </div>
                                            </div>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                size="sm"
                                                class="flex items-center gap-1"
                                                @click="targetForm.rotation_target.push({ value: '' })"
                                            >
                                                <Plus class="h-3.5 w-3.5" />
                                                <span>Yeni Rotasyon Ekle</span>
                                            </Button>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-end space-x-2">
                                        <Button type="button" variant="outline" @click="cancelTargetEdit"> İptal </Button>
                                        <Button type="button" @click="submitTargetForm" :disabled="targetForm.processing"> Kaydet </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Son 7 Gün İstatistikleri -->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-base font-medium">Son 7 Gün İstatistikleri</CardTitle>
                                <Button variant="outline" size="sm">
                                    <Link :href="route('app.link.stats', link.id)" class="flex items-center gap-1.5">
                                        <BarChart3 class="h-3.5 w-3.5" />
                                        <span>Tüm İstatistikler</span>
                                    </Link>
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div v-if="props.stats.totalClicks > 0" class="space-y-4">
                                    <!-- Toplam Tıklama Grafiği -->
                                    <div>
                                        <h3 class="mb-2 text-sm font-medium">Tıklama Grafiği</h3>
                                        <div class="h-48 w-full">
                                            <canvas ref="clicksChart"></canvas>
                                        </div>
                                    </div>

                                    <!-- İstatistikler Grid -->
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <!-- Yönlendirenler -->
                                        <div>
                                            <h3 class="mb-2 text-sm font-medium">En Çok Ziyaret Edenler</h3>
                                            <div class="rounded-md border">
                                                <div
                                                    v-for="(referrer, index) in props.stats.topReferrers"
                                                    :key="index"
                                                    class="flex items-center justify-between border-b p-2 last:border-0"
                                                >
                                                    <span class="text-sm">{{ referrer.value || 'Doğrudan' }}</span>
                                                    <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">{{
                                                        referrer.count
                                                    }}</span>
                                                </div>
                                                <div v-if="props.stats.topReferrers.length === 0" class="p-3 text-center text-sm text-gray-500">
                                                    Henüz veri yok
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ülkeler -->
                                        <div>
                                            <h3 class="mb-2 text-sm font-medium">En Çok Ziyaret Eden Ülkeler</h3>
                                            <div class="rounded-md border">
                                                <div
                                                    v-for="(country, index) in props.stats.topCountries"
                                                    :key="index"
                                                    class="flex items-center justify-between border-b p-2 last:border-0"
                                                >
                                                    <span class="text-sm">{{ country.value }}</span>
                                                    <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">{{
                                                        country.count
                                                    }}</span>
                                                </div>
                                                <div v-if="props.stats.topCountries.length === 0" class="p-3 text-center text-sm text-gray-500">
                                                    Henüz veri yok
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tarayıcılar -->
                                        <div>
                                            <h3 class="mb-2 text-sm font-medium">En Çok Kullanılan Tarayıcılar</h3>
                                            <div class="rounded-md border">
                                                <div
                                                    v-for="(browser, index) in props.stats.topBrowsers"
                                                    :key="index"
                                                    class="flex items-center justify-between border-b p-2 last:border-0"
                                                >
                                                    <span class="text-sm">{{ browser.value }}</span>
                                                    <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">{{
                                                        browser.count
                                                    }}</span>
                                                </div>
                                                <div v-if="props.stats.topBrowsers.length === 0" class="p-3 text-center text-sm text-gray-500">
                                                    Henüz veri yok
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Platformlar -->
                                        <div>
                                            <h3 class="mb-2 text-sm font-medium">En Çok Kullanılan Platformlar</h3>
                                            <div class="rounded-md border">
                                                <div
                                                    v-for="(platform, index) in props.stats.topPlatforms"
                                                    :key="index"
                                                    class="flex items-center justify-between border-b p-2 last:border-0"
                                                >
                                                    <span class="text-sm">{{ platform.value }}</span>
                                                    <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">{{
                                                        platform.count
                                                    }}</span>
                                                </div>
                                                <div v-if="props.stats.topPlatforms.length === 0" class="p-3 text-center text-sm text-gray-500">
                                                    Henüz veri yok
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="p-6 text-center">
                                    <p class="text-sm text-muted-foreground">Henüz bu link için istatistik verisi bulunmamaktadır.</p>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Sağ kolon (Ek Bilgiler, UTM, İşlemler) -->
                    <div class="space-y-6">
                        <!-- İşlemler -->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-base font-medium">İşlemler</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-2 gap-3">
                                    <Button as-child size="sm" class="h-7 w-full bg-orange-500 text-xs hover:bg-orange-600">
                                        <a
                                            :href="`https://kampanya.test/${props.link.alias}`"
                                            target="_blank"
                                            class="flex items-center justify-center gap-1.5"
                                        >
                                            <SquareArrowOutUpRight class="h-3.5 w-3.5" />
                                            <span>Linke Git</span>
                                        </a>
                                    </Button>

                                    <Button as-child size="sm" class="h-7 w-full bg-blue-500 text-xs hover:bg-blue-600">
                                        <a
                                            :href="`https://kampanya.test/${props.link.alias}?preview=true`"
                                            target="_blank"
                                            class="flex items-center justify-center gap-1.5"
                                        >
                                            <Eye class="h-3.5 w-3.5" />
                                            <span>Linki Önizle</span>
                                        </a>
                                    </Button>

                                    <Button
                                        v-if="props.link.disabled"
                                        size="sm"
                                        class="h-7 w-full bg-green-600 text-xs hover:bg-green-700"
                                        @click="activateLinkModalOpen = true"
                                    >
                                        <MonitorCheck class="h-3.5 w-3.5" />
                                        <span>Linki Aktifleştir</span>
                                    </Button>
                                    <Button
                                        v-else
                                        size="sm"
                                        class="h-7 w-full bg-gray-700 text-xs hover:bg-gray-800"
                                        @click="freezeLinkModalOpen = true"
                                    >
                                        <Timer class="h-3.5 w-3.5" />
                                        <span>Linki Dondur</span>
                                    </Button>

                                    <Button size="sm" class="h-7 w-full bg-red-600 text-xs hover:bg-red-700" @click="deleteLinkModalOpen = true">
                                        <Trash class="h-3.5 w-3.5" />
                                        <span>Linki Sil</span>
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Ek Özellikler -->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-base font-medium">Ek Özellikler</CardTitle>
                                <Button variant="ghost" size="sm" @click="editExtrasModalOpen = true" class="h-7 text-xs">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        fill="currentColor"
                                        class="bi bi-pencil-square"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
                                        />
                                    </svg>
                                    <span class="ml-1.5">Düzenle</span>
                                </Button>
                            </CardHeader>
                            <CardContent class="text-sm">
                                <div class="flex items-center py-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        class="mr-2 text-gray-500"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5"
                                        />
                                        <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                    </svg>
                                    <span class="text-muted-foreground">Şifre: {{ props.link.password ? 'Tanımlı' : 'Tanımlanmamış' }}</span>
                                </div>

                                <div class="flex items-center py-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        class="mr-2 text-gray-500"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"
                                        />
                                    </svg>
                                    <span class="text-muted-foreground"
                                        >Son Kullanım:
                                        {{ props.link.ends_at ? new Date(props.link.ends_at).toLocaleString('tr-TR') : 'Ayarlanmamış' }}</span
                                    >
                                </div>

                                <div class="flex items-center py-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        class="mr-2 text-gray-500"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3zm4 8a4 4 0 0 1-8 0V5a4 4 0 1 1 8 0zM8 0a5 5 0 0 0-5 5v6a5 5 0 0 0 10 0V5a5 5 0 0 0-5-5z"
                                        />
                                    </svg>
                                    <span class="text-muted-foreground">
                                        Tıklama Limiti:
                                        {{ props.link.expiration_clicks ? props.link.clicks + ' / ' + props.link.expiration_clicks : 'Ayarlanmamış' }}
                                    </span>
                                </div>

                                <div class="flex items-center py-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        class="mr-2 text-gray-500"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"
                                        />
                                    </svg>
                                    <span class="text-muted-foreground">
                                        Süre Sonu URL: {{ props.link.expiration_url ? formatUrl(props.link.expiration_url) : 'Tanımlanmamış' }}
                                    </span>
                                </div>

                                <div class="flex items-center py-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        class="mr-2 text-gray-500"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"
                                        />
                                    </svg>
                                    <span class="text-muted-foreground">
                                        Pikseller:
                                        {{
                                            props.link.pixels && props.link.pixels.length > 0
                                                ? props.link.pixels.map((p) => p.name).join(', ')
                                                : 'Tanımlanmamış'
                                        }}
                                    </span>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- UTM Parametreleri -->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-base font-medium">UTM Parametreleri</CardTitle>
                                <Button variant="ghost" size="sm" @click="editUtmModalOpen = true" class="h-7 text-xs">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        fill="currentColor"
                                        class="bi bi-pencil-square"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
                                        />
                                    </svg>
                                    <span class="ml-1.5">Düzenle</span>
                                </Button>
                            </CardHeader>
                            <CardContent class="text-sm">
                                <!-- UTM Gösterim Tablosu -->
                                <Table>
                                    <TableBody>
                                        <TableRow>
                                            <TableCell class="p-1 font-medium">
                                                <div>Kaynak</div>
                                                <div class="text-xs text-muted-foreground">UTM Source</div>
                                            </TableCell>
                                            <TableCell>{{ props.link.utm_source || 'Tanımlanmamış' }}</TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell class="p-1 font-medium">
                                                <div>Medya</div>
                                                <div class="text-xs text-muted-foreground">UTM Medium</div>
                                            </TableCell>
                                            <TableCell>{{ props.link.utm_medium || 'Tanımlanmamış' }}</TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell class="p-1 font-medium">
                                                <div>Kampanya</div>
                                                <div class="text-xs text-muted-foreground">UTM Campaign</div>
                                            </TableCell>
                                            <TableCell>{{ props.link.utm_campaign || 'Tanımlanmamış' }}</TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell class="p-1 font-medium">
                                                <div>Arama Terimi</div>
                                                <div class="text-xs text-muted-foreground">UTM Term</div>
                                            </TableCell>
                                            <TableCell>{{ props.link.utm_term || 'Tanımlanmamış' }}</TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell class="p-1 font-medium">
                                                <div>İçerik</div>
                                                <div class="text-xs text-muted-foreground">UTM Content</div>
                                            </TableCell>
                                            <TableCell>{{ props.link.utm_content || 'Tanımlanmamış' }}</TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </CardContent>
                        </Card>

                        <!-- Qr Code-->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-base font-medium">Qr (Kare) Kod</CardTitle>
                            </CardHeader>
                            <CardContent class="flex flex-col items-center justify-center">
                                <div class="mb-4 flex flex-col items-center">
                                    <div class="mb-4 rounded-md border bg-white p-2">
                                        <!-- Sabit boyutlu bir div kullanarak QR kodun taşmasını önlüyoruz -->
                                        <canvas ref="qrCanvasRef" :width="previewSize" :height="previewSize"></canvas>
                                    </div>
                                    <div class="mb-3 text-sm text-muted-foreground">QR kodunu tarayarak linke hızlıca ulaşabilirsiniz</div>

                                    <div class="flex w-full flex-col space-y-2">
                                        <div class="mb-2 flex items-center space-x-2">
                                            <Label class="min-w-24">İndirme Boyutu:</Label>
                                            <Select v-model="qrSize">
                                                <SelectTrigger class="w-32">
                                                    <SelectValue placeholder="Boyut" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem v-for="size in qrSizeOptions" :key="size" :value="size">
                                                        {{ size }}x{{ size }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>

                                        <Button @click="downloadQRCode" class="w-full" variant="outline">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="currentColor"
                                                class="mr-2"
                                                viewBox="0 0 16 16"
                                            >
                                                <path
                                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"
                                                />
                                                <path
                                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"
                                                />
                                            </svg>
                                            QR Kodu İndir ({{ qrSize }}x{{ qrSize }})
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </LinksLayout>

        <!-- Genel Bilgiler Düzenleme Modal -->
        <Dialog v-model:open="editBasicModalOpen">
            <DialogContent class="sm:max-w-[525px]">
                <DialogHeader>
                    <DialogTitle>Temel Bilgileri Düzenle</DialogTitle>
                    <DialogDescription> Link temel bilgilerini güncelleyin </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitBasicForm">
                    <div class="grid gap-4 py-4">
                        <!-- Takma Ad alanı - tam genişlik -->
                        <div class="grid gap-2">
                            <Label for="alias">Takma Ad</Label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        class="text-muted-foreground"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zm6 4v7.5a1.5 1.5 0 0 1-1.5 1.5H9V7h6zM2.5 16A1.5 1.5 0 0 1 1 14.5V7h6v9H2.5z"
                                        />
                                    </svg>
                                </div>
                                <Input id="alias" v-model="basicForm.alias" class="pl-10" placeholder="İstediğiniz takma adı giriniz" required />
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Otomatik oluşturulan değer yerine kendi özel tanımınızı giriniz. Örnek: 2025-etkinlik-basvuru
                            </p>
                        </div>

                        <!-- Yayınlama Tarihi - tam genişlik -->
                        <div class="grid gap-2">
                            <Label class="text-sm font-medium">Yayınlama Tarihi/Saati</Label>
                            <span v-if="!isLinkDisabled" class="text-xs text-red-500">* Link aktif olduğu için düzenlenemez</span>
                            <div class="flex gap-2" :class="{ 'opacity-50': !isLinkDisabled }">
                                <div class="relative w-2/3">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="text-muted-foreground"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"
                                            />
                                        </svg>
                                    </div>
                                    <Input v-model="basicForm.published_at_date" type="date" class="pl-10" :min="today" :disabled="!isLinkDisabled" />
                                </div>
                                <div class="w-1/3">
                                    <Input v-model="basicForm.published_at_time" type="time" :disabled="!isLinkDisabled" />
                                </div>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Gelecek bir tarihte otomatik olarak yayınlanması için tarih/saat belirtin.
                                <span v-if="!isLinkDisabled" class="text-red-500">Link aktifken değiştirilemez.</span>
                            </p>
                        </div>

                        <!-- Alan ve Hedeflenen Tıklama yan yana -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Alan seçimi -->
                            <div class="grid gap-2">
                                <Label for="space_id">Alan</Label>
                                <div class="relative">
                                    <Select v-model="basicForm.space_id" :disabled="props.spaces.length === 0">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Bir alan seçin" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="space in props.spaces" :key="space.id" :value="space.id">
                                                {{ space.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <Button
                                        v-if="basicForm.space_id"
                                        type="button"
                                        variant="ghost"
                                        class="absolute right-8 top-0 h-full px-2"
                                        @click="basicForm.space_id = ''"
                                    >
                                        <span class="sr-only">Temizle</span>
                                        <XIcon class="h-4 w-4 text-muted-foreground" />
                                    </Button>
                                </div>
                                <div v-if="props.spaces.length === 0" class="text-xs text-red-500">Lütfen önce bir alan ekleyiniz.</div>
                                <p v-else class="text-xs text-muted-foreground">Linki bir alana eklemek için seçiniz.</p>
                            </div>

                            <!-- Hedeflenen Tıklama -->
                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label for="goal" class="text-sm font-medium">Hedeflenen Tıklama</Label>
                                    <Switch id="enable-goal" v-model:checked="isGoalEnabled" aria-label="Hedef aktif/pasif" />
                                </div>
                                <div class="relative" :class="{ 'opacity-50': !isGoalEnabled }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="text-muted-foreground"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5q0 .807-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33 33 0 0 1 2.5.5zm.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935zm10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935M3.504 1q.01.775.056 1.469c.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.5.5 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667q.045-.694.056-1.469z"
                                            />
                                        </svg>
                                    </div>
                                    <Input id="goal" v-model="basicForm.goal" type="number" class="pl-10" min="0" :disabled="!isGoalEnabled" />
                                </div>
                                <p class="text-xs text-muted-foreground">Linkinizi etkinliğini ölçmek için hedef giriniz</p>
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="editBasicModalOpen = false">İptal</Button>
                        <Button type="submit" :disabled="basicForm.processing">Kaydet</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Ek Özellikler Düzenleme Modal -->
        <Dialog v-model:open="editExtrasModalOpen">
            <DialogContent class="sm:max-w-[600px]">
                <DialogHeader>
                    <DialogTitle>Ek Özellikleri Düzenle</DialogTitle>
                    <DialogDescription>Link ek özelliklerini güncelleyin</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitExtrasForm">
                    <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Şifre ve Şifre İpucu Bölümü -->
                            <div class="grid gap-4 md:col-span-2">
                                <div class="flex items-center justify-between">
                                    <Label for="password" class="text-sm font-medium">Şifre</Label>
                                    <Switch id="enable-password" v-model:checked="isPasswordEnabled" aria-label="Şifre aktif/pasif" />
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2" :class="{ 'opacity-50': !isPasswordEnabled }">
                                    <!-- Şifre alanı -->
                                    <div>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    fill="currentColor"
                                                    class="text-muted-foreground"
                                                    viewBox="0 0 16 16"
                                                >
                                                    <path
                                                        d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"
                                                    />
                                                    <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                                </svg>
                                            </div>
                                            <Input
                                                id="password"
                                                v-model="extrasForm.password"
                                                class="pl-10"
                                                placeholder="Şifre giriniz"
                                                :disabled="!isPasswordEnabled"
                                                @input="onPasswordChange"
                                            />
                                        </div>
                                        <p class="mt-1 text-xs text-muted-foreground">Şifre erişimli link oluşturmak için şifre giriniz.</p>
                                    </div>

                                    <!-- Şifre İpucu alanı -->
                                    <div>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    fill="currentColor"
                                                    class="text-muted-foreground"
                                                    viewBox="0 0 16 16"
                                                >
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                    <path
                                                        d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"
                                                    />
                                                </svg>
                                            </div>
                                            <Input
                                                id="password_hint"
                                                v-model="extrasForm.password_hint"
                                                class="pl-10"
                                                placeholder="Şifre için hatırlatıcı ipucu"
                                                :disabled="!isPasswordEnabled"
                                            />
                                        </div>
                                        <p class="mt-1 text-xs text-muted-foreground">Şifreyi hatırlamanıza yardımcı olacak bir ipucu (opsiyonel).</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tıklama Limiti -->
                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label for="expiration_clicks" class="text-sm font-medium">Tıklama Limiti</Label>
                                    <Switch
                                        id="enable-expiration-clicks"
                                        v-model:checked="isExpirationClicksEnabled"
                                        aria-label="Tıklama limiti aktif/pasif"
                                    />
                                </div>
                                <div class="relative" :class="{ 'opacity-50': !isExpirationClicksEnabled }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="text-muted-foreground"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3zm4 8a4 4 0 0 1-8 0V5a4 4 0 1 1 8 0zM8 0a5 5 0 0 0-5 5v6a5 5 0 0 0 10 0V5a5 5 0 0 0-5-5z"
                                            />
                                        </svg>
                                    </div>
                                    <Input
                                        id="expiration_clicks"
                                        v-model="extrasForm.expiration_clicks"
                                        type="number"
                                        class="pl-10"
                                        min="1"
                                        placeholder="Tıklama sayısı"
                                        :disabled="!isExpirationClicksEnabled"
                                    />
                                </div>
                                <p class="text-xs text-muted-foreground">Belirtilen tıklama sayısına ulaşıldığında link süresi dolacaktır.</p>
                            </div>

                            <!-- Son Tarih/Saat -->
                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label class="text-sm font-medium">Son Tarih/Saat</Label>
                                    <Switch
                                        id="enable-expiration-date"
                                        v-model:checked="isExpirationDateEnabled"
                                        aria-label="Son tarih aktif/pasif"
                                    />
                                </div>
                                <div class="flex gap-2" :class="{ 'opacity-50': !isExpirationDateEnabled }">
                                    <div class="relative w-2/3">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="currentColor"
                                                class="text-muted-foreground"
                                                viewBox="0 0 16 16"
                                            >
                                                <path
                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"
                                                />
                                            </svg>
                                        </div>
                                        <Input
                                            v-model="extrasForm.expiration_date"
                                            type="date"
                                            class="pl-10"
                                            :min="today"
                                            :disabled="!isExpirationDateEnabled"
                                        />
                                    </div>
                                    <div class="w-1/3">
                                        <Input v-model="extrasForm.expiration_time" type="time" :disabled="!isExpirationDateEnabled" />
                                    </div>
                                </div>
                                <p class="text-xs text-muted-foreground">Linkin süresinin dolacağı tarih ve saati belirtin.</p>
                            </div>

                            <!-- Süre Sonu URL -->
                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label for="expiration_url" class="text-sm font-medium">Süre Sonu URL</Label>
                                    <Switch
                                        id="enable-expiration-url"
                                        v-model:checked="isExpirationUrlEnabled"
                                        aria-label="Süre sonu URL aktif/pasif"
                                    />
                                </div>
                                <div class="relative" :class="{ 'opacity-50': !isExpirationUrlEnabled }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="text-muted-foreground"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"
                                            />
                                            <path
                                                fill-rule="evenodd"
                                                d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"
                                            />
                                        </svg>
                                    </div>
                                    <Input
                                        id="expiration_url"
                                        v-model="extrasForm.expiration_url"
                                        type="url"
                                        class="pl-10"
                                        placeholder="https://..."
                                        :disabled="!isExpirationUrlEnabled"
                                    />
                                </div>
                                <p class="text-xs text-muted-foreground">Link süresi dolduğunda yönlendirilecek URL adresini belirtin.</p>
                            </div>

                            <!-- Pikseller -->
                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label for="pixel_ids" class="text-sm font-medium">Pikseller</Label>
                                    <Switch id="enable-pixels" v-model:checked="isPixelsEnabled" aria-label="Pikseller aktif/pasif" />
                                </div>
                                <div class="relative" :class="{ 'opacity-50': !isPixelsEnabled }">
                                    <div class="w-full rounded-md border border-input p-2">
                                        <div class="mb-2 flex flex-wrap gap-1">
                                            <div
                                                v-for="pixelId in extrasForm.pixel_ids"
                                                :key="pixelId"
                                                class="inline-flex items-center rounded-md bg-primary/10 px-2 py-1 text-xs"
                                            >
                                                {{ findPixelName(pixelId) }}
                                                <button type="button" @click="removePixel(pixelId)" class="ml-1" :disabled="!isPixelsEnabled">
                                                    <XIcon class="h-3 w-3" />
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <Select v-model="selectedPixel" @update:modelValue="addSelectedPixel" :disabled="!isPixelsEnabled">
                                                <SelectTrigger class="h-8 border-0 p-0">
                                                    <SelectValue placeholder="Piksel ekle..." />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem v-for="pixel in availablePixels" :key="pixel.id" :value="pixel.id">
                                                        {{ pixel.name }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-muted-foreground">Linki için özel piksel değeri tanımlamak isterseniz seçiniz.</p>
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="editExtrasModalOpen = false">İptal</Button>
                        <Button type="submit" :disabled="extrasForm.processing">Kaydet</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- UTM Parametreleri Düzenleme Modal -->
        <Dialog v-model:open="editUtmModalOpen">
            <DialogContent class="sm:max-w-[600px]">
                <DialogHeader>
                    <DialogTitle>UTM Parametrelerini Düzenle</DialogTitle>
                    <DialogDescription> Link UTM parametrelerini güncelleyin </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitUtmForm">
                    <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Kampanya Adı -->
                            <div class="space-y-2 md:col-span-2">
                                <div class="flex items-center justify-between">
                                    <Label for="utm_campaign" class="text-sm font-medium">Kampanya Adı</Label>
                                    <Switch id="enable-utm-campaign" v-model:checked="isUtmCampaignEnabled" aria-label="Kampanya adı aktif/pasif" />
                                </div>
                                <div class="relative" :class="{ 'opacity-50': !isUtmCampaignEnabled }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
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
                                            class="lucide lucide-tag"
                                        >
                                            <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z" />
                                            <path d="M7 7h.01" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="utm_campaign"
                                        v-model="utmForm.utm_campaign"
                                        placeholder="Örn: yaz_indirimi, yeni_urun"
                                        class="pl-10"
                                        :disabled="!isUtmCampaignEnabled"
                                    />
                                </div>
                                <div class="text-xs text-muted-foreground">Kampanyanın adını girin (örneğin: yaz_indirimi, yeni_urun vb.)</div>
                            </div>

                            <!-- Trafik Kaynağı -->
                            <div class="space-y-2">
                                <Label for="utm_source" class="text-sm font-medium">Trafik Kaynağı</Label>
                                <div class="relative" :class="{ 'opacity-50': !isUtmSourceActive }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
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
                                            class="lucide lucide-globe"
                                        >
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                            <path d="M2 12h20" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="utm_source"
                                        v-model="utmForm.utm_source"
                                        placeholder="Örn: google, facebook, newsletter"
                                        class="pl-10"
                                        :disabled="!isUtmSourceActive"
                                    />
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    Trafiğin geldiği kaynağı belirtin (örneğin: Google, Facebook, E-posta bülteni vb.)
                                </div>
                            </div>

                            <!-- Trafik Kanalı -->
                            <div class="space-y-2">
                                <Label for="utm_medium" class="text-sm font-medium">Trafik Kanalı</Label>
                                <div class="relative" :class="{ 'opacity-50': !isUtmMediumActive }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
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
                                            class="lucide lucide-arrow-right-left"
                                        >
                                            <path d="m16 3 4 4-4 4" />
                                            <path d="M20 7H4" />
                                            <path d="m8 21-4-4 4-4" />
                                            <path d="M4 17h16" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="utm_medium"
                                        v-model="utmForm.utm_medium"
                                        placeholder="Örn: cpc, email, social"
                                        class="pl-10"
                                        :disabled="!isUtmMediumActive"
                                    />
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    Trafiğin hangi kanal üzerinden geldiğini belirtin (örneğin: CPC, E-posta, Sosyal Medya vb.)
                                </div>
                            </div>

                            <!-- Anahtar Kelime -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <Label for="utm_term" class="text-sm font-medium">Anahtar Kelime</Label>
                                    <Switch id="enable-utm-term" v-model:checked="isUtmTermEnabled" aria-label="Anahtar kelime aktif/pasif" />
                                </div>
                                <div class="relative" :class="{ 'opacity-50': !isUtmTermEnabled }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
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
                                            class="lucide lucide-hash"
                                        >
                                            <line x1="4" x2="20" y1="9" y2="9" />
                                            <line x1="4" x2="20" y1="15" y2="15" />
                                            <line x1="10" x2="8" y1="3" y2="21" />
                                            <line x1="16" x2="14" y1="3" y2="21" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="utm_term"
                                        v-model="utmForm.utm_term"
                                        placeholder="Örn: yaz-indirimi, mavi-gömlek"
                                        class="pl-10"
                                        :disabled="!isUtmTermEnabled"
                                    />
                                </div>
                                <div class="text-xs text-muted-foreground">Ücretli arama reklamlarında kullanılan anahtar kelimeyi belirtin.</div>
                            </div>

                            <!-- İçerik Varyasyonu -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <Label for="utm_content" class="text-sm font-medium">İçerik Varyasyonu</Label>
                                    <Switch
                                        id="enable-utm-content"
                                        v-model:checked="isUtmContentEnabled"
                                        aria-label="İçerik varyasyonu aktif/pasif"
                                    />
                                </div>
                                <div class="relative" :class="{ 'opacity-50': !isUtmContentEnabled }">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
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
                                            class="lucide lucide-layers"
                                        >
                                            <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                            <polyline points="2 17 12 22 22 17" />
                                            <polyline points="2 12 12 17 22 12" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="utm_content"
                                        v-model="utmForm.utm_content"
                                        placeholder="Örn: banner-link, text-link"
                                        class="pl-10"
                                        :disabled="!isUtmContentEnabled"
                                    />
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    İçerik veya reklam varyasyonlarını ayırt etmek için bir açıklama girin.
                                </div>
                            </div>
                        </div>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="editUtmModalOpen = false">İptal</Button>
                        <Button type="submit" :disabled="utmForm.processing">Kaydet</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Link Dondurma Modal -->
        <Dialog v-model:open="freezeLinkModalOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Linki Dondur</DialogTitle>
                    <DialogDescription> Bu işlem linki erişime kapatacaktır </DialogDescription>
                </DialogHeader>
                <div class="py-4">
                    <div class="mb-4 border-l-4 border-yellow-400 bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Dikkat!</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>
                                        Linki dondurursanız, linkiniz erişime kapalı olacak ve ziyaretçileriniz bu link üzerinden yönlendirme
                                        alamayacaktır.
                                    </p>
                                    <p class="mt-2">Daha sonra istediğiniz zaman linki tekrar aktifleştirebilirsiniz.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="mb-2">Linki dondurmak istediğinize emin misiniz?</p>
                    <div class="mb-3 flex items-center rounded bg-gray-50 p-2">
                        <span class="font-medium"> https://kampanya.test/{{ props.link.alias }} </span>
                    </div>
                </div>
                <DialogFooter>
                    <div class="flex w-full justify-between">
                        <Button variant="outline" @click="freezeLinkModalOpen = false">İptal</Button>
                        <Button variant="destructive" @click="toggleLinkStatus">Evet, Linki Dondur</Button>
                    </div>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Link Aktifleştirme Modal -->
        <Dialog v-model:open="activateLinkModalOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Linki Aktifleştir</DialogTitle>
                    <DialogDescription> Bu işlem linki erişime açacaktır </DialogDescription>
                </DialogHeader>
                <div class="py-4">
                    <p class="mb-2">Linki aktifleştirmek istediğinize emin misiniz?</p>
                    <div class="mb-3 flex items-center rounded bg-gray-50 p-2">
                        <span class="font-medium"> https://kampanya.test/{{ props.link.alias }} </span>
                    </div>
                </div>
                <DialogFooter>
                    <div class="flex w-full justify-between">
                        <Button variant="outline" @click="activateLinkModalOpen = false">İptal</Button>
                        <Button variant="success" class="bg-green-600 hover:bg-green-700" @click="toggleLinkStatus"> Evet, Linki Aktifleştir </Button>
                    </div>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Link Silme Modal -->
        <Dialog v-model:open="deleteLinkModalOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Linki Sil</DialogTitle>
                    <DialogDescription> Bu işlem kalıcıdır ve geri alınamaz </DialogDescription>
                </DialogHeader>
                <div class="py-4">
                    <div class="mb-4 border-l-4 border-red-400 bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Uyarı!</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Bu işlem linkinizi ve tüm istatistiklerini kalıcı olarak silecektir. Bu işlemi geri alamazsınız.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="mb-2">Bu linki silmek istediğinize emin misiniz?</p>
                    <div class="mb-3 flex items-center rounded bg-gray-50 p-2">
                        <span class="font-medium"> https://kampanya.test/{{ props.link.alias }} </span>
                    </div>
                </div>
                <DialogFooter>
                    <div class="flex w-full justify-between">
                        <Button variant="outline" @click="deleteLinkModalOpen = false">İptal</Button>
                        <Button variant="destructive" @click="deleteLink">Evet, Linki Sil</Button>
                    </div>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
