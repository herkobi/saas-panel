<script setup lang="ts">
import { cn } from '@/lib/utils';
import { ChevronDown } from 'lucide-vue-next';
import { SelectTrigger, useForwardProps, type SelectTriggerProps } from 'radix-vue';
import { computed, type HTMLAttributes } from 'vue';
import { useSelectContext } from './SelectContext';

interface Props extends SelectTriggerProps {
  class?: HTMLAttributes['class'];
}

const props = defineProps<Props>();

const context = useSelectContext();

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;
  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <SelectTrigger
    v-bind="forwardedProps"
    :class="cn(
      'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1',
      props.class
    )"
  >
    <slot />
    <ChevronDown class="h-4 w-4 opacity-50" />
  </SelectTrigger>
</template>
