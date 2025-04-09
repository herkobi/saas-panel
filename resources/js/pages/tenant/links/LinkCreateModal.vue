<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LinkIcon } from 'lucide-vue-next';

interface Props {
    open: boolean;
}

const props = defineProps<Props>();
const emit = defineEmits(['update:open']);

const form = useForm({
    url: '',
});

const isSubmitting = ref(false);

const closeModal = () => {
    emit('update:open', false);
    setTimeout(() => {
        form.reset();
        form.clearErrors();
    }, 300);
};

const submitForm = () => {
    if (!form.url) return;

    isSubmitting.value = true;

    form.post(route('app.link.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Hızlı Link Oluştur</DialogTitle>
                <DialogDescription>
                    Kısaltılacak URL adresini girin ve oluştur butonuna tıklayın.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submitForm" class="space-y-4">
                <div class="space-y-2">
                    <Label for="url">URL Adresi</Label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <LinkIcon class="h-5 w-5 text-muted-foreground" />
                        </div>
                        <Input
                            id="url"
                            v-model="form.url"
                            type="url"
                            placeholder="https://..."
                            class="pl-10"
                            required
                        />
                    </div>
                    <div v-if="form.errors.url" class="text-sm text-destructive">
                        {{ form.errors.url }}
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeModal">
                        İptal
                    </Button>
                    <Button type="submit" :disabled="isSubmitting || !form.url">
                        {{ isSubmitting ? 'Oluşturuluyor...' : 'Oluştur' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
