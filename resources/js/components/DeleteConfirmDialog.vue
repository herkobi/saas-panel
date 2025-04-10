<script setup lang="ts">
import { ref } from 'vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

const props = defineProps<{
  isOpen: boolean;
  title: string;
  description: string;
  confirmLabel?: string;
  cancelLabel?: string;
}>();

const emit = defineEmits<{
  'update:isOpen': [value: boolean];
  'confirm': [];
  'cancel': [];
}>();

const closeModal = () => {
  emit('update:isOpen', false);
  emit('cancel');
};

const confirmAction = () => {
  emit('confirm');
  emit('update:isOpen', false);
};
</script>

<template>
  <Dialog :open="isOpen" @update:open="(value) => emit('update:isOpen', value)">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ title }}</DialogTitle>
        <DialogDescription>{{ description }}</DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <Button variant="outline" @click="closeModal">
          {{ cancelLabel || 'İptal' }}
        </Button>
        <Button variant="destructive" @click="confirmAction">
          {{ confirmLabel || 'Sil' }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
