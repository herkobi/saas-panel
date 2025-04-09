<script setup lang="ts">
import InputError from '@/components/admin/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Select, SelectContent, SelectGroup, SelectItem, SelectSeparator, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon, CircleX } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface PlanFeature {
    id?: number;
    feature_id: number;
    feature_name?: string;
    access_type: 'access_only' | 'limited';
    limit_type?: 'renewable' | 'cumulative' | null;
    limit_value?: number | null;
    limit_reset_period?: 'hourly' | 'daily' | 'weekly' | 'monthly' | 'yearly' | null;
    restore_on_delete: boolean;
}

interface Plan {
    id?: number;
    name: string;
    description: string | null;
    is_featured: boolean;
    is_free: boolean;
    billing_period: 'monthly' | 'yearly' | 'both';
    country_code: string;
    currency_code: string;
    tax_rate_code: string;
    monthly_price: number | null;
    yearly_price: number | null;
    trial_days: number;
    grace_period_days: number;
    payment_timing: 'upfront' | 'later';
    status: boolean;
    sort_order: number | null;
    planFeatures?: PlanFeature[];
}

interface Feature {
    id: number;
    name: string;
    slug: string;
    description: string | null;
}

interface Currency {
    name: string;
    symbol: string;
    position: string;
    thousands_separator: string;
    decimal_separator: string;
    decimals: number;
    iso_code: string;
}

interface Country {
    name: string;
    code: string;
}

interface TaxRate {
    name: string;
    code: string;
    rate: number;
}

const props = defineProps<{
    plan?: Plan;
    features: Feature[];
    currencies: Currency[];
    countries: Country[];
    taxRates: TaxRate[];
    mode: 'create' | 'edit';
}>();

// Form verilerini sakla
const formData = useForm<Plan>({
    name: props.plan?.name || '',
    description: props.plan?.description || '',
    is_featured: props.plan?.is_featured ?? false,
    is_free: props.plan?.is_free ?? false,
    billing_period: props.plan?.billing_period || 'both',
    country_code: props.plan?.country_code || '',
    currency_code: props.plan?.currency_code || '',
    tax_rate_code: props.plan?.tax_rate_code || '',
    monthly_price: props.plan?.monthly_price || null,
    yearly_price: props.plan?.yearly_price || null,
    trial_days: props.plan?.trial_days || 0,
    grace_period_days: props.plan?.grace_period_days || 0,
    payment_timing: props.plan?.payment_timing || 'upfront',
    status: props.plan?.status ?? true,
    sort_order: props.plan?.sort_order || null,
    planFeatures: props.plan?.planFeatures || [],
});

// Mevcut planın özelliklerini ve tüm özellikleri birleştirin
const availableFeatures = computed(() => {
    // Planın sahip olduğu özellik ID'lerini al
    const planFeatureIds = formData.planFeatures?.map((pf) => pf.feature_id) || [];

    // Henüz eklenmeyen özellikleri döndür
    return props.features.filter((feature) => !planFeatureIds.includes(feature.id));
});

// Yeni özellik eklemek için seçilen özellik
const selectedFeatureId = ref<number | null>(null);

// Yeni özellik ekle
const addFeature = () => {
    if (!selectedFeatureId.value) return;

    const featureId = selectedFeatureId.value;
    const feature = props.features.find((f) => f.id === featureId);

    if (feature) {
        formData.planFeatures = [
            ...(formData.planFeatures || []),
            {
                feature_id: featureId,
                feature_name: feature.name,
                access_type: 'access_only',
                restore_on_delete: false,
            },
        ];
    }

    selectedFeatureId.value = null;
};

// Özelliği kaldır
const removeFeature = (index: number) => {
    if (!formData.planFeatures) return;
    formData.planFeatures = [...formData.planFeatures.slice(0, index), ...formData.planFeatures.slice(index + 1)];
};

const clearSelection = (field: any) => {
    if (field === 'country') {
        formData.country_code = ''; // Ülke seçimi sıfırlama
    } else if (field === 'currency') {
        formData.currency_code = ''; // Para birimi seçimi sıfırlama
    } else if (field === 'taxRate') {
        formData.tax_rate_code = ''; // Vergi oranı seçimi sıfırlama
    }
};

// Özellik olmadan kayıt için uyarı state'i
const noFeaturesWarningOpen = ref(false);

