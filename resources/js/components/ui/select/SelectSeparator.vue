<script setup lang="ts">
import { cn } from '@/lib/utils';
import { SelectSeparator, useForwardProps, type SelectSeparatorProps } from 'radix-vue';
import { computed, type HTMLAttributes } from 'vue';

interface Props extends SelectSeparatorProps {
  class?: HTMLAttributes['class'];
}

const props = defineProps<Props>();

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;
  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <SelectSeparator v-bind="forwardedProps" :class="cn('-mx-1 my-1 h-px bg-muted', props.class)" />
</template>
