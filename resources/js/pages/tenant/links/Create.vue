<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { ArrowLeft, Link as LinkIcon, Plus, Save, Trash, XIcon } from 'lucide-vue-next';

import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';

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

interface Props {
    spaces: Space[];
    pixels: Pixel[];
    countries: Record<string, string>;
    platforms: string[];
    languages: Record<string, { name: string; iso: string; rtl: number }>;
}

const props = defineProps<Props>();

const isMultiple = ref(false);
const multipleUrls = ref([{ url: '' }]);

// Hedefleme için state
const targetType = ref(0); // Eski yapı için bunu tutuyoruz (şimdilik)
const countryTargetEnabled = ref(false);
const platformTargetEnabled = ref(false);
const languageTargetEnabled = ref(false);
const rotationTargetEnabled = ref(false);
const countryRows = ref([{ key: '', value: '' }]);
const platformRows = ref([{ key: '', value: '' }]);
const languageRows = ref([{ key: '', value: '' }]);
const rotationRows = ref([{ value: '' }]);

const form = useForm({
    url: '',
    is_multiple: false,
    multiple_urls: [],
    alias: '',
    space_id: '',
    pixel_ids: [],
    disabled: false,
    password: '',
    target_type: 0,
    goal: null,
    published_at_date: '',
    published_at_time: '',
    country_target: [], // Değiştirdik
    platform_target: [], // Değiştirdik
    language_target: [], // Değiştirdik
    rotation_target: [], // Değiştirdik
    expiration_date: '',
    expiration_time: '',
    expiration_clicks: null,
    expiration_url: '',

    utm_source: '',
    utm_medium: '',
    utm_campaign: '',
    utm_term: '',
    utm_content: '',
});

const addMultipleUrl = () => {
    if (multipleUrls.value.length < 10) {
        multipleUrls.value.push({ url: '' });
    }
};

const removeMultipleUrl = (index: number) => {
    multipleUrls.value.splice(index, 1);
};

const toggleMultipleMode = () => {
    isMultiple.value = !isMultiple.value;
    form.is_multiple = isMultiple.value;

    if (isMultiple.value) {
        form.url = '';
    } else {
        form.multiple_urls = [];
        multipleUrls.value = [{ url: '' }];
    }
};

// Geçici seçim için state
const selectedPixel = ref(null);

// Hesaplanan özellik: Henüz seçilmemiş pikseller
const availablePixels = computed(() => {
    return props.pixels.filter((pixel) => !form.pixel_ids.includes(pixel.id));
});

// Piksel adını ID'ye göre bulan yardımcı fonksiyon
const findPixelName = (pixelId) => {
    const pixel = props.pixels.find((p) => p.id === pixelId);
    return pixel ? pixel.name : 'Bilinmeyen Piksel';
};

// Seçilen pikseli ekleyen fonksiyon
const addSelectedPixel = (value) => {
    if (value && !form.pixel_ids.includes(value)) {
        form.pixel_ids.push(value);
    }
    selectedPixel.value = null; // Seçimi sıfırla
};

// Piksel kaldırma fonksiyonu
const removePixel = (pixelId) => {
    form.pixel_ids = form.pixel_ids.filter((id) => id !== pixelId);
};

const prepareFormData = () => {
    // Form için targetType'ı sayıya çevir
    form.target_type = 0;

    // Hedefleme verilerini hazırla
    if (countryTargetEnabled.value) {
        form.country_target = countryRows.value.filter((row) => row.key && row.value);
    } else {
        form.country_target = [];
    }

    if (platformTargetEnabled.value) {
        form.platform_target = platformRows.value.filter((row) => row.key && row.value);
    } else {
        form.platform_target = [];
    }

    if (languageTargetEnabled.value) {
        form.language_target = languageRows.value.filter((row) => row.key && row.value);
    } else {
        form.language_target = [];
    }

    if (rotationTargetEnabled.value) {
        form.rotation_target = rotationRows.value.filter((row) => row.value);
    } else {
        form.rotation_target = [];
    }

    // En az bir hedefleme türü aktif ise target_type = 1 olsun
    if (form.country_target.length > 0 || form.platform_target.length > 0 || form.language_target.length > 0 || form.rotation_target.length > 0) {
        form.target_type = 1;
    }
};

