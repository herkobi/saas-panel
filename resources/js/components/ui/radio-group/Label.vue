<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

interface LabelProps {
  class?: HTMLAttributes['class'];
  htmlFor?: string;
}

const props = defineProps<LabelProps>();
</script>

<template>
  <label
    :for="htmlFor"
    :class="cn('text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70', props.class)"
  >
    <slot />
  </label>
</template>
