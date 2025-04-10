<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import DeleteConfirmDialog from '@/components/DeleteConfirmDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { QuillEditor } from '@/components/ui/quill-editor';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Switch } from '@/components/ui/switch';
import { router, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon, LoaderCircle, RotateCcw, Save, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    contract?: {
        id?: number;
        title: string;
        slug: string;
        date: string;
        content: string;
        status: boolean;
    };
    mode: 'create' | 'edit';
}>();

// Form verilerini sakla
const formData = useForm({
    title: props.contract?.title || '',
    slug: props.contract?.slug || '',
    date: props.contract?.date || '',
    type: props.contract?.type || 'Genel', // Varsayılan olarak Genel seçeneği
    content: props.contract?.content || '',
    status: props.contract?.status ?? true,
});

// Form submission - manuel tetiklenecek
const submit = (e) => {
    // Event varsa, varsayılan form gönderimini engelle
    if (e) e.preventDefault();

    if (props.mode === 'create') {
        formData.post(route('panel.contracts.store'), {
            preserveScroll: true,
        });
    } else {
        formData.put(route('panel.contracts.update', props.contract?.id), {
            preserveScroll: true,
        });
    }
};

// Modal durumu için state
const deleteDialogOpen = ref(false);

// Silme işlemi için onay modalını göster
const confirmDelete = () => {
    deleteDialogOpen.value = true;
};

// Silme işlemi onaylandığında
const handleDeleteConfirmed = () => {
    if (props.contract?.id) {
        router.delete(route('panel.contracts.destroy', props.contract.id));
    }
};

// Reset form to original values
const resetForm = () => {
    formData.reset();
    formData.clearErrors();
};

const turkishToEnglish = (str) => {
    return str
        .replace(/ğ/g, 'g')
        .replace(/Ğ/g, 'G')
        .replace(/ü/g, 'u')
        .replace(/Ü/g, 'U')
        .replace(/ş/g, 's')
        .replace(/Ş/g, 'S')
        .replace(/ı/g, 'i')
        .replace(/İ/g, 'I')
        .replace(/ö/g, 'o')
        .replace(/Ö/g, 'O')
        .replace(/ç/g, 'c')
        .replace(/Ç/g, 'C');
};

const generateSlug = () => {
    if (!formData.title) return;
    const nameInEnglish = turkishToEnglish(formData.title);
    formData.slug = nameInEnglish
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
};
</script>

