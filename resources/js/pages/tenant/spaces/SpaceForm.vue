<script setup lang="ts">
import DeleteConfirmDialog from '@/components/DeleteConfirmDialog.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LoaderCircle, RotateCcw, Save, Trash2 } from 'lucide-vue-next';

interface Space {
    id?: number;
    name: string;
    color: string;
}

interface Props {
    space?: Space;
    isEditing?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    space: () => ({ name: '', color: '#3B82F6' }),
    isEditing: false,
});

const colors = [
    '#EF4444', // Red
    '#F97316', // Orange
    '#F59E0B', // Amber
    '#84CC16', // Lime
    '#10B981', // Emerald
    '#06B6D4', // Cyan
    '#3B82F6', // Blue
    '#8B5CF6', // Violet
    '#EC4899', // Pink
    '#6B7280', // Gray
];

const form = useForm({
    name: props.space.name || '',
    color: props.space.color || '#3B82F6',
});

// Update form values when props change
watch(
    () => props.space,
    (newSpace) => {
        form.name = newSpace.name || '';
        form.color = newSpace.color || '#3B82F6';
    },
    { deep: true },
);

const submit = (e) => {
    e.preventDefault();

    if (props.isEditing && props.space?.id) {
        form.post(route('app.space.update', props.space.id));
    } else {
        form.post(route('app.space.store'));
    }
};

// Reset form
const resetForm = () => {
    if (!props.isEditing) {
        form.reset();
        form.color = '#3B82F6';
    } else {
        form.name = props.space?.name || '';
        form.color = props.space?.color || '#3B82F6';
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
    if (props.space?.id) {
        router.delete(route('app.space.destroy', props.space.id));
    }
};
</script>

<template>
    <form @submit.prevent="submit">
        <Card>
            <CardHeader>
                <CardTitle>Alan Detayları</CardTitle>
                <CardDescription> Alanınızın adını ve rengini {{ isEditing ? 'düzenleyin' : 'belirleyin' }} </CardDescription>
            </CardHeader>
            <CardContent class="space-y-6">
                <div class="space-y-2">
                    <Label for="name">Alan Adı <span class="text-destructive">*</span></Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Örn: Sosyal Medya, Blog, Newsletter"
                        :disabled="form.processing"
                        maxlength="100"
                        required
                    />
                    <div v-if="form.errors.name" class="mt-1 text-sm text-destructive">
                        {{ form.errors.name }}
                    </div>
                </div>

                <div class="space-y-2">
                    <Label>Renk <span class="text-destructive">*</span></Label>
                    <div class="flex flex-wrap gap-3">
                        <div
                            v-for="color in colors"
                            :key="color"
                            :style="{ backgroundColor: color }"
                            class="h-8 w-8 cursor-pointer rounded-full ring-offset-2"
                            :class="{ 'ring-2 ring-ring': form.color === color }"
                            @click="form.color = color"
                        ></div>
                    </div>
                    <div v-if="form.errors.color" class="mt-1 text-sm text-destructive">
                        {{ form.errors.color }}
                    </div>
                </div>
            </CardContent>
            <CardFooter class="flex justify-between">
                <Button variant="outline" as-child>
                    <a :href="route('app.spaces')">İptal</a>
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

                    <Button type="submit" :disabled="form.processing || !form.name" class="gap-1.5">
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
        description="Bu alanı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz, bağlı linklerde alan blgisi boş olarak görünecektir."
        @confirm="handleDeleteConfirmed"
    />
</template>
