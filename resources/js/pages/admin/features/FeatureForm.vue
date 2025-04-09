<script setup lang="ts">
import DeleteConfirmDialog from '@/components/DeleteConfirmDialog.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { router, useForm } from '@inertiajs/vue3';
import { LoaderCircle, RotateCcw, Save, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

interface Feature {
    id?: number;
    name: string;
    slug: string;
    description: string | null;
    status: boolean;
}

const props = defineProps<{
    feature?: Feature;
    mode: 'create' | 'edit';
}>();

// Form verilerini sakla
const formData = useForm({
    name: props.feature?.name || '',
    slug: props.feature?.slug || '',
    description: props.feature?.description || '',
    status: props.feature?.status ?? true,
});

// Silme modalı için state
const deleteDialogOpen = ref(false);

// Form submission
const submit = (e) => {
    e.preventDefault();

    if (props.mode === 'create') {
        formData.post(route('panel.features.store'), {
            preserveScroll: true,
            onSuccess: () => {
                if (props.mode === 'create') {
                    formData.reset();
                }
            },
        });
    } else {
        formData.put(route('panel.features.update', props.feature?.id), {
            preserveScroll: true,
        });
    }
};

// Reset form
const resetForm = () => {
    if (props.mode === 'create') {
        formData.reset();
    } else {
        formData.name = props.feature?.name || '';
        formData.slug = props.feature?.slug || '';
        formData.description = props.feature?.description || '';
        formData.status = props.feature?.status ?? true;
    }
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
    if (!formData.name) return;
    const nameInEnglish = turkishToEnglish(formData.name);
    formData.slug = nameInEnglish
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
};

// Silme işlemi için onay modalını göster
const confirmDelete = () => {
    deleteDialogOpen.value = true;
};

// Silme işlemi onaylandığında
const handleDeleteConfirmed = () => {
    if (props.feature?.id) {
        router.delete(route('panel.features.destroy', props.feature.id));
    }
};
</script>

<template>
    <form @submit="submit" class="space-y-3">
        <!-- Title Field -->
        <div class="space-y-1">
            <Label for="name" class="text-sm font-medium">Başlık <span class="text-destructive">*</span></Label>
            <Input
                id="name"
                v-model="formData.name"
                required
                class="h-8 text-sm"
                placeholder="Özellik başlığı"
                @blur="!formData.slug && generateSlug()"
            />
            <div v-if="formData.errors.name" class="mt-1 text-sm text-red-600">
                {{ formData.errors.name }}
            </div>
        </div>

        <!-- Slug Field -->
        <div class="space-y-1">
            <div class="flex items-end justify-between">
                <Label for="slug" class="text-sm font-medium">Slug</Label>
                <Button type="button" variant="outline" size="sm" @click="generateSlug" class="h-6 px-2 text-sm"> Başlıktan Oluştur </Button>
            </div>
            <Input id="slug" v-model="formData.slug" class="h-8 text-sm" placeholder="ozellik-slug-alani" />
            <div v-if="formData.errors.slug" class="mt-1 text-sm text-red-600">
                {{ formData.errors.slug }}
            </div>
        </div>

        <!-- Description Field -->
        <div class="space-y-1">
            <Label for="description" class="text-sm font-medium">Açıklama</Label>
            <Textarea
                id="description"
                v-model="formData.description"
                rows="3"
                class="min-h-[70px] resize-none text-sm"
                placeholder="Özellik açıklaması"
            />
            <div v-if="formData.errors.description" class="mt-1 text-sm text-red-600">
                {{ formData.errors.description }}
            </div>
        </div>

        <!-- Status Field -->
        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <Label for="status" class="text-sm font-medium">Durum</Label>
                <div class="flex items-center gap-2">
                    <Switch id="status" v-model:checked="formData.status" />
                    <span class="text-sm font-medium" :class="formData.status ? 'text-green-600' : 'text-red-600'">
                        {{ formData.status ? 'Aktif' : 'Pasif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Buttons -->
        <div class="flex gap-2 pt-2">
            <Button type="submit" size="sm" class="h-8 gap-1.5" :disabled="formData.processing">
                <LoaderCircle v-if="formData.processing" class="h-3.5 w-3.5 animate-spin" />
                <Save v-else class="h-3.5 w-3.5" />
                <span class="text-sm">{{ mode === 'create' ? 'Kaydet' : 'Güncelle' }}</span>
            </Button>

            <Button v-if="mode === 'create'" type="button" variant="outline" size="sm" @click="resetForm" class="h-8 gap-1.5">
                <RotateCcw class="h-3.5 w-3.5" />
                <span class="text-sm">Sıfırla</span>
            </Button>

            <Button v-if="mode === 'edit'" type="button" variant="destructive" size="sm" @click="confirmDelete" class="ml-auto h-8 gap-1.5">
                <Trash2 class="h-3.5 w-3.5" />
                <span class="text-sm">Sil</span>
            </Button>
        </div>
    </form>

    <!-- Silme onay modalı -->
    <DeleteConfirmDialog
        v-if="mode === 'edit'"
        v-model:isOpen="deleteDialogOpen"
        title="Özelliği Sil"
        description="Bu özelliği silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
        @confirm="handleDeleteConfirmed"
    />
</template>