// Form submission - özellikleri kontrol et
const submit = (e) => {
    e.preventDefault();

    // Plana hiç özellik eklenmemiş mi kontrolü
    if (!formData.planFeatures || formData.planFeatures.length === 0) {
        noFeaturesWarningOpen.value = true; // Uyarı modalını göster
    } else {
        // Özellikler eklenmiş, normal kayıt işlemine devam et
        submitForm();
    }
};

// Normal form gönderimi
const submitForm = () => {
    // Ücretsiz plan olarak işaretlendiyse ilgili alanları temizle
    if (formData.is_free) {
        formData.monthly_price = null;
        formData.yearly_price = null;
        formData.billing_period = 'both'; // Varsayılan bir değer ata
        formData.country_code = '';
        formData.currency_code = '';
        formData.tax_rate_code = '';
        formData.payment_timing = 'upfront'; // Varsayılan bir değer ata
    }

    if (props.mode === 'create') {
        formData.post(route('panel.plans.store'), {
            preserveScroll: true,
        });
    } else if (props.plan?.id) {
        formData.put(route('panel.plans.update', props.plan.id), {
            preserveScroll: true,
        });
    }
};

// Özellikleri olmayan planı pasif olarak kaydet
const submitWithNoFeatures = () => {
    // Planın durumunu pasif yap
    formData.status = false;

    // Form gönderimi
    submitForm();

    // Modalı kapat
    noFeaturesWarningOpen.value = false;
};

// Ücretsiz plan seçildiğinde fiyatları sıfırla
watch(
    () => formData.is_free,
    (newValue) => {
        if (newValue) {
            formData.monthly_price = null;
            formData.yearly_price = null;
        }
    },
);

// Limit tipi değiştiğinde limit değerini kontrol et
watch(
    () => formData.planFeatures,
    (newFeatures) => {
        if (newFeatures) {
            newFeatures.forEach((feature) => {
                // Eğer limit tipi "cumulative" (sabit) ise ve limit değeri -1 ise düzelt
                if (feature.limit_type === 'cumulative' && feature.limit_value === -1) {
                    feature.limit_value = 0; // -1 yerine 0 veya başka bir değer ataması
                }
            });
        }
    },
    { deep: true }, // Derin izleme
);

// Reset form
const resetForm = () => {
    formData.reset();
    formData.clearErrors();
};
</script>

