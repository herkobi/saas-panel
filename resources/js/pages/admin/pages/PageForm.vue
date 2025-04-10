<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import DeleteConfirmDialog from '@/components/DeleteConfirmDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { QuillEditor } from '@/components/ui/quill-editor';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { router, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon, LoaderCircle, RotateCcw, Save, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    page?: {
        id?: number;
        title: string;
        slug: string;
        summary: string;
        content: string;
        status: boolean;
    };
    mode: 'create' | 'edit';
}>();

// Form verilerini sakla
const formData = useForm({
    title: props.page?.title || '',
    slug: props.page?.slug || '',
    summary: props.page?.summary || '',
    content: props.page?.content || '',
    status: props.page?.status ?? true,
});

// Form submission - manuel tetiklenecek
const submit = (e) => {
    if (e) e.preventDefault();

    if (props.mode === 'create') {
        formData.post(route('panel.pages.store'), {
            preserveScroll: true,
        });
    } else {
        formData.put(route('panel.pages.update', props.page?.id), {
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
    if (props.page?.id) {
        router.delete(route('panel.pages.destroy', props.page.id));
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
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-medium tracking-tight">
                    {{ mode === 'create' ? 'Yeni Sayfa Ekle' : 'Sayfayı Düzenle' }}
                </h1>
                <p class="mt-0.5 text-sm text-muted-foreground">Sayfa bilgilerini girin ve kaydedin</p>
            </div>
            <Button variant="outline" as-child size="sm" class="h-7 gap-1 px-2">
                <a :href="route('panel.pages.index')">
                    <ArrowLeftIcon class="h-3.5 w-3.5" />
                    <span class="text-sm">Listeye Dön</span>
                </a>
            </Button>
        </div>

        <form @submit="submit">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Sol Bölüm - Ana İçerik -->
                <div class="space-y-6 lg:col-span-2">
                    <Card>
                        <CardContent class="space-y-4 pt-4">
                            <!-- Title Field -->
                            <div class="space-y-2">
                                <Label for="title" class="text-sm">Başlık <span class="text-destructive">*</span></Label>
                                <Input
                                    id="title"
                                    v-model="formData.title"
                                    required
                                    class="h-8 text-sm"
                                    placeholder="Sayfa başlığı"
                                    @blur="!formData.slug && generateSlug()"
                                />
                                <InputError :message="formData.errors.title" />
                            </div>

                            <!-- Slug Field -->
                            <div class="space-y-2">
                                <div class="flex items-end justify-between">
                                    <Label for="slug" class="text-sm">Slug <span class="text-destructive">*</span></Label>
                                    <Button type="button" variant="outline" size="sm" @click="generateSlug" class="h-6 px-1.5 text-[10px]">
                                        Başlıktan Oluştur
                                    </Button>
                                </div>
                                <Input id="slug" v-model="formData.slug" required class="h-8 text-sm" placeholder="sayfa-url" />
                                <InputError :message="formData.errors.slug" />
                            </div>

                            <!-- Summary Field -->
                            <div class="space-y-2">
                                <Label for="summary" class="text-sm">Özet</Label>
                                <Textarea id="summary" v-model="formData.summary" class="min-h-[80px] text-sm" placeholder="Kısa sayfa özeti" />
                                <InputError :message="formData.errors.summary" />
                            </div>

                            <!-- Content Field -->
                            <div class="space-y-2">
                                <Label for="content" class="text-sm">İçerik</Label>
                                <QuillEditor id="content" v-model="formData.content" placeholder="Sayfa içeriği" height="200px" />
                                <InputError :message="formData.errors.content" />
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sağ Bölüm - İşlemler -->
                <div class="space-y-6">
                    <Card>
                        <CardHeader class="pb-2 pt-4">
                            <CardTitle class="text-sm font-medium">İşlemler</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="status" class="text-sm">Durum</Label>
                                    <div class="flex items-center gap-3">
                                        <Switch id="status" v-model:checked="formData.status" class="h-4 w-7 [&>span]:h-2.5 [&>span]:w-2.5" />
                                        <span class="text-sm font-medium" :class="formData.status ? 'text-green-600' : 'text-red-600'">
                                            {{ formData.status ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </div>
                                    <InputError :message="formData.errors.status" />
                                    <p class="text-[10px] text-muted-foreground">
                                        Aktif sayfalar site üzerinde görüntülenecektir. Pasif sayfalar ise görüntülenmeyecektir.
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <Button type="submit" size="sm" class="h-8 w-full gap-1.5" :disabled="formData.processing">
                                    <LoaderCircle v-if="formData.processing" class="h-3.5 w-3.5 animate-spin" />
                                    <Save v-else class="h-3.5 w-3.5" />
                                    <span class="text-sm">{{ mode === 'create' ? 'Kaydet' : 'Güncelle' }}</span>
                                </Button>

                                <Button type="button" variant="outline" size="sm" @click="resetForm" class="h-8 w-full gap-1.5">
                                    <RotateCcw class="h-3.5 w-3.5" />
                                    <span class="text-sm">Sıfırla</span>
                                </Button>

                                <Button
                                    v-if="mode === 'edit'"
                                    type="button"
                                    variant="destructive"
                                    size="sm"
                                    @click="confirmDelete"
                                    class="h-8 w-full gap-1.5"
                                >
                                    <Trash2 class="h-3.5 w-3.5" />
                                    <span class="text-sm">Sil</span>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </form>

        <!-- Silme onay modalı -->
        <DeleteConfirmDialog
            v-model:isOpen="deleteDialogOpen"
            title="Sayfayı Sil"
            description="Bu sayfayı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
            @confirm="handleDeleteConfirmed"
        />
    </div>
</template>
