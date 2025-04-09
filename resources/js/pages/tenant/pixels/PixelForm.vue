<script setup lang="ts">
import DeleteConfirmDialog from '@/components/DeleteConfirmDialog.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle, RotateCcw, Save, Trash2, XIcon } from 'lucide-vue-next';

interface Pixel {
    id?: number;
    name: string;
    type: string;
    value: string;
}

interface PixelType {
    value: string;
    label: string;
}

interface Props {
    pixel?: Pixel;
    pixelTypes: PixelType[];
    isEditing?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    pixel: () => ({ name: '', type: '', value: '' }),
    isEditing: false,
});

const form = useForm({
    name: props.pixel.name || '',
    type: props.pixel.type || '',
    value: props.pixel.value || '',
});

// Update form values when props change
watch(
    () => props.pixel,
    (newPixel) => {
        if (newPixel) {
            form.name = newPixel.name || '';
            form.type = newPixel.type || '';
            form.value = newPixel.value || '';
        }
    },
    { immediate: true, deep: true },
);

const submit = (e) => {
    e.preventDefault();

    if (!form.type) {
        alert('Lütfen bir piksel türü seçin!');
        return;
    }

    if (props.isEditing && props.pixel?.id) {
        form.post(route('app.pixel.update', props.pixel.id));
    } else {
        form.post(route('app.pixel.store'));
    }
};

// Reset form
const resetForm = () => {
    if (!props.isEditing) {
        form.reset();
    } else {
        form.name = props.pixel?.name || '';
        form.type = props.pixel?.type || '';
        form.value = props.pixel?.value || '';
    }
    form.clearErrors();
};

// Silme modalı için state
const deleteDialogOpen = ref(false);

// Silme işlemi için onay modalını göster
const confirmDelete = () => {
    deleteDialogOpen.value = true;
};

// Silme işlemi onaylandığında
const handleDeleteConfirmed = () => {
    if (props.pixel?.id) {
        router.delete(route('app.pixel.destroy', props.pixel.id));
    }
};
</script>

<template>
    <form @submit.prevent="submit">
        <Card>
            <CardHeader>
                <CardTitle>Piksel Detayları</CardTitle>
                <CardDescription> Piksel bilgilerini {{ isEditing ? 'düzenleyin' : 'belirleyin' }} </CardDescription>
            </CardHeader>
            <CardContent class="space-y-6">
                <div class="space-y-2">
                    <Label for="name">Piksel Adı <span class="text-destructive">*</span></Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Örn: Facebook Piksel, Google Ads Dönüşüm"
                        :disabled="form.processing"
                        maxlength="100"
                        required
                    />
                    <div v-if="form.errors.name" class="mt-1 text-sm text-destructive">
                        {{ form.errors.name }}
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="type">Piksel Türü <span class="text-destructive">*</span></Label>
                    <div class="relative">
                        <Select v-model="form.type">
                            <SelectTrigger id="type" class="w-full">
                                <SelectValue placeholder="Bir piksel türü seçin" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="type in pixelTypes" :key="type.value" :value="type.value">
                                    {{ type.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <Button v-if="form.type" type="button" variant="ghost" class="absolute right-8 top-0 h-full px-2" @click="form.type = ''">
                            <span class="sr-only">Temizle</span>
                            <XIcon class="h-4 w-4 text-muted-foreground" />
                        </Button>
                    </div>
                    <div v-if="form.errors.type" class="mt-1 text-sm text-destructive">
                        {{ form.errors.type }}
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="value">Piksel Kodu <span class="text-destructive">*</span></Label>
                    <Textarea
                        id="value"
                        v-model="form.value"
                        placeholder="Piksel kodunu buraya yapıştırın"
                        :disabled="form.processing"
                        rows="6"
                        class="font-mono text-sm"
                        required
                    />
                    <div v-if="form.errors.value" class="mt-1 text-sm text-destructive">
                        {{ form.errors.value }}
                    </div>
                </div>
            </CardContent>
            <CardFooter class="flex justify-between">
                <Button variant="outline" as-child>
                    <a :href="route('app.pixels')">İptal</a>
                </Button>

                <div class="flex gap-2">
                    <Button v-if="!isEditing" type="button" variant="outline" @click="resetForm" class="gap-1.5">
                        <RotateCcw class="h-4 w-4" />
                        <span>Sıfırla</span>
                    </Button>

                    <Button v-if="isEditing" type="button" variant="destructive" @click="confirmDelete" class="gap-1.5">
                        <Trash2 class="h-4 w-4" />
                        <span>Sil</span>
                    </Button>

                    <Button type="submit" :disabled="form.processing || !form.name || !form.type || !form.value" class="gap-1.5">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        <Save v-else class="h-4 w-4" />
                        <span>{{ isEditing ? 'Güncelle' : 'Kaydet' }}</span>
                    </Button>
                </div>
            </CardFooter>
        </Card>
    </form>

    <!-- Silme onay modalı -->
    <DeleteConfirmDialog
        v-if="isEditing"
        v-model:isOpen="deleteDialogOpen"
        title="Alanı Sil"
        description="Bu alanı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve bu alana bağlı linkler varsa silinemez."
        @confirm="handleDeleteConfirmed"
    />
</template>