const submit = () => {
    // Çoklu URL modu aktifse
    if (isMultiple.value) {
        form.multiple_urls = multipleUrls.value.map((item) => item.url).filter((url) => url.trim() !== '');
    }

    // Yayınlama tarih ve saati varsa published_at hesapla
    if (form.published_at_date && form.published_at_time) {
        form.published_at = form.published_at_date + ' ' + form.published_at_time;
    }

    // Hedefleme verilerini oluştur
    prepareFormData();

    // Form gönderimi
    form.post(route('app.link.store'));
};

const today = ref(new Date().toISOString().split('T')[0]);
</script>

<template>
    <AppLayout>
        <Head title="Yeni Link" />
        <LinksLayout>
            <div class="w-full space-y-6">
                <div class="flex w-full items-center justify-between">
                    <HeadingSmall title="Yeni Link" description="Link bilgilerini bu form yardımıyla oluşturabilirsiniz" />

                    <Link :href="route('app.links')" class="inline-flex">
                        <Button class="h-7 px-2 text-xs" variant="outline">
                            <ArrowLeft class="mr-1.5 h-3.5 w-3.5" />
                            <span>Geri Dön</span>
                        </Button>
                    </Link>
                </div>

                <form @submit.prevent="submit">
                    <!-- Ana Link Alanı -->
                    <div class="mb-6 rounded-md border bg-card p-4">
                        <div class="flex flex-col items-start gap-4 md:flex-row">
                            <div class="flex-1">
                                <div v-if="!isMultiple" class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <LinkIcon class="h-5 w-5 text-muted-foreground" />
                                    </div>
                                    <Input
                                        v-model="form.url"
                                        type="url"
                                        placeholder="https://..."
                                        class="py-6 pl-10 text-lg"
                                        :disabled="isMultiple"
                                        required
                                    />
                                </div>
                                <div v-if="!isMultiple" class="mt-1 text-xs text-muted-foreground">Lütfen kısaltılacak web adresini giriniz</div>

                                <div v-else class="space-y-3">
                                    <div v-for="(item, index) in multipleUrls" :key="index" class="flex gap-2">
                                        <div class="relative flex-1">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <LinkIcon class="h-5 w-5 text-muted-foreground" />
                                            </div>
                                            <Input v-model="item.url" type="url" placeholder="https://..." class="pl-10" required />
                                        </div>
                                        <Button
                                            v-if="index > 0 || multipleUrls.length > 1"
                                            type="button"
                                            variant="destructive"
                                            size="icon"
                                            @click="removeMultipleUrl(index)"
                                        >
                                            <Trash class="h-4 w-4" />
                                        </Button>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="text-xs text-muted-foreground">Birden fazla link ekleyebilirsiniz (maksimum 10)</div>
                                        <Button
                                            v-if="multipleUrls.length < 10"
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            class="flex items-center gap-1"
                                            @click="addMultipleUrl"
                                        >
                                            <Plus class="h-4 w-4" />
                                            <span>URL Ekle</span>
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex min-w-[15rem] flex-col gap-2">
                                <Button type="submit" class="flex w-full items-center gap-2 py-6">
                                    <Save class="h-5 w-5" />
                                    <span class="text-lg">Link Oluştur</span>
                                </Button>
                                <Button type="button" variant="outline" class="flex w-full items-center gap-2" @click="toggleMultipleMode">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
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
                                    <span>{{ isMultiple ? 'Tekli URL Modu' : 'Çoklu URL Modu' }}</span>
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Form Sections -->
                    <div class="space-y-8 rounded-md border p-6">
                        <!-- Genel Bilgiler Bölümü -->
                        <div>
                            <h3 class="mb-4 text-lg font-medium">Genel Bilgiler</h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="space-y-2 md:col-span-3">
                                    <Label for="alias" class="text-sm font-medium">Takma Ad</Label>
                                    <div class="relative">
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
                                                class="lucide lucide-type"
                                            >
                                                <polyline points="4 7 4 4 20 4 20 7" />
                                                <line x1="9" x2="15" y1="20" y2="20" />
                                                <line x1="12" x2="12" y1="4" y2="20" />
                                            </svg>
                                        </div>
                                        <Input id="alias" v-model="form.alias" placeholder="İsteğe bağlı özel takma ad" class="pl-10" />
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Otomatik oluşturulan değer yerine kendi özel tanımınızı giriniz. Örnek: 2025-etkinlik-basvuru.
                                    </div>
                                    <div v-if="form.errors.alias" class="text-sm text-destructive">{{ form.errors.alias }}</div>
                                </div>

                                <!-- Alan (Space) Select -->
                                <div class="space-y-2">
                                    <Label for="space_id" class="text-sm font-medium">Alan</Label>
                                    <div class="relative">
                                        <Select v-model="form.space_id">
                                            <SelectTrigger>
                                                <SelectValue placeholder="Bir alan seçin" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="space in spaces" :key="space.id" :value="space.id">
                                                    {{ space.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <Button
                                            v-if="form.space_id"
                                            type="button"
                                            variant="ghost"
                                            class="absolute right-8 top-0 h-full px-2"
                                            @click="form.space_id = ''"
                                        >
                                            <span class="sr-only">Temizle</span>
                                            <XIcon class="h-4 w-4 text-muted-foreground" />
                                        </Button>
                                    </div>
                                    <div class="text-xs text-muted-foreground">Linki bir gruba eklemek isterseniz seçiniz.</div>
                                </div>

                                <!-- Piksel Select -->
                                <div class="space-y-2">
                                    <Label for="pixel_ids" class="text-sm font-medium">Pikseller</Label>
                                    <div class="relative">
                                        <div class="w-full rounded-md border border-input p-2">
                                            <div class="flex flex-wrap gap-1">
                                                <div
                                                    v-for="pixelId in form.pixel_ids"
                                                    :key="pixelId"
                                                    class="inline-flex items-center rounded-md bg-primary/10 px-2 py-1 text-xs"
                                                >
                                                    {{ findPixelName(pixelId) }}
                                                    <button type="button" @click="removePixel(pixelId)" class="ml-1">
                                                        <XIcon class="h-3 w-3" />
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <Select v-model="selectedPixel" @update:modelValue="addSelectedPixel">
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
                                    <div class="text-xs text-muted-foreground">Linke eklemek istediğiniz pikselleri seçiniz.</div>
                                </div>

                                <!-- Tıklama Hedefi -->
                                <div class="space-y-2">
                                    <Label for="goal" class="text-sm font-medium">Hedeflenen Tıklama</Label>
                                    <div class="relative">
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
                                                class="lucide lucide-trophy"
                                            >
                                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
                                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
                                                <path d="M4 22h16" />
                                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
                                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
                                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z" />
                                            </svg>
                                        </div>
                                        <Input
                                            id="goal"
                                            v-model="form.goal"
                                            type="number"
                                            min="0"
                                            placeholder="Hedeflenen tıklama sayısı"
                                            class="pl-10"
                                        />
                                    </div>
                                    <div class="text-xs text-muted-foreground">Linkinizi etkinliğini ölçmek için hedef giriniz</div>
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-sm font-medium">Durum</Label>
                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="disabled" v-model:checked="form.disabled" />
                                        <label
                                            for="disabled"
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                        >
                                            Taslak olarak kaydet
                                        </label>
                                    </div>
                                    <div class="text-xs text-muted-foreground">İşaretlerseniz, link oluşturulacak ancak aktif olmayacaktır.</div>
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-sm font-medium">Zamanla</Label>
                                    <div class="flex gap-2">
                                        <div class="relative w-2/3">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <!-- Takvim ikonu -->
                                            </div>
                                            <Input v-model="form.published_at_date" type="date" class="pl-10" :min="today" />
                                        </div>
                                        <div class="w-1/3">
                                            <Input v-model="form.published_at_time" type="time" />
                                        </div>
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Gelecek bir tarihte otomatik olarak yayınlanması için tarih/saat belirtin.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Separator />

                        <!-- Ek Bilgiler Bölümü -->
                        <div>
                            <h3 class="mb-4 text-lg font-medium">Ek Bilgiler</h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Son Tarih/Saat -->
                                <div class="space-y-2">
                                    <Label class="text-sm font-medium">Son Tarih/Saat</Label>
                                    <div class="flex gap-2">
                                        <div class="relative w-2/3">
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
                                                    class="lucide lucide-calendar"
                                                >
                                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                                    <line x1="16" x2="16" y1="2" y2="6"></line>
                                                    <line x1="8" x2="8" y1="2" y2="6"></line>
                                                    <line x1="3" x2="21" y1="10" y2="10"></line>
                                                </svg>
                                            </div>
                                            <Input v-model="form.expiration_date" type="date" class="pl-10" :min="today" />
                                        </div>
                                        <div class="w-1/3">
                                            <Input v-model="form.expiration_time" type="time" />
                                        </div>
                                    </div>
                                    <div class="text-xs text-muted-foreground">Linkin süresinin dolacağı tarih ve saati belirtin.</div>
                                </div>

                                <!-- Tıklama Limiti -->
                                <div class="space-y-2">
                                    <Label for="expiration_clicks" class="text-sm font-medium">Tıklama Limiti</Label>
                                    <div class="relative">
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
                                                class="lucide lucide-mouse-pointer-click"
                                            >
                                                <path d="m9 9 5 12 1.8-5.2L21 14Z" />
                                                <path d="M7.2 2.2 8 5.1" />
                                                <path d="m5.1 8-2.9-.8" />
                                                <path d="M14 4.1 12 6" />
                                                <path d="m6 12-1.9 2" />
                                            </svg>
                                        </div>
                                        <Input
                                            id="expiration_clicks"
                                            v-model="form.expiration_clicks"
                                            type="number"
                                            min="1"
                                            placeholder="Tıklama sayısı"
                                            class="pl-10"
                                        />
                                    </div>
                                    <div class="text-xs text-muted-foreground">Belirtilen tıklama sayısına ulaşıldığında link süresi dolacaktır.</div>
                                </div>

                                <!-- Süre Sonu URL -->
                                <div class="space-y-2">
                                    <Label for="expiration_url" class="text-sm font-medium">Süre Sonu URL</Label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <LinkIcon class="h-4 w-4 text-muted-foreground" />
                                        </div>
                                        <Input id="expiration_url" v-model="form.expiration_url" type="url" placeholder="https://..." class="pl-10" />
                                    </div>
                                    <div class="text-xs text-muted-foreground">Link süresi dolduğunda yönlendirilecek URL adresini belirtin.</div>
                                </div>

                                <!-- Şifre -->
                                <div class="space-y-2">
                                    <Label for="password" class="text-sm font-medium">Şifre</Label>
                                    <div class="relative">
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
                                                class="lucide lucide-key"
                                            >
                                                <path
                                                    d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"
                                                />
                                            </svg>
                                        </div>
                                        <Input id="password" v-model="form.password" type="text" placeholder="Şifre giriniz" class="pl-10" />
                                    </div>
                                    <div class="text-xs text-muted-foreground">Şifre erişimli bir link oluşturmak isterseniz şifre giriniz.</div>
                                </div>
                            </div>
                        </div>

                        <Separator />

                        <!-- UTM Bilgileri Bölümü -->
                        <div>
                            <h3 class="mb-4 text-lg font-medium">UTM Bilgileri</h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Kampanya Adı -->
                                <div class="space-y-2 md:col-span-2">
                                    <Label for="utm_campaign" class="text-sm font-medium">Kampanya Adı</Label>
                                    <div class="relative">
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
                                            v-model="form.utm_campaign"
                                            placeholder="Örn: yaz_indirimi, yeni_urun"
                                            class="pl-10"
                                        />
                                    </div>
                                    <div class="text-xs text-muted-foreground">Kampanyanın adını girin (örneğin: yaz_indirimi, yeni_urun vb.)</div>
                                </div>

                                <!-- Trafik Kaynağı -->
                                <div class="space-y-2">
                                    <Label for="utm_source" class="text-sm font-medium">Trafik Kaynağı</Label>
                                    <div class="relative">
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
                                                <path
                                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"
                                                />
                                                <path d="M2 12h20" />
                                            </svg>
                                        </div>
                                        <Input
                                            id="utm_source"
                                            v-model="form.utm_source"
                                            placeholder="Örn: google, facebook, newsletter"
                                            class="pl-10"
                                        />
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Trafiğin geldiği kaynağı belirtin (örneğin: Google, Facebook, E-posta bülteni vb.)
                                    </div>
                                </div>

                                <!-- Trafik Kanalı -->
                                <div class="space-y-2">
                                    <Label for="utm_medium" class="text-sm font-medium">Trafik Kanalı</Label>
                                    <div class="relative">
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
                                        <Input id="utm_medium" v-model="form.utm_medium" placeholder="Örn: cpc, email, social" class="pl-10" />
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Trafiğin hangi kanal üzerinden geldiğini belirtin (örneğin: CPC, E-posta, Sosyal Medya vb.)
                                    </div>
                                </div>

                                <!-- Anahtar Kelime -->
                                <div class="space-y-2">
                                    <Label for="utm_term" class="text-sm font-medium">Anahtar Kelime</Label>
                                    <div class="relative">
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
                                        <Input id="utm_term" v-model="form.utm_term" placeholder="Örn: yaz-indirimi, mavi-gömlek" class="pl-10" />
                                    </div>
                                    <div class="text-xs text-muted-foreground">Ücretli arama reklamlarında kullanılan anahtar kelimeyi belirtin.</div>
                                </div>

                                <!-- İçerik Varyasyonu -->
                                <div class="space-y-2">
                                    <Label for="utm_content" class="text-sm font-medium">İçerik Varyasyonu</Label>
                                    <div class="relative">
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
                                        <Input id="utm_content" v-model="form.utm_content" placeholder="Örn: banner-link, text-link" class="pl-10" />
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        İçerik veya reklam varyasyonlarını ayırt etmek için bir açıklama girin.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hedefleme Bölümü -->
                        <div>
                            <h3 class="mb-4 text-lg font-medium">Hedefleme</h3>

                            <div class="mb-4">
                                <div class="mb-2 flex items-center space-x-2">
                                    <Checkbox id="enable-country-targeting" v-model:checked="countryTargetEnabled" />
                                    <Label for="enable-country-targeting" class="text-sm font-medium"> Ülke bazlı hedefleme </Label>
                                </div>

                                <!-- Ülke Hedefleme -->
                                <div v-if="countryTargetEnabled" class="ml-6 space-y-4">
                                    <div v-for="(row, index) in countryRows" :key="`country-${index}`" class="flex gap-2">
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
                                            <Button type="button" variant="destructive" class="w-full" @click="countryRows.splice(index, 1)">
                                                <Trash class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        class="flex items-center gap-1"
                                        @click="countryRows.push({ key: '', value: '' })"
                                    >
                                        <Plus class="h-4 w-4" />
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
                                    <div v-for="(row, index) in platformRows" :key="`platform-${index}`" class="flex gap-2">
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
                                            <Button type="button" variant="destructive" class="w-full" @click="platformRows.splice(index, 1)">
                                                <Trash class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        class="flex items-center gap-1"
                                        @click="platformRows.push({ key: '', value: '' })"
                                    >
                                        <Plus class="h-4 w-4" />
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
                                    <div v-for="(row, index) in languageRows" :key="`language-${index}`" class="flex gap-2">
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
                                            <Button type="button" variant="destructive" class="w-full" @click="languageRows.splice(index, 1)">
                                                <Trash class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        class="flex items-center gap-1"
                                        @click="languageRows.push({ key: '', value: '' })"
                                    >
                                        <Plus class="h-4 w-4" />
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
                                    <div v-for="(row, index) in rotationRows" :key="`rotation-${index}`" class="flex gap-2">
                                        <div class="relative w-4/5">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <LinkIcon class="h-4 w-4 text-muted-foreground" />
                                            </div>
                                            <Input v-model="row.value" type="url" placeholder="https://..." class="pl-10" />
                                        </div>
                                        <div class="w-1/5">
                                            <Button type="button" variant="destructive" class="w-full" @click="rotationRows.splice(index, 1)">
                                                <Trash class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        class="flex items-center gap-1"
                                        @click="rotationRows.push({ value: '' })"
                                    >
                                        <Plus class="h-4 w-4" />
                                        <span>Yeni Rotasyon Ekle</span>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </LinksLayout>
    </AppLayout>
</template>