<template>
    <div class="w-full space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-medium tracking-tight">
                    {{ mode === 'create' ? 'Yeni Sözleşme Ekle' : 'Sözleşmeyi Düzenle' }}
                </h1>
                <p class="mt-0.5 text-sm text-muted-foreground">Sözleşme bilgilerini girin ve kaydedin</p>
            </div>
            <Button variant="outline" asChild size="sm" class="h-7 gap-1 px-2">
                <a :href="route('panel.contracts.index')">
                    <ArrowLeftIcon class="h-3.5 w-3.5" />
                    <span class="text-sm">Listeye Dön</span>
                </a>
            </Button>
        </div>

        <form @submit="submit">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <!-- Sol Bölüm - Ana İçerik -->
                <div class="space-y-4 lg:col-span-2">
                    <Card>
                        <CardContent class="space-y-3 pt-4">
                            <!-- Title Field -->
                            <div class="space-y-1">
                                <Label for="title" class="text-sm">Başlık <span class="text-destructive">*</span></Label>
                                <Input
                                    id="title"
                                    v-model="formData.title"
                                    required
                                    class="h-8 text-sm"
                                    placeholder="Sözleşme başlığı"
                                    @blur="!formData.slug && generateSlug()"
                                />
                                <InputError :message="formData.errors.title" />
                            </div>

                            <!-- Slug Field -->
                            <div class="space-y-1">
                                <div class="flex items-end justify-between">
                                    <Label for="slug" class="text-sm">Slug <span class="text-destructive">*</span></Label>
                                    <Button type="button" variant="outline" size="sm" @click="generateSlug" class="h-6 px-1.5 text-[10px]">
                                        Sözleşme Adından Oluştur
                                    </Button>
                                </div>
                                <Input id="slug" v-model="formData.slug" required class="h-8 text-sm" placeholder="sozlesme-slug-alani" />
                                <InputError :message="formData.errors.slug" />
                            </div>

                            <!-- Content Field -->
                            <div class="space-y-1">
                                <Label for="content" class="text-sm">İçerik</Label>
                                <QuillEditor id="content" v-model="formData.content" placeholder="Sözleşme içeriği" height="200px" />
                                <InputError :message="formData.errors.content" />
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sağ Bölüm - Yan Menü -->
                <div class="space-y-4">
                    <!-- Durum Kartı -->
                    <Card>
                        <CardHeader class="pb-2 pt-4">
                            <CardTitle class="text-sm font-medium">Yayın Durumu</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <Label for="status" class="text-sm">Durum</Label>
                                    <div class="flex items-center gap-1.5">
                                        <Switch id="status" v-model:checked="formData.status" class="h-4 w-7 [&>span]:h-2.5 [&>span]:w-2.5" />
                                        <span class="text-sm font-medium" :class="formData.status ? 'text-green-600' : 'text-red-600'">
                                            {{ formData.status ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </div>
                                    <InputError :message="formData.errors.status" />
                                </div>
                                <p class="text-[10px] text-muted-foreground">
                                    Aktif sözleşmeler site üzerinde görüntülenecektir. Pasif sözleşmeler ise görüntülenmeyecektir.
                                </p>
                            </div>
                            <div class="mt-3 space-y-1">
                                <Label class="text-sm">Sözleşme Tipi <span class="text-destructive">*</span></Label>
                                <RadioGroup v-model="formData.type" class="flex gap-4">
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem id="type_general" value="Genel" v-model:modelValue="formData.type" />
                                        <Label for="type_general" class="text-sm">Genel</Label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem id="type_membership" value="Üyelik" v-model:modelValue="formData.type" />
                                        <Label for="type_membership" class="text-sm">Üyelik</Label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem id="type_payment" value="Ödeme" v-model:modelValue="formData.type" />
                                        <Label for="type_payment" class="text-sm">Ödeme</Label>
                                    </div>
                                </RadioGroup>
                                <InputError :message="formData.errors.type" />
                            </div>
                            <div class="mt-3 space-y-1">
                                <Label for="date" class="text-sm">Sözleşme Güncelleme Tarihi <span class="text-destructive">*</span></Label>
                                <Input id="date" v-model="formData.date" required class="h-8 text-sm" placeholder="Sözleşme güncelleme tarihi" />
                                <InputError :message="formData.errors.date" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Kaydet/Sıfırla Butonları -->
                    <Card>
                        <CardHeader class="pb-2 pt-4">
                            <CardTitle class="text-sm font-medium">İşlemler</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-2">
                                <Button type="submit" size="sm" class="h-8 w-full gap-1.5" :disabled="formData.processing">
                                    <LoaderCircle v-if="formData.processing" class="h-3.5 w-3.5 animate-spin" />
                                    <Save v-else class="h-3.5 w-3.5" />
                                    <span class="text-sm">{{ mode === 'create' ? 'Kaydet' : 'Güncelle' }}</span>
                                </Button>

                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    <Button type="button" variant="outline" size="sm" @click="resetForm" class="h-8 gap-1.5">
                                        <RotateCcw class="h-3.5 w-3.5" />
                                        <span class="text-sm">Sıfırla</span>
                                    </Button>

                                    <Button
                                        v-if="mode === 'edit'"
                                        type="button"
                                        variant="destructive"
                                        size="sm"
                                        @click="confirmDelete"
                                        class="h-8 gap-1.5"
                                    >
                                        <Trash2 class="h-3.5 w-3.5" />
                                        <span class="text-sm">Sil</span>
                                    </Button>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <!-- Silme onay modalı -->
                    <DeleteConfirmDialog
                        v-model:isOpen="deleteDialogOpen"
                        title="Sözleşmeyi Sil"
                        description="Bu sözleşmeyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                        @confirm="handleDeleteConfirmed"
                    />
                </div>
            </div>
        </form>
    </div>
</template>
