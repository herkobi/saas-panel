<!-- CampaignForm.vue -->
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { QuillEditor } from '@/components/ui/quill-editor';
import { Save, XIcon } from 'lucide-vue-next';

const props = defineProps({
    campaign: {
        type: Object,
        default: () => ({}),
    },
    submitLabel: {
        type: String,
        default: 'Kaydet',
    },
    isEdit: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['submit']);

// Tarih biçimlendirme işlevi
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
};

// Bugünün tarihini YYYY-MM-DD formatında al
const today = new Date().toISOString().split('T')[0];

const form = useForm({
    title: props.campaign?.title || '',
    content: props.campaign?.content || '',
    terms: props.campaign?.terms || '',
    start_date: formatDate(props.campaign?.start_date) || '',
    end_date: formatDate(props.campaign?.end_date) || '',
    apply_date: formatDate(props.campaign?.apply_date) || null,
    apply_name: props.campaign?.apply_name || '',
    status: '3', // Her zaman Draft (3) olarak kaydet
    external_link: props.campaign?.external_link || '',
    image: null,
    is_featured: props.campaign?.is_featured || false,
    meta_title: props.campaign?.meta_title || '',
    meta_description: props.campaign?.meta_description || '',
    _method: props.isEdit ? 'put' : 'post',
});

const imagePreview = ref(props.campaign?.image ? props.campaign.image : null);
const processing = ref(false);

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    form.image = null;
    imagePreview.value = null;

    // Dosya inputunu da sıfırla
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.value = ''; // Dosya inputunu temizle
    }
}

function submit() {
    processing.value = true;
    emit('submit', form);
}

// Form gönderildiğinde hatalar çıkarsa processing değerini sıfırla
watch(
    () => form.errors,
    (newErrors) => {
        if (Object.keys(newErrors).length > 0) {
            processing.value = false;
        }
    },
    { deep: true },
);

// Form geçerliliğini kontrol eden computed property
const isFormValid = computed(() => {
    // Başlangıç tarihi ve bitiş tarihi kontrolü
    if (form.start_date && form.end_date) {
        if (new Date(form.end_date) <= new Date(form.start_date)) {
            return false;
        }
    }

    // Diğer form doğrulama kontrolleri buraya eklenebilir
    return true;
});
</script>