<template>
    <div class="w-full space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-medium tracking-tight">
                    {{ mode === 'create' ? 'Yeni Plan Ekle' : 'Planı Düzenle' }}
                </h1>
                <p class="mt-0.5 text-sm text-muted-foreground">Plan bilgilerini girin ve kaydedin</p>
            </div>
            <Button variant="outline" asChild size="sm" class="h-7 gap-1 px-2">
                <a :href="route('panel.plans.index')">
                    <ArrowLeftIcon class="h-3.5 w-3.5" />
                    <span class="text-sm">Listeye Dön</span>
                </a>
            </Button>
        </div>

        <form @submit="submit">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <!-- Sol Bölüm - Ana Bilgiler -->
                <div class="space-y-4 lg:col-span-2">
                    <Card>
                        <CardHeader class="pb-4 pt-4">
                            <CardTitle class="text-sm font-medium">Plan Bilgileri</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <!-- İsim Alanı -->
                            <div class="space-y-1">
                                <Label for="name" class="text-sm">İsim <span class="text-destructive">*</span></Label>
                                <Input id="name" v-model="formData.name" required class="h-8 text-sm" />
                                <InputError :message="formData.errors.name" />
                            </div>

                            <!-- Açıklama Alanı -->
                            <div class="space-y-1">
                                <Label for="description" class="text-sm">Açıklama</Label>
                                <Textarea id="description" v-model="formData.description" rows="3" class="min-h-[80px] text-sm" />
                                <InputError :message="formData.errors.description" />
                            </div>

                            <!-- Sıralama, Deneme ve Grace Süresi (Tek Satırda) -->
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="space-y-1">
                                    <Label for="sort_order" class="text-sm">Sıralama</Label>
                                    <Input id="sort_order" v-model="formData.sort_order" type="number" min="0" class="h-8 text-sm" />
                                    <InputError :message="formData.errors.sort_order" />
                                </div>

                                <div class="space-y-1">
                                    <Label for="trial_days" class="text-sm">Deneme Süresi (Gün)</Label>
                                    <Input id="trial_days" v-model="formData.trial_days" type="number" min="0" class="h-8 text-sm" />
                                    <InputError :message="formData.errors.trial_days" />
                                </div>

                                <div class="space-y-1">
                                    <Label for="grace_period_days" class="text-sm">Grace Süresi (Gün)</Label>
                                    <Input id="grace_period_days" v-model="formData.grace_period_days" type="number" min="0" class="h-8 text-sm" />
                                    <InputError :message="formData.errors.grace_period_days" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Fiyatlandırma Bilgileri -->
                    <Card>
                        <CardHeader class="pb-4 pt-4">
                            <CardTitle class="text-sm font-medium">Fiyatlandırma Bilgileri</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <!-- Ücretsiz / Ücretli Seçimi -->
                            <div class="flex items-center justify-between space-y-1">
                                <Label for="is_free" class="text-sm">Ücretsiz Plan</Label>
                                <div class="flex items-center gap-2">
                                    <Switch id="is_free" v-model:checked="formData.is_free" />
                                    <span class="text-sm font-medium">{{ formData.is_free ? 'Evet' : 'Hayır' }}</span>
                                </div>
                                <InputError :message="formData.errors.is_free" />
                            </div>

                            <!-- Ücretsiz değilse fiyatlandırma alanlarını göster -->
                            <div v-if="!formData.is_free">
                                <!-- Yenileme Periyodu ve Ödeme Zamanlaması (Tek Satırda) -->
                                <div class="grid grid-cols-1 gap-3 space-y-1 sm:grid-cols-2">
                                    <!-- Yenileme Periyodu (Radio Button) -->
                                    <div class="space-y-1">
                                        <Label class="text-sm">Yenileme Periyodu</Label>
                                        <RadioGroup v-model="formData.billing_period" class="flex gap-4">
                                            <div class="flex items-center space-x-2">
                                                <RadioGroupItem id="monthly" value="monthly" v-model:modelValue="formData.billing_period" />
                                                <Label for="monthly" class="text-sm">Aylık</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <RadioGroupItem id="yearly" value="yearly" v-model:modelValue="formData.billing_period" />
                                                <Label for="yearly" class="text-sm">Yıllık</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <RadioGroupItem id="both" value="both" v-model:modelValue="formData.billing_period" />
                                                <Label for="both" class="text-sm">Hem Aylık Hem Yıllık</Label>
                                            </div>
                                        </RadioGroup>
                                        <InputError :message="formData.errors.billing_period" />
                                    </div>

                                    <!-- Ödeme Yapısı (Radio Button) -->
                                    <div class="space-y-1">
                                        <Label class="text-sm">Ödeme Yapısı</Label>
                                        <RadioGroup v-model:modelValue="formData.payment_timing" class="flex gap-4">
                                            <div class="flex items-center space-x-2">
                                                <RadioGroupItem id="upfront" value="upfront" v-model:modelValue="formData.payment_timing" />
                                                <Label for="upfront" class="text-sm">Peşin Ödeme</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <RadioGroupItem id="later" value="later" v-model:modelValue="formData.payment_timing" />
                                                <Label for="later" class="text-sm">Sonradan Ödeme</Label>
                                            </div>
                                        </RadioGroup>
                                        <InputError :message="formData.errors.payment_timing" />
                                    </div>
                                </div>

                                <!-- Fiyatlar -->
                                <div class="grid grid-cols-1 gap-3 space-y-1 sm:grid-cols-2">
                                    <div class="space-y-1" v-if="formData.billing_period !== 'yearly'">
                                        <Label for="monthly_price" class="text-sm">Aylık Fiyat</Label>
                                        <Input
                                            id="monthly_price"
                                            v-model="formData.monthly_price"
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            class="h-8 text-sm"
                                        />
                                        <InputError :message="formData.errors.monthly_price" />
                                    </div>

                                    <div class="space-y-1" v-if="formData.billing_period !== 'monthly'">
                                        <Label for="yearly_price" class="text-sm">Yıllık Fiyat</Label>
                                        <Input
                                            id="yearly_price"
                                            v-model="formData.yearly_price"
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            class="h-8 text-sm"
                                        />
                                        <InputError :message="formData.errors.yearly_price" />
                                    </div>
                                </div>

                                <!-- Ülke, Para Birimi ve Vergi -->
                                <div class="grid grid-cols-1 gap-3 space-y-1 sm:grid-cols-3">
                                    <div class="space-y-1">
                                        <Label for="country_code" class="text-sm">Ülke</Label>
                                        <div class="relative">
                                            <Select v-model="formData.country_code">
                                                <SelectTrigger id="country_code" class="h-8 text-sm">
                                                    <SelectValue placeholder="Ülke seçin" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectGroup>
                                                        <SelectItem v-if="formData.country_code" value="none" key="default-option"
                                                            >Ülke seçin</SelectItem
                                                        >
                                                        <SelectSeparator />
                                                        <SelectItem v-for="country in countries" :key="country.code" :value="country.code">
                                                            {{ country.name }}
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                            <Button
                                                v-if="formData.country_code && formData.country_code !== 'none'"
                                                type="button"
                                                variant="ghost"
                                                class="absolute right-8 top-0 h-full px-2"
                                                @click="clearSelection('country')"
                                            >
                                                <span class="sr-only">Temizle</span>
                                                <CircleX class="h-4 w-4 text-muted-foreground" />
                                            </Button>
                                        </div>
                                        <InputError :message="formData.errors.country_code" />
                                    </div>

                                    <div class="space-y-1">
                                        <Label for="currency_code" class="text-sm">Para Birimi</Label>
                                        <div class="relative">
                                            <Select v-model="formData.currency_code">
                                                <SelectTrigger id="currency_code" class="h-8 text-sm">
                                                    <SelectValue placeholder="Para birimi seçin" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem v-if="formData.currency_code" value="none" key="default-option"
                                                        >Para birimi seçin</SelectItem
                                                    >
                                                    <SelectSeparator />
                                                    <SelectGroup>
                                                        <SelectItem
                                                            v-for="currency in currencies"
                                                            :key="currency.iso_code"
                                                            :value="currency.iso_code"
                                                        >
                                                            {{ currency.name }} ({{ currency.symbol }})
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                            <Button
                                                v-if="formData.currency_code"
                                                type="button"
                                                variant="ghost"
                                                class="absolute right-8 top-0 h-full px-2"
                                                @click="clearSelection('currency')"
                                            >
                                                <span class="sr-only">Temizle</span>
                                                <CircleX class="h-4 w-4 text-muted-foreground" />
                                            </Button>
                                        </div>
                                        <InputError :message="formData.errors.currency_code" />
                                    </div>

                                    <div class="space-y-1">
                                        <Label for="tax_rate_code" class="text-sm">Vergi Oranı</Label>
                                        <div class="relative">
                                            <Select v-model="formData.tax_rate_code">
                                                <SelectTrigger id="tax_rate_code" class="h-8 text-sm">
                                                    <SelectValue placeholder="Vergi oranı seçin" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem v-if="formData.tax_rate_code" value="none" key="default-option"
                                                        >Vergi oranı seçin</SelectItem
                                                    >
                                                    <SelectSeparator />
                                                    <SelectGroup>
                                                        <SelectItem v-for="tax in taxRates" :key="tax.code" :value="tax.code">
                                                            {{ tax.name }} ({{ tax.rate }}%)
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                            <Button
                                                v-if="formData.tax_rate_code"
                                                type="button"
                                                variant="ghost"
                                                class="absolute right-8 top-0 h-full px-2"
                                                @click="clearSelection('taxRate')"
                                            >
                                                <span class="sr-only">Temizle</span>
                                                <CircleX class="h-4 w-4 text-muted-foreground" />
                                            </Button>
                                        </div>
                                        <InputError :message="formData.errors.tax_rate_code" />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <!-- Plan Özellikleri -->
                    <Card>
                        <CardHeader class="pb-4 pt-4">
                            <CardTitle class="text-sm font-medium">Plan Özellikleri</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex items-end gap-2">
                                <div class="flex-1 space-y-1">
                                    <Label for="feature_select" class="text-sm">Özellik Ekle</Label>
                                    <div class="relative">
                                        <Select v-model="selectedFeatureId">
                                            <SelectTrigger class="h-8 text-sm">
                                                <SelectValue placeholder="Özellik seçin" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem :value="null">Özellik Seçin</SelectItem>
                                                <SelectSeparator />
                                                <SelectGroup>
                                                    <SelectItem v-for="feature in availableFeatures" :key="feature.id" :value="feature.id">
                                                        {{ feature.name }}
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                        <Button
                                            v-if="selectedFeatureId"
                                            type="button"
                                            variant="ghost"
                                            class="absolute right-8 top-0 h-full px-2"
                                            @click="selectedFeatureId = null"
                                        >
                                            <span class="sr-only">Temizle</span>
                                            <CircleX class="h-4 w-4 text-muted-foreground" />
                                        </Button>
                                    </div>
                                </div>
                                <Button type="button" size="sm" class="h-8 px-2" :disabled="!selectedFeatureId" @click="addFeature">
                                    <Plus class="mr-1 h-3.5 w-3.5" />
                                    <span class="text-sm">Ekle</span>
                                </Button>
                            </div>

                            <div v-if="formData.planFeatures && formData.planFeatures.length > 0" class="space-y-3 pt-2">
                                <div v-for="(planFeature, index) in formData.planFeatures" :key="index" class="space-y-3 rounded-md border p-3">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium">{{ planFeature.feature_name }}</h3>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="sm"
                                            class="h-6 w-6 p-0 text-destructive"
                                            @click="removeFeature(index)"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </div>

                                    <div class="space-y-3">
                                        <!-- Erişim Tipi -->
                                        <div class="space-y-1">
                                            <Label :for="`access_type_${index}`" class="text-sm">Erişim Ayarları</Label>
                                            <RadioGroup v-model="planFeature.access_type" class="mt-1 flex gap-4">
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem
                                                        :id="`access_type_access_only_${index}`"
                                                        value="access_only"
                                                        v-model:modelValue="planFeature.access_type"
                                                    />
                                                    <Label :for="`access_type_access_only_${index}`" class="text-sm">Erişilebilir Kullanım</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem
                                                        :id="`access_type_limited_${index}`"
                                                        value="limited"
                                                        v-model:modelValue="planFeature.access_type"
                                                    />
                                                    <Label :for="`access_type_limited_${index}`" class="text-sm">Limitli Kullanım</Label>
                                                </div>
                                            </RadioGroup>
                                        </div>

                                        <!-- Sınırlı erişim seçenekleri -->
                                        <div v-if="planFeature.access_type === 'limited'" class="space-y-1">
                                            <div class="space-y-1">
                                                <Label :for="`limit_type_${index}`" class="text-sm">Limit Tipi</Label>
                                                <RadioGroup v-model="planFeature.limit_type" class="mt-1 flex gap-4">
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem
                                                            :id="`limit_type_renewable_${index}`"
                                                            value="renewable"
                                                            v-model:modelValue="planFeature.limit_type"
                                                        />
                                                        <Label :for="`limit_type_renewable_${index}`" class="text-sm">Yenilenebilir</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem
                                                            :id="`limit_type_cumulative_${index}`"
                                                            value="cumulative"
                                                            v-model:modelValue="planFeature.limit_type"
                                                        />
                                                        <Label :for="`limit_type_cumulative_${index}`" class="text-sm">Sabit</Label>
                                                    </div>
                                                </RadioGroup>
                                            </div>

                                            <div class="space-y-1">
                                                <Label :for="`limit_value_${index}`" class="text-sm">Limit Değeri</Label>
                                                <Input
                                                    :id="`limit_value_${index}`"
                                                    v-model="planFeature.limit_value"
                                                    type="number"
                                                    :min="planFeature.limit_type === 'cumulative' ? 0 : -1"
                                                    class="h-8 text-sm"
                                                />
                                                <p class="text-[10px] text-muted-foreground">
                                                    {{
                                                        planFeature.limit_type === 'cumulative'
                                                            ? 'Sabit limit tipi için 0 veya pozitif değer girilmelidir'
                                                            : '-1 değeri sınırsız anlamına gelir'
                                                    }}
                                                </p>
                                            </div>

                                            <div class="space-y-1" v-if="planFeature.limit_type === 'renewable'">
                                                <Label :for="`limit_reset_period_${index}`" class="text-sm">Yenilenme Periyodu</Label>
                                                <RadioGroup v-model="planFeature.limit_reset_period" class="mt-1 flex flex-wrap gap-4">
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem
                                                            :id="`limit_reset_hourly_${index}`"
                                                            value="hourly"
                                                            v-model:modelValue="planFeature.limit_reset_period"
                                                        />
                                                        <Label :for="`limit_reset_hourly_${index}`" class="text-sm">Saatlik</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem
                                                            :id="`limit_reset_daily_${index}`"
                                                            value="daily"
                                                            v-model:modelValue="planFeature.limit_reset_period"
                                                        />
                                                        <Label :for="`limit_reset_daily_${index}`" class="text-sm">Günlük</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem
                                                            :id="`limit_reset_weekly_${index}`"
                                                            value="weekly"
                                                            v-model:modelValue="planFeature.limit_reset_period"
                                                        />
                                                        <Label :for="`limit_reset_weekly_${index}`" class="text-sm">Haftalık</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem
                                                            :id="`limit_reset_monthly_${index}`"
                                                            value="monthly"
                                                            v-model:modelValue="planFeature.limit_reset_period"
                                                        />
                                                        <Label :for="`limit_reset_monthly_${index}`" class="text-sm">Aylık</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem
                                                            :id="`limit_reset_yearly_${index}`"
                                                            value="yearly"
                                                            v-model:modelValue="planFeature.limit_reset_period"
                                                        />
                                                        <Label :for="`limit_reset_yearly_${index}`" class="text-sm">Yıllık</Label>
                                                    </div>
                                                </RadioGroup>
                                            </div>
                                        </div>

                                        <!-- Silme sonrası geri yükleme -->
                                        <div class="flex items-center justify-between">
                                            <Label :for="`restore_on_delete_${index}`" class="text-sm">Silme Sonrası Geri Yükle</Label>
                                            <div class="flex items-center gap-2">
                                                <Switch :id="`restore_on_delete_${index}`" v-model:checked="planFeature.restore_on_delete" />
                                                <span class="text-sm">{{ planFeature.restore_on_delete ? 'Evet' : 'Hayır' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="rounded-md border p-4 text-center text-sm text-muted-foreground">
                                Bu plana henüz özellik eklenmemiş.
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sağ Bölüm - Yan Panel -->
                <div class="space-y-4">
                    <!-- Durum Kartı -->
                    <Card>
                        <CardHeader class="pb-4 pt-4">
                            <CardTitle class="text-sm font-medium">Plan Durumu</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <!-- Status -->
                                <div class="flex items-center justify-between">
                                    <Label for="status" class="text-sm">Durum</Label>
                                    <div class="flex items-center gap-2">
                                        <Switch id="status" v-model:checked="formData.status" />
                                        <span class="text-sm font-medium" :class="formData.status ? 'text-green-600' : 'text-red-600'">
                                            {{ formData.status ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </div>
                                    <InputError :message="formData.errors.status" />
                                </div>

                                <!-- Featured -->
                                <div class="flex items-center justify-between">
                                    <Label for="is_featured" class="text-sm">Öne Çıkan</Label>
                                    <div class="flex items-center gap-2">
                                        <Switch id="is_featured" v-model:checked="formData.is_featured" />
                                        <span class="text-sm">{{ formData.is_featured ? 'Evet' : 'Hayır' }}</span>
                                    </div>
                                    <InputError :message="formData.errors.is_featured" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Kaydet/Sıfırla Butonları -->
                    <Card>
                        <CardHeader class="pb-4 pt-4">
                            <CardTitle class="text-sm font-medium">İşlemler</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-2">
                                <Button type="submit" size="sm" class="h-8 w-full gap-1.5" :disabled="formData.processing">
                                    <LoaderCircle v-if="formData.processing" class="h-3.5 w-3.5 animate-spin" />
                                    <Save v-else class="h-3.5 w-3.5" />
                                    <span class="text-sm">{{ mode === 'create' ? 'Kaydet' : 'Güncelle' }}</span>
                                </Button>

                                <Button type="button" variant="outline" size="sm" @click="resetForm" class="h-8 w-full gap-1.5">
                                    <RotateCcw class="h-3.5 w-3.5" />
                                    <span class="text-sm">Sıfırla</span>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </form>
    </div>

    <!-- Özellik Yok Uyarı Modalı -->
    <DeleteConfirmDialog
        v-model:isOpen="noFeaturesWarningOpen"
        title="Planınızda Hiç Özellik Yok"
        description="Plana hiçbir özellik eklemediniz. Özellik olmadan kaydedilen planlar otomatik olarak pasif duruma geçirilir. Devam etmek istiyor musunuz?"
        confirmLabel="Pasif Olarak Kaydet"
        cancelLabel="İptal Et"
        @confirm="submitWithNoFeatures"
    />
</template>