<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Sol Kolon - Ana Bilgiler (2/3) -->
            <div class="space-y-6 md:col-span-2">
                <!-- Kampanya Adı -->
                <div class="space-y-2">
                    <Label for="title" class="text-sm font-medium">Kampanya Adı</Label>
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
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                />
                                <path
                                    fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
                                />
                            </svg>
                        </div>
                        <Input id="title" v-model="form.title" placeholder="Kampanya Adını Giriniz" class="pl-10" required />
                    </div>
                    <div class="text-xs text-muted-foreground">Kampanya adını giriniz</div>
                    <div v-if="form.errors.title" class="text-sm text-destructive">{{ form.errors.title }}</div>
                </div>

                <!-- Kampanya Linki -->
                <div class="space-y-2">
                    <Label for="external_link" class="text-sm font-medium">Kampanya Linki</Label>
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
                                class="lucide lucide-link text-muted-foreground"
                            >
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        </div>
                        <Input id="external_link" v-model="form.external_link" type="url" placeholder="https://" class="pl-10" />
                    </div>
                    <div class="text-xs text-muted-foreground">Kampanyanıza ait web sitesi linkini giriniz</div>
                    <div v-if="form.errors.external_link" class="text-sm text-destructive">{{ form.errors.external_link }}</div>
                </div>

                <!-- Kampanya İçeriği -->
                <div class="space-y-2">
                    <Label for="content" class="text-sm font-medium">Kampanya İçeriği</Label>
                    <QuillEditor id="content" v-model="form.content" placeholder="Kampanya hakkında kısa açıklama giriniz" height="200px" />
                    <div class="text-xs text-muted-foreground">Kampanya hakkında kısa açıklama giriniz</div>
                    <div v-if="form.errors.content" class="text-sm text-destructive">{{ form.errors.content }}</div>
                </div>

                <!-- Kampanya Koşulları -->
                <div class="space-y-2">
                    <Label for="terms" class="text-sm font-medium">Kampanya Koşulları</Label>
                    <QuillEditor id="terms" v-model="form.terms" placeholder="Kampanya koşullarını giriniz" height="200px" />
                    <div class="text-xs text-muted-foreground">Kampanya koşullarını giriniz</div>
                    <div v-if="form.errors.terms" class="text-sm text-destructive">{{ form.errors.terms }}</div>
                </div>

                <!-- SEO Alanları -->
                <div class="pt-4">
                    <h3 class="mb-4 border-b pb-2 text-lg font-medium">SEO Ayarları</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-4 gap-3">
                            <Label for="meta_title" class="col-span-1 text-sm font-medium">Sayfa Başlığı</Label>
                            <div class="col-span-3 space-y-2">
                                <div class="relative">
                                    <Input id="meta_title" v-model="form.meta_title" maxlength="60" placeholder="Meta başlığını giriniz" />
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-muted-foreground">
                                        {{ form.meta_title?.length || 0 }}/60
                                    </div>
                                </div>
                                <div class="text-xs text-muted-foreground">Arama motorları için meta başlık (50-60 karakter)</div>
                                <div v-if="form.errors.meta_title" class="text-sm text-destructive">{{ form.errors.meta_title }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-3">
                            <Label for="meta_description" class="col-span-1 text-sm font-medium">Sayfa Açıklaması</Label>
                            <div class="col-span-3 space-y-2">
                                <div class="relative">
                                    <textarea
                                        id="meta_description"
                                        v-model="form.meta_description"
                                        rows="3"
                                        maxlength="160"
                                        class="w-full rounded-md border border-input bg-transparent px-3 py-2 pr-12 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                        placeholder="Meta açıklamasını giriniz"
                                    ></textarea>
                                    <div class="absolute right-3 top-3 text-xs text-muted-foreground">
                                        {{ form.meta_description?.length || 0 }}/160
                                    </div>
                                </div>
                                <div class="text-xs text-muted-foreground">Arama motorları için meta açıklama (150-160 karakter)</div>
                                <div v-if="form.errors.meta_description" class="text-sm text-destructive">{{ form.errors.meta_description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon - Ek Özellikler (1/3) -->
            <div class="space-y-6">
                <!-- Kampanya Görseli -->
                <div class="space-y-2">
                    <Label for="image" class="text-sm font-medium">Kampanya Görseli</Label>
                    <Input type="file" id="image" accept="image/*" @change="handleImageUpload" class="cursor-pointer" />
                    <div class="text-xs text-muted-foreground">Kampanya görselini yükleyiniz</div>
                    <div v-if="form.errors.image" class="text-sm text-destructive">{{ form.errors.image }}</div>

                    <div v-if="imagePreview" class="relative mt-3">
                        <img :src="imagePreview" alt="Kampanya Görseli Önizleme" class="h-auto max-w-full rounded-md" />
                        <Button type="button" variant="destructive" size="icon" class="absolute right-1 top-1" @click="removeImage">
                            <XIcon class="h-4 w-4" />
                        </Button>
                    </div>
                </div>

                <!-- Kampanya Tarihleri Bölümü -->
                <div class="mt-6 space-y-4">
                    <!-- Başlangıç Tarihi -->
                    <div class="space-y-2">
                        <Label for="start_date" class="text-sm font-medium">Başlangıç Tarihi</Label>
                        <Input id="start_date" v-model="form.start_date" type="date" :min="today" required />
                        <div class="text-xs text-muted-foreground">Kampanya başlangıç tarihi</div>
                        <div v-if="form.errors.start_date" class="text-sm text-destructive">{{ form.errors.start_date }}</div>
                    </div>

                    <!-- Bitiş Tarihi -->
                    <div class="space-y-2">
                        <Label for="end_date" class="text-sm font-medium">Bitiş Tarihi</Label>
                        <Input id="end_date" v-model="form.end_date" type="date" :min="today" required />
                        <div class="text-xs text-muted-foreground">Kampanya bitiş tarihi</div>
                        <div v-if="form.errors.end_date" class="text-sm text-destructive">{{ form.errors.end_date }}</div>
                    </div>

                    <!-- Ek Tarih Başlığı -->
                    <div class="space-y-2">
                        <Label for="apply_name" class="text-sm font-medium">Varsa Ek Tarih Başlığı</Label>
                        <Input id="apply_name" v-model="form.apply_name" type="text" placeholder="Ör: Sonuç Açıklama Tarihi" />
                        <div class="text-xs text-muted-foreground">
                            Varsa kampanya ile ilgili ek işlem yapılacak bir tarih varsa işlemini adını giriniz. Örnek: Fatura Yükleme Tarihi
                        </div>
                        <div v-if="form.errors.apply_name" class="text-sm text-destructive">{{ form.errors.apply_name }}</div>
                    </div>

                    <!-- Ek Tarih -->
                    <div class="space-y-2">
                        <Label for="apply_date" class="text-sm font-medium">Ek Tarih</Label>
                        <Input id="apply_date" v-model="form.apply_date" type="date" :min="today" />
                        <div class="text-xs text-muted-foreground">Varsa kampanyaya ait ek işlem tarihini giriniz.</div>
                        <div v-if="form.errors.apply_date" class="text-sm text-destructive">{{ form.errors.apply_date }}</div>
                    </div>
                </div>

                <!-- Seçenekler -->
                <div class="space-y-3">
                    <Label class="text-sm font-medium">Seçenekler</Label>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <Checkbox id="is_featured" v-model:checked="form.is_featured" />
                            <label
                                for="is_featured"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            >
                                Öne Çıkar
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Kaydet Butonu -->
                <div class="pt-4">
                    <Button type="submit" class="w-full" :disabled="processing || !isFormValid">
                        <Save class="mr-1.5 h-3.5 w-3.5" />
                        {{ submitLabel }}
                    </Button>
                </div>
            </div>
        </div>
    </form>
</template>
